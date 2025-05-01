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

class AppointmentCalendarController extends Controller
{
    protected TransactionService $transactionService;

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

        $appointments = Appointment::query()
            ->where(function($query) use ($start, $end) {
                // Filter appointments with inspection date within the range
                $query->whereBetween('inspection_date', [$start->toDateString(), $end->toDateString()]);
            })
            // Optionally filter by status if needed
            // ->whereNotIn('inspection_status', ['Declined'])
            ->get();

        $events = $appointments->map(function (Appointment $appointment) {
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
            $inspectionDate = Carbon::parse($appointment->inspection_date);
            $inspectionTime = $appointment->inspection_time ? Carbon::parse($appointment->inspection_time) : null;
            
            // If we have a time, combine it with the date
            if ($inspectionTime) {
                $startTime = Carbon::parse($appointment->inspection_date)
                    ->setHour($inspectionTime->hour)
                    ->setMinute($inspectionTime->minute)
                    ->setSecond(0);
                
                // Add 1 hour for the end by default
                $endTime = $startTime->copy()->addHour();
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
            return response()->json(['message' => 'Invalid date format.', 'errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        try {
            $updatedAppointment = $this->transactionService->run(
                // 1. Database operations
                function () use ($id, $validatedData) {
                    $appointment = Appointment::findOrFail($id);
                    
                    // Parse new times
                    $newStartTime = Carbon::parse($validatedData['start']);
                    
                    // Update the inspection date
                    $appointment->inspection_date = $newStartTime->toDateString();
                    
                    // If the appointment has a specific time, also update the time
                    if (!$newStartTime->startOfDay()->eq($newStartTime)) {
                        $appointment->inspection_time = $newStartTime->format('H:i:s');
                    }
                    
                    // Check for conflicts with other appointments (optional)
                    $existingAppointment = Appointment::where('id', '!=', $appointment->id)
                        ->where('inspection_date', $appointment->inspection_date)
                        ->where('inspection_time', $appointment->inspection_time)
                        ->whereNotIn('inspection_status', ['Declined'])
                        ->first();

                    if ($existingAppointment) {
                        throw new \RuntimeException('Schedule conflict: Another appointment is already scheduled for this time.', 409);
                    }

                    // Mark the inspection as confirmed if it has a date and time
                    if ($appointment->inspection_date && $appointment->inspection_time) {
                        $appointment->inspection_confirmed = true;
                        $appointment->inspection_status = 'Confirmed';
                        $appointment->status_lead = 'Called';
                    }
                    
                    $appointment->save();

                    Log::info('Appointment updated via drag and drop in calendar', ['id' => $appointment->id]);
                    return $appointment;
                },
                // 2. Action to execute after completing the transaction
                function ($updatedAppointment) {
                    // Send notification via job
                    ProcessAppointmentEmail::dispatch($updatedAppointment, 'rescheduled');                    
                    Log::info('Rescheduling email job dispatched', ['id' => $updatedAppointment->id]);
                }
            );

            // If the transaction is successful
            return response()->json(['message' => 'Appointment updated successfully. A notification email has been sent to the client.']);

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
            return response()->json(['message' => 'Error updating the appointment in the calendar.'], 500);
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
                return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
            }

            $appointment = Appointment::findOrFail($id);
            $oldStatus = $appointment->inspection_status;
            $appointment->inspection_status = $status;
            
            // Update inspection_confirmed flag based on status
            if ($status === 'Confirmed') {
                $appointment->inspection_confirmed = true;
                $appointment->status_lead = 'Called';
            } else if ($status === 'Declined') {
                $appointment->inspection_confirmed = false;
                $appointment->status_lead = 'Declined';
                // Clear inspection date and time to free up the slot
                $appointment->inspection_date = null;
                $appointment->inspection_time = null;
            } else if ($status === 'Completed') {
                $appointment->status_lead = 'Called';
            }
            
            $appointment->save();

            // Determine message and email type based on status
            $message = 'Appointment status updated successfully.';
            $emailType = '';
            
            if ($status === 'Confirmed') {
                $emailType = 'confirmed';
                $message = 'Appointment confirmed successfully. A confirmation email has been sent to the client.';
            } else if ($status === 'Declined') {
                $emailType = 'declined';
                $message = 'Appointment declined successfully. A notification email has been sent to the client.';
            } else if ($status === 'Completed') {
                $emailType = 'completed';
                $message = 'Appointment marked as completed successfully.';
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
                    'message' => 'Client not found'
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
                    'message' => 'This time slot is already booked with another client.',
                    'errors' => [
                        'schedule_conflict' => 'Please select a different date or time for your inspection.'
                    ]
                ], 422);
            }

            // Update the appointment with new inspection details
            $oldStatus = $client->inspection_status;
            $client->inspection_date = $request->inspection_date;
            $client->inspection_time = $request->inspection_time;
            $client->inspection_status = $request->inspection_status;
            $client->inspection_confirmed = ($request->inspection_status === 'Confirmed');
            
            // Update lead status if appointment is confirmed
            if ($request->inspection_status === 'Confirmed') {
                $client->status_lead = 'Called';
            } else if ($request->inspection_status === 'Declined') {
                $client->status_lead = 'Declined';
                $client->inspection_confirmed = false;
            }
            
            $client->save();

            // Determine email type and message based on status
            $message = 'Appointment scheduled successfully.';
            $emailType = '';
            
            if ($request->inspection_status === 'Confirmed') {
                if ($oldStatus !== 'Confirmed') {
                    $emailType = 'confirmed';
                    $message = 'Appointment confirmed successfully. A confirmation email has been sent to the client.';
                } else {
                    $emailType = 'rescheduled';
                    $message = 'Appointment rescheduled successfully. A notification email has been sent to the client.';
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
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating appointment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch available clients for appointment scheduling
     */
    public function getClients()
    {
        try {
            $clients = Appointment::select('uuid', 'first_name', 'last_name', 'email', 'phone')
                ->whereNotNull('first_name')
                ->whereNotNull('email')
                ->whereNotIn('inspection_status', ['Declined'])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $clients
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching clients: ' . $e->getMessage()
            ], 500);
        }
    }
} 