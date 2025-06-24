<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Jobs\ProcessAppointmentEmail;
use Illuminate\Support\Facades\Cache;
use App\Traits\CacheTrait;
use App\Traits\PhoneDataFormatter;
use Illuminate\Support\Str;
use App\Jobs\ProcessNewLead;

class AppointmentCalendarController extends Controller
{
    use CacheTrait, PhoneDataFormatter;
    
    protected TransactionService $transactionService;
    public $search = '';
    public $sortField = 'inspection_date';
    public $sortDirection = 'asc';
    public $perPage = 100;
    public $showDeleted = false;
    protected $significantDataChange = false;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display the calendar view.
     */
    public function index()
    {
        // Simply return the calendar view
        return view('appointments.calendar'); 
    }

    /**
     * Fetch appointments as events for FullCalendar.
     */
    public function events(Request $request)
    {
        // Get the date range requested by FullCalendar (parameters start, end)
        // Or load all relevant appointments if a range is not initially provided
        $start = $request->query('start') ? Carbon::parse($request->query('start'))->startOfDay() : now()->subMonth();
        $end = $request->query('end') ? Carbon::parse($request->query('end'))->endOfDay() : now()->addMonth();
        
        // Create a cache key based on the date range
        $cacheKey = 'calendar_events_' . $start->format('Y-m-d') . '_' . $end->format('Y-m-d');
        
        // Register this cache key for future invalidation
        $this->trackCalendarCacheKey($cacheKey);
        
        // Cache the events for 60 minutes (adjust as needed)
        $events = Cache::remember($cacheKey, 60, function() use ($start, $end) {
            $appointments = Appointment::query()
                ->where(function($query) use ($start, $end) {
                    // Filter appointments with inspection date within the range
                    $query->whereBetween('inspection_date', [$start->toDateString(), $end->toDateString()]);
                })
                // Optionally filter by status if needed
                // ->whereNotIn('inspection_status', ['Declined'])
                ->get();
    
            return $appointments->map(function (Appointment $appointment) {
                // Color based on appointment status
                $color = '#3b82f6'; // Blue by default (pending)
                switch ($appointment->inspection_status) {
                    case 'Completed':
                        $color = '#10b981'; // Green
                        break;
                    case 'Confirmed':
                        $color = '#8B5CF6'; // Purple
                        break;
                    case 'Declined':
                        $color = '#ef4444'; // Red
                        break;
                    case 'Pending':
                        $color = '#f59e0b'; // Orange
                        break;
                }
    
                // Convert inspection_date and inspection_time to Carbon objects
                $inspectionDate = Carbon::parse((string) $appointment->inspection_date);
                $inspectionTime = $appointment->inspection_time ? Carbon::parse((string) $appointment->inspection_time) : null;
                
                // If we have a time, combine it with the date
                if ($inspectionTime) {
                    $startTime = Carbon::parse((string) $appointment->inspection_date)
                        ->setHour($inspectionTime->hour)
                        ->setMinute($inspectionTime->minute)
                        ->setSecond(0);
                    
                    // Add 2 hours for the end by default
                    $endTime = $startTime->copy()->addHours(3);
                } else {
                    // If there's no time, use all day
                    $startTime = $inspectionDate;
                    $endTime = $inspectionDate->copy()->addDay();
                }
    
                // Format the appointment for FullCalendar
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->first_name . ' ' . $appointment->last_name,
                    'start' => $startTime->toIso8601String(),
                    'end' => $endTime->toIso8601String(),
                    'color' => $color,
                    'allDay' => $inspectionTime ? false : true,
                    // Additional properties to display in the popup
                    'extendedProps' => [
                        'clientName' => $appointment->first_name . ' ' . $appointment->last_name,
                        'clientEmail' => $appointment->email,
                        'clientPhone' => $appointment->phone,
                        'status' => $appointment->inspection_status,
                        'leadStatus' => $appointment->status_lead,
                        'notes' => $appointment->notes,
                        'address' => $appointment->address . ', ' . $appointment->city . ', ' . $appointment->state . ' ' . $appointment->zipcode,
                        'message' => $appointment->message,
                        'damage' => $appointment->damage_detail,
                        'hasInsurance' => $appointment->insurance_property ? 'Yes' : 'No',
                        'latitude' => $appointment->latitude,
                        'longitude' => $appointment->longitude,
                    ]
                ];
            });
        });

        return response()->json($events);
    }

    /**
     * Update appointment time via drag-and-drop.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming start and end times
        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => __('appointment_calendar_invalid_date_format'), 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            $updatedAppointment = $this->transactionService->run(
                // 1. Database operations
                function () use ($id, $validatedData) {
                    $appointment = Appointment::findOrFail($id);
                    
                    // Parse new times
                    $newStartTime = Carbon::parse($validatedData['start']);
                    
                    // Update the inspection date and time
                    $appointment->inspection_date = $newStartTime->toDateString();
                    $appointment->inspection_time = $newStartTime->format('H:i:s');
                    
                    // Check for conflicts with other appointments
                    $existingAppointment = Appointment::where('id', '!=', $appointment->id)
                        ->where('inspection_date', $appointment->inspection_date)
                        ->where('inspection_time', $appointment->inspection_time)
                        ->whereNotIn('inspection_status', ['Declined'])
                        ->first();

                    if ($existingAppointment) {
                        throw new \RuntimeException(__('appointment_calendar_schedule_conflict'), 409);
                    }

                    // Mark the inspection as confirmed
                    $appointment->inspection_status = 'Confirmed';
                    $appointment->status_lead = 'Called';
                    
                    $appointment->save();

                    Log::info('Appointment updated via drag and drop in calendar', ['id' => $appointment->id]);
                    return $appointment;
                },
                // 2. Action to execute after completing the transaction
                function ($updatedAppointment) {
                    // Send notification via job
                    ProcessAppointmentEmail::dispatch($updatedAppointment, 'rescheduled');                    
                    Log::info('Rescheduling email job dispatched', ['id' => $updatedAppointment->id]);
                    
                    // Mark significant change and clear relevant caches
                    $this->significantDataChange = true;
                    $this->clearCache('appointments');
                    
                    // Clear calendar event cache for all potential date ranges that could include this appointment
                    // We'll use a pattern to clear all calendar event caches
                    $cacheKeys = Cache::get('calendar_event_keys', []);
                    foreach ($cacheKeys as $key) {
                        Cache::forget($key);
                    }
                    Cache::forget('calendar_event_keys');
                }
            );

            // If the transaction is successful
            return response()->json(['message' => __('appointment_calendar_updated_successfully')]);

        } catch (\RuntimeException $re) {
            // Capture specific errors thrown from the transaction
            Log::warning('Business logic error during calendar update: ' . $re->getMessage(), [
                'id' => $id,
                'code' => $re->getCode()
            ]);
            return response()->json(['message' => $re->getMessage()], $re->getCode() ?: 422);
        } catch (Throwable $e) {
            // Capture any other error during the transaction
            Log::error('Error during appointment update in calendar: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e
            ]);
            return response()->json(['message' => __('appointment_calendar_error_updating')], 500);
        }
    }

    /**
     * Change appointment status (confirm/decline)
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $status = $request->input('status');
            // Only allow valid values based on the database schema
            if (!in_array($status, ['Confirmed', 'Completed', 'Pending', 'Declined'])) {
                return response()->json(['success' => false, 'message' => __('appointment_calendar_invalid_status')], 400);
            }

            $appointment = Appointment::findOrFail($id);
            $oldStatus = $appointment->inspection_status;
            $appointment->inspection_status = $status;
            
            // Update status_lead based on inspection_status
            if ($status === 'Confirmed' || $status === 'Completed') {
                $appointment->status_lead = 'Called';
            } else if ($status === 'Declined') {
                $appointment->status_lead = 'Declined';
                // Clear inspection date and time to free up the slot
                $appointment->inspection_date = null;
                $appointment->inspection_time = null;
            } else if ($status === 'Pending') {
                if ($appointment->status_lead !== 'Pending') {
                    $appointment->status_lead = 'New';
                }
            }
            
            // If status is not Confirmed or Completed, but date/time are set,
            // ensure inspection_status is set to Confirmed
            if (($status !== 'Confirmed' && $status !== 'Completed') && 
                $appointment->inspection_date && $appointment->inspection_time) {
                $appointment->inspection_status = 'Confirmed';
                $appointment->status_lead = 'Called';
            }
            
            $appointment->save();
            
            // Clear cache
            $this->significantDataChange = true;
            $this->clearCache('appointments');
            
            // Clear calendar event cache
            $cacheKeys = Cache::get('calendar_event_keys', []);
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            Cache::forget('calendar_event_keys');

            // Determine message and email type based on status
            $message = __('appointment_calendar_status_updated');
            $emailType = '';
            
            if ($appointment->inspection_status === 'Confirmed') {
                $emailType = 'confirmed';
                $message = __('appointment_calendar_confirmed_successfully');
            } else if ($appointment->inspection_status === 'Declined') {
                $emailType = 'declined';
                $message = __('appointment_calendar_declined_successfully');
            } else if ($appointment->inspection_status === 'Completed') {
                $emailType = 'completed';
                $message = __('appointment_calendar_completed_successfully');
            }
            
            // Send notification email if needed
            if (!empty($emailType)) {
                ProcessAppointmentEmail::dispatch($appointment, $emailType);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'appointment' => $appointment
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a new appointment from the calendar view
     */
    public function create(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'client_uuid' => 'required|exists:appointments,uuid',
                'inspection_date' => 'required|date|after_or_equal:today',
                'inspection_time' => 'required|date_format:H:i',
                'inspection_status' => 'required|in:Confirmed,Pending'
            ]);

            // Find the client (existing appointment)
            $client = Appointment::where('uuid', $request->client_uuid)->first();
            if (!$client) {
                return response()->json([
                'success' => false,
                'message' => __('appointment_calendar_client_not_found')
            ], 404);
            }

            // Check for schedule conflicts
            $conflictCheck = Appointment::where('inspection_date', $request->inspection_date)
                ->where('inspection_time', $request->inspection_time)
                ->whereNotIn('inspection_status', ['Declined', 'Cancelled'])
                ->where('uuid', '!=', $client->uuid)
                ->exists();

            if ($conflictCheck) {
                return response()->json([
                    'success' => false,
                    'message' => __('appointment_calendar_time_slot_booked'),
                    'errors' => [
                        'schedule_conflict' => __('appointment_calendar_select_different_time')
                    ]
                ], 422);
            }

            // Update the appointment with new inspection details
            $oldStatus = $client->inspection_status;
            $client->inspection_date = $request->inspection_date;
            
            // Both date and time must always be provided together
            if (empty($request->inspection_date) && !empty($request->inspection_time)) {
                return response()->json([
                    'success' => false,
                    'message' => __('appointment_calendar_validation_error'),
                    'errors' => [
                        'inspection_date' => [__('appointment_calendar_date_required_with_time')]
                    ]
                ], 422);
            }
            
            if (!empty($request->inspection_date) && empty($request->inspection_time)) {
                return response()->json([
                    'success' => false,
                    'message' => __('appointment_calendar_validation_error'),
                    'errors' => [
                        'inspection_time' => [__('appointment_calendar_time_required_with_date')]
                    ]
                ], 422);
            }
            
            $client->inspection_time = $request->inspection_time;
            
            // Ensure consistent status handling
            // If both date and time are provided, always set status to Confirmed
            if (!empty($client->inspection_date) && !empty($client->inspection_time)) {
                $client->inspection_status = 'Confirmed';
                $client->status_lead = 'Called';
            } else {
                $client->inspection_status = $request->inspection_status;
                
                // Set status_lead based on inspection_status
                if ($client->inspection_status === 'Confirmed') {
                    $client->status_lead = 'Called';
                } else if ($client->inspection_status === 'Declined') {
                    $client->status_lead = 'Declined';
                } else if ($client->inspection_status === 'Pending') {
                    if ($client->status_lead !== 'Pending') {
                        $client->status_lead = 'New';
                    }
                }
            }
            
            $client->save();
            
            // Clear cache after updating
            $this->significantDataChange = true;
            $this->clearCache('appointments');
            
            // Clear calendar event cache
            $cacheKeys = Cache::get('calendar_event_keys', []);
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            Cache::forget('calendar_event_keys');

            // Determine email type and message based on status
            $message = __('appointment_calendar_scheduled_successfully');
            $emailType = '';
            
            if ($client->inspection_status === 'Confirmed') {
                if ($oldStatus !== 'Confirmed') {
                    $emailType = 'confirmed';
                    $message = __('appointment_calendar_confirmed_successfully');
                } else {
                    $emailType = 'rescheduled';
                    $message = __('appointment_calendar_rescheduled_successfully');
                }
            }
            
            // Send notification email if needed
            if (!empty($emailType)) {
                ProcessAppointmentEmail::dispatch($client, $emailType);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'appointment' => $client
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('appointment_calendar_validation_error'),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('appointment_calendar_error_creating') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch available clients for appointment scheduling
     */
    public function getClients()
    {
        try {
            // Create a cache key for clients list
            $cacheKey = 'calendar_available_clients';
            
            // Cache the clients list for 10 minutes
            $clients = Cache::remember($cacheKey, 10, function() {
                return Appointment::select('uuid', 'first_name', 'last_name', 'email', 'phone')
                    ->whereNotNull('first_name')
                    ->whereNotNull('email')
                    ->where(function($query) {
                        $query->whereNull('inspection_date')
                              ->orWhereNull('inspection_time');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $clients
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('appointment_calendar_error_fetching_clients') . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Store a new lead from the calendar modal
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\'-]+$/'],
                'last_name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s\'-]+$/'],
                'email' => 'required|email|max:255|unique:appointments,email',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:255',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
            ], [
                'first_name.required' => __('first_name_required'),
                'first_name.regex' => __('first_name_regex'),
                'last_name.required' => __('last_name_required'),
                'last_name.regex' => __('last_name_regex'),
                'email.required' => __('email_required'),
                'email.email' => __('email_invalid'),
                'email.unique' => __('email_unique'),
                'phone.required' => __('phone_required'),
                'address.required' => __('address_required'),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => __('appointment_calendar_validation_error'),
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            // Format phone number
            $validatedData['phone'] = $this->formatPhone($validatedData['phone']);

            // Check for duplicate phone after formatting
            $existingPhone = Appointment::where('phone', $validatedData['phone'])->first();
            if ($existingPhone) {
                return response()->json([
                    'success' => false,
                    'message' => __('appointment_calendar_validation_error'),
                    'errors' => ['phone' => [__('phone_unique')]]
                ], 422);
            }

            // Create the appointment
            $appointment = new Appointment();
            $appointment->uuid = Str::uuid();
            $appointment->first_name = $validatedData['first_name'];
            $appointment->last_name = $validatedData['last_name'];
            $appointment->email = $validatedData['email'];
            $appointment->phone = $validatedData['phone'];
            $appointment->address = $validatedData['address'];
            $appointment->latitude = $validatedData['latitude'] ?? null;
            $appointment->longitude = $validatedData['longitude'] ?? null;
            $appointment->lead_source = 'Website';
            $appointment->status_lead = 'New';
            $appointment->inspection_status = 'Pending';
            $appointment->registration_date = now();
            $appointment->save();

            // Clear cache
            $this->significantDataChange = true;
            $this->clearCache('appointments');
            
            // Clear calendar event cache
            $cacheKeys = Cache::get('calendar_event_keys', []);
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
            Cache::forget('calendar_event_keys');

            // Dispatch new lead processing job
            ProcessNewLead::dispatch($appointment);

            return response()->json([
                'success' => true,
                'message' => __('appointment_calendar_lead_created_successfully'),
                'appointment' => $appointment
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating lead from calendar: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('appointment_calendar_error_creating') . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if email exists in appointments
     */
    public function checkEmailExists(Request $request)
    {
        $email = $request->input('email');
        $excludeUuid = $request->input('exclude_uuid');
        
        $query = Appointment::where('email', $email);
        
        if ($excludeUuid) {
            $query->where('uuid', '!=', $excludeUuid);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? __('email_unique') : null
        ]);
    }

    /**
     * Check if phone exists in appointments
     */
    public function checkPhoneExists(Request $request)
    {
        $phone = $request->input('phone');
        $excludeUuid = $request->input('exclude_uuid');
        
        // Format the phone number for consistent checking
        $formattedPhone = $this->formatPhone($phone);
        
        $query = Appointment::where('phone', $formattedPhone);
        
        if ($excludeUuid) {
            $query->where('uuid', '!=', $excludeUuid);
        }
        
        $exists = $query->exists();
        
        return response()->json([
            'exists' => $exists,
            'message' => $exists ? __('phone_unique') : null,
            'formatted_phone' => $this->formatPhoneForDisplay($formattedPhone)
        ]);
    }

    /**
     * Track calendar event cache keys for efficient invalidation
     */
    private function trackCalendarCacheKey($key)
    {
        $cacheKeys = Cache::get('calendar_event_keys', []);
        if (!in_array($key, $cacheKeys)) {
            $cacheKeys[] = $key;
            Cache::put('calendar_event_keys', $cacheKeys, 60 * 24); // Store for 24 hours
        }
    }
}