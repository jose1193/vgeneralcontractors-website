{{-- 
    REFACTORED CALENDAR VIEW
    This is the simplified main calendar view that includes modular components
--}}
<x-app-layout>
    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                            {{ __('appointment_calendar_title') }}
                        </h2>
                        <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                            {{ __('appointment_calendar_subtitle') }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main content area --}}
        <div class="py-2 sm:py-4 md:py-2 lg:py-2">
            <div class="max-w-7xl mx-auto py-2 px-4 sm:py-4 sm:px-6 lg:px-8">
                <!-- Main container -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg">
                    <div class="p-6">
                        <!-- Main Calendar Container -->
                        @include('appointments.partials.calendar-container')

                        <!-- Event Detail Modal -->
                        @include('appointments.partials.event-detail-modal')

                        <!-- New Appointment Modal -->
                        @include('appointments.partials.new-appointment-modal')

                        {{-- Push JavaScript to the stack --}}
                        @push('scripts')
                            <!-- FullCalendar CSS -->
                            <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

                            <!-- jQuery (required dependency) -->
                            <script src="https://code.jquery.com/jquery-3.7.1.min.js"
                                integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

                            <!-- SweetAlert2 (required dependency) -->
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>

                            <!-- FullCalendar JS -->
                            <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

                            <!-- Tippy.js for tooltips -->
                            <script src="https://unpkg.com/@popperjs/core@2"></script>
                            <script src="https://unpkg.com/tippy.js@6"></script>

                            <!-- Google Maps API -->
                            <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"
                                async defer></script>

                            <!-- Calendar JavaScript Modules -->
                            <script src="{{ asset('js/calendar-utils.js') }}"></script>
                            <script src="{{ asset('js/calendar-config.js') }}"></script>
                            <script src="{{ asset('js/calendar-api.js') }}"></script>
                            <script src="{{ asset('js/calendar-events.js') }}"></script>
                            <script src="{{ asset('js/calendar-modals.js') }}"></script>
                            <script src="{{ asset('js/calendar-main.js') }}"></script>

                            <!-- Initialize translations for JavaScript -->
                            <script>
                                // Global translations object for JavaScript modules
                                window.translations = {
                                    // General
                                    'success': @json(__('Success')),
                                    'error': @json(__('Error')),
                                    'cancel': @json(__('Cancel')),
                                    'processing': @json(__('Processing')),
                                    'loading': @json(__('Loading')),
                                    'saving': @json(__('Saving')),
                                    'delete': @json(__('Delete')),
                                    'edit': @json(__('Edit')),
                                    'view': @json(__('View')),
                                    'close': @json(__('Close')),

                                    // Calendar specific
                                    'please_select_client': @json(__('Please select a client')),
                                    'appointment_created_successfully': @json(__('Appointment created successfully')),
                                    'appointment_updated_successfully': @json(__('Appointment updated successfully')),
                                    'appointment_deleted_successfully': @json(__('Appointment deleted successfully')),
                                    'unexpected_error': @json(__('An unexpected error occurred')),

                                    // Appointment actions
                                    'reschedule_appointment': @json(__('Reschedule Appointment')),
                                    'move_appointment_to': @json(__('Move appointment to {newTime}')),
                                    'yes_move': @json(__('Yes, move it')),
                                    'moved': @json(__('Moved!')),
                                    'could_not_update_appointment': @json(__('Could not update appointment')),

                                    // Confirm/Decline
                                    'confirm_appointment_title': @json(__('Confirm Appointment')),
                                    'confirm_appointment_text': @json(__('Are you sure you want to confirm this appointment?')),
                                    'yes_confirm': @json(__('Yes, confirm')),
                                    'confirmed': @json(__('Confirmed!')),
                                    'could_not_confirm_appointment': @json(__('Could not confirm appointment')),

                                    'decline_appointment_title': @json(__('Decline Appointment')),
                                    'decline_appointment_text': @json(__('Are you sure you want to decline this appointment?')),
                                    'yes_decline': @json(__('Yes, decline')),
                                    'declined': @json(__('Declined!')),
                                    'could_not_decline_appointment': @json(__('Could not decline appointment')),

                                    // Form validation
                                    'field_required': @json(__('This field is required')),
                                    'invalid_email': @json(__('Please enter a valid email address')),
                                    'invalid_phone': @json(__('Please enter a valid phone number')),
                                    'time_slot_unavailable': @json(__('This time slot is already booked')),

                                    // Client management
                                    'no_clients_available': @json(__('No clients available')),
                                    'loading_clients': @json(__('Loading clients...')),
                                    'client_load_error': @json(__('Error loading clients')),

                                    // Date/Time
                                    'select_date_time': @json(__('Please select a date and time')),
                                    'invalid_date': @json(__('Please select a valid date')),
                                    'invalid_time': @json(__('Please select a valid time')),
                                    'past_date_error': @json(__('Cannot schedule appointments in the past')),

                                    // Sharing
                                    'link_copied': @json(__('Link copied to clipboard')),
                                    'copy_failed': @json(__('Failed to copy link')),
                                    'share_location': @json(__('Share Location')),
                                    'open_in_maps': @json(__('Open in Maps')),

                                    // Status messages
                                    'loading_events': @json(__('Loading events...')),
                                    'no_events_found': @json(__('No events found')),
                                    'calendar_refresh': @json(__('Calendar refreshed')),

                                    // Keyboard shortcuts
                                    'keyboard_shortcuts_help': @json(__('Press ESC to close modals, F5 to refresh, Ctrl+N for new appointment'))
                                };

                                // Language change prevention fix
                                document.addEventListener('click', function(e) {
                                    const target = e.target.closest('a[href*="/lang/"]');
                                    if (target) {
                                        e.preventDefault();
                                        e.stopImmediatePropagation();
                                        window.location.href = target.href;
                                        return false;
                                    }
                                }, true);

                                // Enhanced debug script to identify module loading issues
                                setTimeout(() => {
                                    console.log('=== ENHANCED CALENDAR DEBUG INFO ===');

                                    // Check basic dependencies
                                    console.log('Dependencies Check:');
                                    console.log('- Calendar element exists:', !!document.getElementById('calendar'));
                                    console.log('- FullCalendar loaded:', typeof FullCalendar !== 'undefined');
                                    console.log('- jQuery loaded:', typeof $ !== 'undefined');
                                    console.log('- SweetAlert2 loaded:', typeof Swal !== 'undefined');
                                    console.log('- Tippy.js loaded:', typeof tippy !== 'undefined');

                                    // Check calendar modules
                                    console.log('Calendar Modules Check:');
                                    console.log('- CalendarUtils:', typeof CalendarUtils !== 'undefined' ? '✓' : '✗');
                                    console.log('- CalendarConfig:', typeof CalendarConfig !== 'undefined' ? '✓' : '✗');
                                    console.log('- CalendarEvents:', typeof CalendarEvents !== 'undefined' ? '✓' : '✗');
                                    console.log('- CalendarModals:', typeof CalendarModals !== 'undefined' ? '✓' : '✗');
                                    console.log('- CalendarAPI:', typeof CalendarAPI !== 'undefined' ? '✓' : '✗');
                                    console.log('- CalendarMain:', typeof CalendarMain !== 'undefined' ? '✓' : '✗');

                                    // Check window objects
                                    console.log('Window Objects:');
                                    console.log('- window.CalendarUtils:', !!window.CalendarUtils ? '✓' : '✗');
                                    console.log('- window.CalendarConfig:', !!window.CalendarConfig ? '✗' : '✗');
                                    console.log('- window.CalendarEvents:', !!window.CalendarEvents ? '✗' : '✗');
                                    console.log('- window.CalendarModals:', !!window.CalendarModals ? '✗' : '✗');
                                    console.log('- window.CalendarAPI:', !!window.CalendarAPI ? '✗' : '✗');
                                    console.log('- window.calendarMain:', !!window.calendarMain ? '✓' : '✗');

                                    if (window.calendarMain) {
                                        console.log('Calendar Main Status:', window.calendarMain.getStatus());
                                    }

                                    // Check if calendar has any visible content
                                    const calendarEl = document.getElementById('calendar');
                                    if (calendarEl) {
                                        console.log('Calendar Element Info:', {
                                            width: calendarEl.offsetWidth,
                                            height: calendarEl.offsetHeight,
                                            display: getComputedStyle(calendarEl).display,
                                            visibility: getComputedStyle(calendarEl).visibility,
                                            innerHTML_length: calendarEl.innerHTML.length
                                        });
                                    }

                                    // Check for JavaScript errors
                                    console.log('Checking for script errors...');

                                    // Try to manually verify each module
                                    try {
                                        if (typeof CalendarUtils !== 'undefined') {
                                            console.log('CalendarUtils test:', CalendarUtils.formatPhoneNumber('1234567890'));
                                        }
                                    } catch (e) {
                                        console.error('CalendarUtils error:', e);
                                    }

                                    console.log('=== END ENHANCED DEBUG INFO ===');
                                }, 3000);
                            </script>
                        @endpush

                        {{-- Push CSS to the stack --}}
                        @push('styles')
                            <style>
                                /* Additional calendar-specific styles that need to be inline */
                                .fc-event-dragging .fc-event {
                                    opacity: 0.75;
                                }

                                .fc-event-resizing .fc-event {
                                    opacity: 0.75;
                                }

                                /* Loading state */
                                .calendar-loading {
                                    position: relative;
                                }

                                .calendar-loading::before {
                                    content: '';
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background: rgba(255, 255, 255, 0.8);
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    z-index: 1000;
                                }

                                /* Temporary selection event styling */
                                .temp-selection-event {
                                    opacity: 0.8 !important;
                                    border: 2px dashed #1d4ed8 !important;
                                    background: linear-gradient(45deg, #3b82f6, #60a5fa) !important;
                                }

                                .temp-selection-event .fc-event-title {
                                    font-weight: bold !important;
                                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3) !important;
                                }

                                /* Print styles */
                                @media print {
                                    .fc-header-toolbar {
                                        display: none;
                                    }

                                    .fc-button-group {
                                        display: none;
                                    }
                                }
                            </style>
                        @endpush
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
