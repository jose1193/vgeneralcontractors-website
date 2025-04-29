@extends('layouts.app')

@section('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        .fc-event {
            cursor: pointer;
        }

        .fc-event-pending {
            background-color: #f59e0b;
            border-color: #d97706;
        }

        .fc-event-scheduled {
            background-color: #3b82f6;
            border-color: #2563eb;
        }

        .fc-event-completed {
            background-color: #10b981;
            border-color: #059669;
        }

        .fc-event-cancelled {
            background-color: #ef4444;
            border-color: #dc2626;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Appointments Calendar</h1>
            <div class="flex space-x-4">
                <a href="{{ route('appointments.index') }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    List View
                </a>
                <button id="addAppointmentBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                    Add New Appointment
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <div id="calendar"></div>
        </div>

        <!-- Appointment Details Modal -->
        <div id="appointmentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 id="detailsModalTitle" class="text-lg font-medium text-gray-900"></h3>
                        <button id="closeDetailsModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div id="appointmentDetails" class="p-6">
                        <!-- Details will be loaded here -->
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button id="closeDetailsBtn"
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the appointment modal from index.blade.php -->
    @include('components.crud.modal', [
        'id' => 'appointmentModal',
        'title' => 'Appointment Details',
        'formId' => 'appointmentForm',
    ])

    @push('scripts')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: function(info, successCallback, failureCallback) {
                        $.ajax({
                            url: '{{ route('appointments.calendar.events') }}',
                            type: 'GET',
                            data: {
                                start_date: info.startStr,
                                end_date: info.endStr
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Add status class to events
                                    const events = response.data.map(event => {
                                        return {
                                            ...event,
                                            className: `fc-event-${event.status.toLowerCase()}`
                                        };
                                    });
                                    successCallback(events);
                                } else {
                                    failureCallback(new Error('Could not load events'));
                                }
                            },
                            error: function(error) {
                                failureCallback(new Error('AJAX call failed'));
                            }
                        });
                    },
                    eventClick: function(info) {
                        const appointmentId = info.event.id;

                        // Load appointment details and show edit modal
                        $.ajax({
                            url: `/appointments/${appointmentId}/edit`,
                            type: 'GET',
                            success: function(response) {
                                if (response.success) {
                                    const appointment = response.entity;

                                    // Populate form fields
                                    $('#appointmentId').val(appointment.id);
                                    $('#first_name').val(appointment.first_name);
                                    $('#last_name').val(appointment.last_name);
                                    $('#email').val(appointment.email);
                                    $('#phone').val(appointment.phone);
                                    $('#address').val(appointment.address);
                                    $('#city').val(appointment.city);
                                    $('#state').val(appointment.state);
                                    $('#zipcode').val(appointment.zipcode);
                                    $('#inspection_status').val(appointment.inspection_status);
                                    $('#message').val(appointment.message);
                                    $('#notes').val(appointment.notes);

                                    // Format date for datetime-local input
                                    if (appointment.inspection_date && appointment
                                        .inspection_time) {
                                        $('#inspection_date').val(appointment.inspection_date);
                                        $('#inspection_time').val(appointment.inspection_time);
                                    }

                                    // Update modal title and button text
                                    $('#modalTitle').text('Edit Appointment');
                                    $('#saveBtn').find('.button-text').text(
                                        'Update Appointment');

                                    // Show the modal
                                    $('#appointmentModal').removeClass('hidden').addClass(
                                        'flex');
                                }
                            }
                        });
                    }
                });

                calendar.render();

                // Add New Appointment button
                $('#addAppointmentBtn').on('click', function() {
                    // Reset form
                    $('#appointmentForm')[0].reset();
                    $('#appointmentId').val('');

                    // Update modal title and button text
                    $('#modalTitle').text('Add New Appointment');
                    $('#saveBtn').find('.button-text').text('Create Appointment');

                    // Show the modal
                    $('#appointmentModal').removeClass('hidden').addClass('flex');
                });
            });
        </script>
    @endpush
@endsection
