<x-app-layout>
    {{-- Commenting out Jetstream header to avoid duplicate headers --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Appointment Calendar') }}
        </h2>
    </x-slot> --}}

    {{-- Dark background container with consistent styling --}}
    <div class="min-h-screen bg-gray-900" style="background-color: #141414;">
        {{-- Header section with title and subtitle --}}
        <div class="p-4 sm:p-6">
            <div class="mb-4 sm:mb-8 text-center sm:text-center md:text-left lg:text-left">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                    <div>
                        <h2 class="text-base sm:text-base md:text-2xl lg:text-2xl font-bold text-white mb-2">
                            {{ __('appointment_calendar_title') }}</h2>
                        <p class="text-base sm:text-base md:text-base lg:text-base text-gray-400">
                            {{ __('appointment_calendar_subtitle') }}
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button id="openCreateLeadModal" type="button"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('Create New Lead') }}
                        </button>
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
                        {{-- Calendar Container --}}
                        <div class="bg-white dark:bg-gray-800 shadow-md rounded p-4 mb-4">
                            <div id='calendar'></div>
                        </div>

                        {{-- Modal para detalles del evento (usando HTML/Tailwind básico) --}}
                        <div id="eventDetailModal" class="fixed z-50 inset-0 overflow-y-auto hidden"
                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div
                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                    aria-hidden="true">
                                </div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                    aria-hidden="true">&#8203;</span>
                                <div
                                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                                        {{-- Modal Header - Keep the client name here and improve formatting --}}
                                        <div
                                            class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100"
                                                id="modalEventTitle"></h3>
                                            <button type="button" id="closeEventModalBtn"
                                                class="rounded-full bg-red-600 p-2 inline-flex items-center justify-center text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                <span class="sr-only">Close</span>
                                            </button>
                                        </div>
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <div class="mt-2 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{-- Remove the client line since it's redundant with the header --}}
                                                    <p><strong>{{ __('email') }}:</strong> <span
                                                            id="modalEventEmail"></span></p>
                                                    <p><strong>{{ __('phone') }}:</strong> <span
                                                            id="modalEventPhone"></span></p>
                                                    <p><strong>{{ __('date_time') }}:</strong> <span
                                                            id="modalEventDateTime"></span></p>

                                                    {{-- Group status information together --}}
                                                    <div class="flex flex-col md:flex-row md:gap-6 my-2">
                                                        <p><strong>{{ __('appointment_status') }}:</strong> <span
                                                                id="modalEventStatus"
                                                                class="px-2 py-1 text-xs font-bold rounded-full"></span>
                                                        </p>
                                                        <p><strong>{{ __('lead_status') }}:</strong> <span
                                                                id="modalEventLeadStatus"
                                                                class="px-2 py-1 text-xs font-bold rounded-full"></span>
                                                        </p>
                                                    </div>

                                                    <p><strong>{{ __('address') }}:</strong> <span
                                                            id="modalEventAddress" class="whitespace-pre-wrap"></span>
                                                    </p>

                                                    {{-- Google Maps sharing buttons --}}
                                                    <div class="mt-2 flex flex-wrap gap-2">
                                                        <a href="#" id="share-whatsapp"
                                                            class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">
                                                            <svg class="h-5 w-5 mr-1" fill="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path
                                                                    d="M17.498 14.382l-1.87-1.147c-0.308-0.24-0.705-0.242-1.058-0.046l-1.103 0.69c-0.26 0.16-0.563 0.217-0.858 0.147c-0.893-0.216-2.404-1.511-3.122-2.251c-0.483-0.502-1.038-1.489-1.254-2.362c-0.09-0.351 0.014-0.721 0.269-0.974l0.74-0.73c0.243-0.241 0.37-0.567 0.354-0.904l-0.075-1.78c-0.035-0.843-0.913-1.384-1.693-1.041l-0.807 0.353c-0.905 0.405-1.467 1.268-1.457 2.241c0.01 0.935 0.307 3.375 2.301 6.123c2.035 2.809 4.372 3.526 5.338 3.628c0.975 0.103 1.926-0.621 2.251-1.505l0.282-0.861c0.256-0.788-0.343-1.623-1.238-1.724z" />
                                                            </svg>
                                                            {{ __('whatsapp') }}
                                                        </a>
                                                        <a href="#" id="share-email"
                                                            class="inline-flex items-center px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                                                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                            {{ __('email_share') }}
                                                        </a>
                                                        <a href="#" id="share-maps"
                                                            class="inline-flex items-center px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600">
                                                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                            {{ __('open_in_maps') }}
                                                        </a>
                                                        <button id="copy-address"
                                                            class="inline-flex items-center px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                                                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                            </svg>
                                                            {{ __('copy_link') }}
                                                        </button>
                                                    </div>

                                                    {{-- Hidden fields for coordinates --}}
                                                    <input type="hidden" id="event-latitude" name="latitude"
                                                        value="">
                                                    <input type="hidden" id="event-longitude" name="longitude"
                                                        value="">

                                                    <p><strong>{{ __('notes') }}:</strong> <span
                                                            id="modalEventNotes" class="whitespace-pre-wrap"></span>
                                                    </p>
                                                    <p><strong>{{ __('damage') }}:</strong> <span
                                                            id="modalEventDamage" class="whitespace-pre-wrap"></span>
                                                    </p>
                                                    <p><strong>{{ __('has_insurance') }}:</strong> <span
                                                            id="modalEventInsurance"
                                                            class="px-2 py-1 text-xs font-semibold rounded-full"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row justify-center">
                                        {{-- Botones de acción para citas --}}
                                        <div id="statusActionButtons" class="flex space-x-4">
                                            <button type="button" id="confirmAppointmentBtn"
                                                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:text-sm">
                                                <span class="normal-btn-text">{{ __('confirm_appointment') }}</span>
                                                <span class="processing-btn-text hidden">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    {{ __('processing') }}
                                                </span>
                                            </button>
                                            <button type="button" id="declineAppointmentBtn"
                                                class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:text-sm">
                                                <span class="normal-btn-text">{{ __('decline_appointment') }}</span>
                                                <span class="processing-btn-text hidden">
                                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12"
                                                            r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor"
                                                            d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                        </path>
                                                    </svg>
                                                    {{ __('processing') }}
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Fin del Modal --}}

                        {{-- Modal for creating a new appointment --}}
                        <div id="newAppointmentModal" class="fixed z-50 inset-0 overflow-y-auto hidden"
                            aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div
                                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                    aria-hidden="true">
                                </div>
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                    aria-hidden="true">&#8203;</span>
                                <div
                                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                                        {{-- Close button --}}
                                        <button type="button" id="closeNewAppointmentModalBtn"
                                            class="absolute top-2 right-2 rounded-full bg-red-600 p-2 inline-flex items-center justify-center text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="sr-only">Close</span>
                                        </button>
                                        <div class="sm:flex sm:items-start">
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3
                                                    class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">
                                                    {{ __('create_new_appointment') }}
                                                </h3>
                                                <div class="mt-4 space-y-4">
                                                    <form id="newAppointmentForm">
                                                        @csrf
                                                        {{-- Selected Date/Time (readonly) --}}
                                                        <div>
                                                            <label for="selectedDateTime"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                {{ __('date_time') }}
                                                            </label>
                                                            <input type="text" id="selectedDateTime"
                                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                                readonly>
                                                            <input type="hidden" id="appointmentDate"
                                                                name="inspection_date">
                                                            <input type="hidden" id="appointmentTime"
                                                                name="inspection_time">
                                                        </div>

                                                        {{-- Client Selector --}}
                                                        <div class="mt-3">
                                                            <label for="clientSelector"
                                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                                {{ __('select_client') }}
                                                            </label>
                                                            <select id="clientSelector" name="client_id" required
                                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                                <option value="">{{ __('please_select_client') }}</option>
                                                            </select>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row justify-center">
                                        <button type="button" id="createAppointmentBtn"
                                            class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                            <span class="normal-btn-text">{{ __('create_confirmed_appointment') }}</span>
                                            <span class="processing-btn-text hidden">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                {{ __('processing') }}
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- End of new appointment modal --}}
                    </div>
                </div>
            </div>

            @push('styles')
                {{-- Use a specific version of FullCalendar --}}
                <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
                {{-- Add meta CSRF token if not in main layout --}}
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <style>
                    /* Optional: Customize calendar appearance */
                    #calendar {
                        max-width: 1100px;
                        margin: 20px auto;
                        padding: 0 10px;
                    }

                    /* Style for event tooltips (using tippy.js) */
                    .tippy-box[data-theme~='light-border'] {
                        font-size: 0.85rem;
                    }

                    .tippy-box[data-theme~='light-border'] .tippy-content {
                        padding: 0.5rem;
                    }

                    /* Mejoras para la visualización de eventos */
                    .fc-event {
                        font-size: 0.75rem !important;
                        /* Reduce tamaño de fuente */
                        line-height: 1.2 !important;
                        /* Reduce espacio entre líneas */
                    }

                    /* Estilo para el contenido personalizado de eventos */
                    .fc-event-content-custom {
                        width: 100%;
                        padding: 1px 2px !important;
                    }

                    /* Cliente (primera línea) */
                    .client-title {
                        font-weight: bold;
                        font-size: 0.8rem;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        margin-bottom: 2px;
                    }

                    /* Horario (segunda línea) */
                    .event-time {
                        font-size: 0.7rem;
                        opacity: 0.85;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                        margin-bottom: 2px;
                    }

                    /* Estado (última línea) */
                    .appointment-status {
                        font-size: 0.7rem;
                        opacity: 0.9;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    /* Ocultamos el título nativo para que no se duplique */
                    .fc-event-title-container,
                    .fc-event-time {
                        display: none !important;
                    }

                    /* Mejora del layout de celdas */
                    .fc-timegrid-event-harness {
                        margin-left: 1px !important;
                        margin-right: 1px !important;
                    }

                    .fc-timegrid-event {
                        padding: 1px 2px !important;
                    }
                </style>
            @endpush

            @push('scripts')
                {{-- FullCalendar JS --}}
                <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
                <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/locales/es.global.js'></script>

                {{-- Tooltip library (Tippy.js) --}}
                <script src="https://unpkg.com/@popperjs/core@2"></script>
                <script src="https://unpkg.com/tippy.js@6"></script>

                {{-- Ensure jQuery and SweetAlert are loaded (usually in app.blade.php or here) --}}
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

                <script>
                    // JavaScript translations for SweetAlert
                    const translations = {
                        please_select_client: "{{ __('please_select_client') }}",
                        appointment_created_successfully: "{{ __('appointment_created_successfully') }}",
                        success: "{{ __('success') }}",
                        error: "{{ __('error') }}",
                        unexpected_error: "{{ __('unexpected_error') }}",
                        reschedule_appointment: "{{ __('reschedule_appointment') }}",
                        move_appointment_to: "{{ __('move_appointment_to') }}",
                        yes_move: "{{ __('yes_move') }}",
                        cancel: "{{ __('cancel') }}",
                        moved: "{{ __('moved') }}",
                        could_not_update_appointment: "{{ __('could_not_update_appointment') }}",
                        confirm_appointment_title: "{{ __('confirm_appointment_title') }}",
                        confirm_appointment_text: "{{ __('confirm_appointment_text') }}",
                        yes_confirm: "{{ __('yes_confirm') }}",
                        confirmed: "{{ __('confirmed') }}",
                        could_not_confirm_appointment: "{{ __('could_not_confirm_appointment') }}",
                        decline_appointment_title: "{{ __('decline_appointment_title') }}",
                        decline_appointment_text: "{{ __('decline_appointment_text') }}",
                        yes_decline: "{{ __('yes_decline') }}",
                        declined: "{{ __('declined') }}",
                        could_not_decline_appointment: "{{ __('could_not_decline_appointment') }}"
                    };
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        console.log("DOM loaded, initializing calendar");
                        var calendarEl = document.getElementById('calendar');

                        if (!calendarEl) {
                            console.error("Calendar element not found! Check your HTML.");
                            return;
                        }

                        console.log("Calendar element found:", calendarEl);
                        const eventDetailModal = document.getElementById('eventDetailModal');
                        const closeEventModalBtn = document.getElementById('closeEventModalBtn');
                        const confirmAppointmentBtn = document.getElementById('confirmAppointmentBtn');
                        const declineAppointmentBtn = document.getElementById('declineAppointmentBtn');
                        const statusActionButtons = document.getElementById('statusActionButtons');
                        let currentAppointmentId = null;

                        // New appointment modal elements
                        const newAppointmentModal = document.getElementById('newAppointmentModal');
                        const closeNewAppointmentModalBtn = document.getElementById('closeNewAppointmentModalBtn');
                        const createAppointmentBtn = document.getElementById('createAppointmentBtn');
                        const clientSelector = document.getElementById('clientSelector');
                        const selectedDateTime = document.getElementById('selectedDateTime');
                        const appointmentDate = document.getElementById('appointmentDate');
                        const appointmentTime = document.getElementById('appointmentTime');
                        let selectedStart = null;
                        let selectedEnd = null;

                        // Function to open the new appointment modal
                        function openNewAppointmentModal(start, end) {
                            selectedStart = start;
                            selectedEnd = end;

                            // Format date and time for display - in English with Month first
                            const formattedDate = start.toLocaleDateString('en-US', {
                                month: 'long',
                                day: 'numeric',
                                year: 'numeric'
                            });

                            // Ensure the end time is 3 hours after start
                            const actualEnd = new Date(start.getTime() + (3 * 60 * 60 * 1000));

                            const formattedTime = start.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            }) + ' - ' + actualEnd.toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            }) + ' (3 hours)';

                            // Display formatted date & time with proper capitalization
                            selectedDateTime.value = formattedDate.charAt(0).toUpperCase() + formattedDate.slice(1) + ' • ' +
                                formattedTime;

                            // Set hidden fields for form submission
                            const dateStr = start.toISOString().split('T')[0]; // YYYY-MM-DD
                            const timeStr = start.toTimeString().substring(0, 5); // HH:MM

                            appointmentDate.value = dateStr;
                            appointmentTime.value = timeStr;

                            // Load available clients if not already loaded
                            if (clientSelector.options.length <= 1) {
                                loadClients();
                            }

                            // Show modal
                            newAppointmentModal.classList.remove('hidden');
                            newAppointmentModal.style.display = 'block';
                        }

                        // Function to load clients for the dropdown
                        function loadClients() {
                            // Mostrar estado de carga
                            clientSelector.innerHTML = '<option value="">Loading clients...</option>';
                            console.log('Loading clients from API...');

                            // Fetch de clientes desde el endpoint
                            fetch('{{ secure_url(route('appointment-calendar.clients', [], false)) }}')
                                .then(response => {
                                    console.log('API response status:', response.status);
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('Client data received:', data);
                                    if (data.success && data.data && data.data.length > 0) {
                                        // Limpiar opción de carga
                                        clientSelector.innerHTML = `<option value="">${translations.please_select_client}</option>`;
                                        console.log('Found', data.data.length, 'clients');

                                        // Agregar clientes al dropdown
                                        data.data.forEach(client => {
                                            const option = document.createElement('option');
                                            option.value = client.uuid;
                                            option.textContent =
                                                `${client.first_name} ${client.last_name} (${client.email})`;
                                            option.dataset.email = client.email;
                                            option.dataset.phone = client.phone;
                                            clientSelector.appendChild(option);
                                        });

                                        // Inicializar Select2 después de cargar las opciones
                                        $('#clientSelector').select2({
                                            placeholder: translations.please_select_client,
                                            allowClear: true
                                        });
                                    } else {
                                        console.error('No clients found in data:', data);
                                        clientSelector.innerHTML = '<option value="">No clients available</option>';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetching clients:', error);
                                    clientSelector.innerHTML = '<option value="">Error loading clients</option>';
                                });
                        }

                        // Handle close new appointment modal
                        closeNewAppointmentModalBtn.addEventListener('click', () => {
                            newAppointmentModal.classList.add('hidden');
                            newAppointmentModal.style.display = 'none';
                            // Clear selection on calendar
                            calendar.unselect();
                        });

                        // Handle click outside new appointment modal
                        newAppointmentModal.addEventListener('click', function(event) {
                            if (event.target === newAppointmentModal) {
                                newAppointmentModal.classList.add('hidden');
                                newAppointmentModal.style.display = 'none';
                                // Clear selection on calendar
                                calendar.unselect();
                            }
                        });

                        // Handle create appointment button
                        createAppointmentBtn.addEventListener('click', function() {
                            // Validate client is selected
                            if (!clientSelector.value) {
                                Swal.fire(translations.error, translations.please_select_client, 'error');
                                return;
                            }

                            // Show processing state
                            const btnText = createAppointmentBtn.querySelector('.normal-btn-text');
                            const processingText = createAppointmentBtn.querySelector('.processing-btn-text');
                            btnText.classList.add('hidden');
                            processingText.classList.remove('hidden');
                            createAppointmentBtn.disabled = true;

                            // Get selected client data
                            const selectedClient = clientSelector.options[clientSelector.selectedIndex];
                            const clientUuid = clientSelector.value;

                            // Prepare the data for the AJAX request
                            const formData = new FormData();
                            formData.append('client_uuid', clientUuid);
                            formData.append('inspection_date', appointmentDate.value);
                            formData.append('inspection_time', appointmentTime.value);
                            formData.append('inspection_status', 'Confirmed'); // Set status to Confirmed
                            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'));

                            // Send AJAX request to create appointment
                            fetch('{{ secure_url(route('appointment-calendar.create', [], false)) }}', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content'),
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Reset button state
                                    btnText.classList.remove('hidden');
                                    processingText.classList.add('hidden');
                                    createAppointmentBtn.disabled = false;

                                    if (data.success) {
                                        // Show success message
                                        Swal.fire(translations.success, data.message || translations
                                            .appointment_created_successfully,
                                            'success');

                                        // Close modal and refresh calendar
                                        newAppointmentModal.classList.add('hidden');
                                        newAppointmentModal.style.display = 'none';
                                        calendar.refetchEvents();

                                        // Clear form
                                        clientSelector.value = '';
                                    } else {
                                        // Show error message
                                        let errorMessage = data.message || 'Error creating appointment';

                                        if (data.errors) {
                                            // Format validation errors
                                            errorMessage += '\n\n';
                                            Object.values(data.errors).forEach(error => {
                                                errorMessage += '• ' + error + '\n';
                                            });
                                        }

                                        Swal.fire(translations.error, errorMessage, 'error');
                                    }
                                })
                                .catch(error => {
                                    // Reset button state
                                    btnText.classList.remove('hidden');
                                    processingText.classList.add('hidden');
                                    createAppointmentBtn.disabled = false;

                                    console.error('Error creating appointment:', error);
                                    Swal.fire(translations.error, translations.unexpected_error, 'error');
                                });
                        });

                        // --- CSRF Token for AJAX --- 
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        // Log CSRF token for debugging
                        console.log("CSRF token found:", $('meta[name="csrf-token"]').attr('content') ? "Yes" : "No");

                        try {
                            console.log("Creating calendar with options");
                            var calendar = new FullCalendar.Calendar(calendarEl, {
                                // Core options
                                headerToolbar: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek' // Views
                                },
                                initialView: 'timeGridWeek', // Default view
                                locale: 'en', // English locale
                                timeZone: 'local', // Use local timezone
                                navLinks: true, // allows users to click day/week names to navigate
                                editable: true, // enable drag and drop
                                selectable: true, // Allow users to select time slots
                                selectMirror: true, // Show "mirror" when selecting
                                dayMaxEvents: true, // allow "more" link when too many events
                                nowIndicator: true, // Show current time line

                                // Time grid options
                                slotDuration: '00:30:00', // Set slot duration to 30 mins for grid lines
                                slotMinTime: '09:00:00', // Optional: Start time for the grid
                                slotMaxTime: '18:00:00', // Optional: End time for the grid
                                businessHours: { // Optional: Highlight business hours
                                    daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
                                    startTime: '09:00',
                                    endTime: '18:00',
                                },

                                // Handle date selection
                                select: function(info) {
                                    openNewAppointmentModal(info.start, info.end);
                                },

                                // Renderizado personalizado de eventos
                                eventContent: function(arg) {
                                    let content = document.createElement('div');
                                    content.classList.add('fc-event-content-custom');
                                    content.style.cursor =
                                        'pointer'; // Añadir cursor para indicar que se puede hacer clic
                                    content.style.width = '100%';
                                    content.style.height = '100%';

                                    // 1. Nombre del cliente (primera línea, más grande)
                                    let clientTitle = document.createElement('div');
                                    clientTitle.classList.add('client-title');
                                    clientTitle.innerHTML = arg.event.title;

                                    // 2. Horario (segunda línea)
                                    let timeText = document.createElement('div');
                                    timeText.classList.add('event-time');

                                    // Formatear la hora en formato 24h (HH:MM - HH:MM)
                                    const start = arg.event.start;
                                    const end = arg.event.end;
                                    const startTime = start.toLocaleTimeString('es-ES', {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: false
                                    });
                                    const endTime = end ? end.toLocaleTimeString('es-ES', {
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        hour12: false
                                    }) : '';

                                    timeText.innerHTML = startTime + (endTime ? ' - ' + endTime : '') + ' (3h)';

                                    // 3. Estado (última línea, más pequeña)
                                    let statusText = document.createElement('div');
                                    statusText.classList.add('appointment-status');
                                    statusText.innerHTML = arg.event.extendedProps.status || 'Pending';

                                    // Agregar todo al contenedor
                                    content.appendChild(clientTitle);
                                    content.appendChild(timeText);
                                    content.appendChild(statusText);

                                    return {
                                        domNodes: [content]
                                    };
                                },

                                // Event Data Source
                                events: {
                                    url: '{{ secure_url(route('appointment-calendar.events', [], false)) }}',
                                    failure: function(err) {
                                        console.error("Failed to load events:", err);
                                    },
                                    success: function(events) {
                                        console.log("Events loaded successfully:", events);
                                    }
                                },
                                eventTimeFormat: { // Format time display on events
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: false // Use 24-hour format
                                },

                                // --- Event Handlers ---

                                // Handle event dragging
                                eventDrop: function(info) {
                                    const event = info.event;
                                    const newStart = event.start.toISOString();
                                    const newEnd = event.end ? event.end.toISOString() :
                                        null; // End might be null if duration based

                                    Swal.fire({
                                        title: translations.reschedule_appointment,
                                        html: translations.move_appointment_to.replace('{title}', event
                                            .title).replace('{newTime}', event.start.toLocaleString(
                                            'en-US', {
                                                dateStyle: 'short',
                                                timeStyle: 'short'
                                            })),
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: translations.yes_move,
                                        cancelButtonText: translations.cancel
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Send AJAX request to update backend
                                            $.ajax({
                                                url: `{{ secure_url(route('appointment-calendar.update', '', false)) }}/${event.id}`,
                                                type: 'PATCH',
                                                data: {
                                                    start: newStart,
                                                    end: newEnd
                                                },
                                                success: function(response) {
                                                    Swal.fire(translations.moved, response
                                                        .message,
                                                        'success');
                                                    // Calendar automatically keeps the event in the new position on success
                                                },
                                                error: function(xhr) {
                                                    console.error("Error updating event:", xhr
                                                        .responseText);
                                                    let errorMessage =
                                                        translations
                                                        .could_not_update_appointment;
                                                    if (xhr.responseJSON && xhr.responseJSON
                                                        .message) {
                                                        errorMessage +=
                                                            ` ${xhr.responseJSON.message}`;
                                                    }
                                                    Swal.fire(translations.error, errorMessage,
                                                        'error');
                                                    info
                                                        .revert(); // Revert event to original position on error
                                                }
                                            });
                                        } else {
                                            info.revert(); // Revert if user cancels confirmation
                                        }
                                    });
                                },

                                // Handle clicking on an event
                                eventClick: function(info) {
                                    console.log("Event clicked:", info.event.title);
                                    info.jsEvent
                                        .preventDefault(); // Prevent browser navigation if the event has a URL

                                    try {
                                        const props = info.event.extendedProps;
                                        console.log("Event props:", props);
                                        currentAppointmentId = info.event.id; // Store the current appointment ID
                                        console.log("Current appointment ID:", currentAppointmentId);

                                        // Populate modal with event data
                                        document.getElementById('modalEventTitle').textContent = props.clientName ||
                                            info.event.title;
                                        document.getElementById('modalEventEmail').textContent = props
                                            .clientEmail || 'N/A';
                                        document.getElementById('modalEventPhone').textContent = props
                                            .clientPhone || 'N/A';

                                        // Format and display the date and time
                                        const start = info.event.start;
                                        const end = info.event.end;
                                        let formattedDateTime = new Intl.DateTimeFormat('en-US', {
                                            weekday: 'long',
                                            year: 'numeric',
                                            month: 'long',
                                            day: 'numeric',
                                            hour: 'numeric',
                                            minute: 'numeric',
                                            hour12: true
                                        }).format(start);

                                        // Add end time (3 hours after start)
                                        if (end) {
                                            formattedDateTime += ' - ' + new Intl.DateTimeFormat('en-US', {
                                                hour: 'numeric',
                                                minute: 'numeric',
                                                hour12: true
                                            }).format(end);
                                            formattedDateTime += ' (3 hours)';
                                        }

                                        document.getElementById('modalEventDateTime').textContent =
                                            formattedDateTime;

                                        // Formatear el estado de la cita como badge con color
                                        const statusElement = document.getElementById('modalEventStatus');
                                        statusElement.textContent = props.status || 'N/A';

                                        // Aplicar estilos al badge según el estado
                                        statusElement.className =
                                            'px-2 py-1 text-xs font-semibold rounded-full text-white';
                                        switch (props.status) {
                                            case 'Confirmed':
                                                statusElement.classList.add('bg-purple-600');
                                                break;
                                            case 'Completed':
                                                statusElement.classList.add('bg-green-600');
                                                break;
                                            case 'Pending':
                                                statusElement.classList.add('bg-orange-600');
                                                break;
                                            case 'Declined':
                                                statusElement.classList.add('bg-red-600');
                                                break;
                                            default:
                                                statusElement.classList.add('bg-gray-600');
                                                break;
                                        }

                                        // Formatear el estado del lead como badge con color
                                        const leadStatusElement = document.getElementById('modalEventLeadStatus');
                                        leadStatusElement.textContent = props.leadStatus || 'N/A';

                                        // Aplicar estilos al badge según el estado del lead
                                        leadStatusElement.className =
                                            'px-2 py-1 text-xs font-semibold rounded-full text-white';
                                        switch (props.leadStatus) {
                                            case 'New':
                                                leadStatusElement.classList.add('bg-blue-600');
                                                break;
                                            case 'Called':
                                                leadStatusElement.classList.add('bg-green-600');
                                                break;
                                            case 'Pending':
                                                leadStatusElement.classList.add('bg-orange-600');
                                                break;
                                            case 'Declined':
                                                leadStatusElement.classList.add('bg-red-600');
                                                break;
                                            default:
                                                leadStatusElement.classList.add('bg-gray-600');
                                                break;
                                        }

                                        document.getElementById('modalEventAddress').textContent = props.address ||
                                            'N/A';

                                        // Set up location sharing buttons
                                        setupMapSharing(props);

                                        document.getElementById('modalEventNotes').textContent = props.notes ||
                                            'N/A';
                                        document.getElementById('modalEventDamage').textContent = props.damage ||
                                            'N/A';
                                        // Formatear el seguro como badge con color
                                        const insuranceElement = document.getElementById('modalEventInsurance');
                                        if (props.hasInsurance === 'Yes') {
                                            insuranceElement.textContent = 'Yes';
                                            insuranceElement.className =
                                                'px-2 py-1 text-xs font-semibold rounded-full text-white bg-green-600';
                                        } else if (props.hasInsurance === 'No') {
                                            insuranceElement.textContent = 'No';
                                            insuranceElement.className =
                                                'px-2 py-1 text-xs font-semibold rounded-full text-white bg-red-600';
                                        } else {
                                            insuranceElement.textContent = 'N/A';
                                            insuranceElement.className =
                                                'px-2 py-1 text-xs font-semibold rounded-full text-white bg-gray-600';
                                        }

                                        // Mostrar/ocultar botones de acción según el estado actual
                                        if (props.status === 'Completed' || props.status === 'Declined') {
                                            statusActionButtons.classList.add('hidden');
                                        } else {
                                            statusActionButtons.classList.remove('hidden');

                                            // Disable "Confirm" button if already confirmed
                                            if (props.status === 'Confirmed') {
                                                confirmAppointmentBtn.classList.add('opacity-50',
                                                    'cursor-not-allowed');
                                                confirmAppointmentBtn.disabled = true;
                                            } else {
                                                confirmAppointmentBtn.classList.remove('opacity-50',
                                                    'cursor-not-allowed');
                                                confirmAppointmentBtn.disabled = false;
                                            }
                                        }

                                        // Mostrar el modal
                                        eventDetailModal.classList.remove('hidden');
                                        eventDetailModal.style.display = 'block';
                                        console.log("Modal should be visible now");
                                    } catch (error) {
                                        console.error("Error in eventClick handler:", error);
                                    }
                                },

                                // --- Tooltips on Hover (using Tippy.js) ---
                                eventMouseEnter: function(info) {
                                    tippy(info.el, {
                                        content: `<strong>${info.event.title}</strong><br>Status: ${info.event.extendedProps.status || 'Pending'}`,
                                        allowHTML: true,
                                        theme: 'light-border', // Example theme
                                        placement: 'top',
                                        arrow: true
                                    });
                                }

                            });

                            console.log("Rendering calendar...");
                            try {
                                calendar.render();
                                console.log("Calendar rendered successfully!");
                            } catch (err) {
                                console.error("Error rendering calendar:", err);
                            }

                            // Close modal button with explicit style update
                            closeEventModalBtn.addEventListener('click', () => {
                                eventDetailModal.classList.add('hidden');
                                eventDetailModal.style.display = 'none';
                                console.log("Modal closed by button");
                            });

                            // Close modal on clicking outside with explicit style update
                            eventDetailModal.addEventListener('click', function(event) {
                                if (event.target === eventDetailModal) { // Check if click is on the backdrop
                                    eventDetailModal.classList.add('hidden');
                                    eventDetailModal.style.display = 'none';
                                    console.log("Modal closed by clicking outside");
                                }
                            });

                            // Handle confirm appointment button
                            confirmAppointmentBtn.addEventListener('click', function() {
                                if (!currentAppointmentId) return;

                                Swal.fire({
                                    title: translations.confirm_appointment_title,
                                    text: translations.confirm_appointment_text,
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#10b981',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: translations.yes_confirm,
                                    cancelButtonText: translations.cancel
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Mostrar estado de procesamiento
                                        const btnText = confirmAppointmentBtn.querySelector('.normal-btn-text');
                                        const processingText = confirmAppointmentBtn.querySelector(
                                            '.processing-btn-text');
                                        btnText.classList.add('hidden');
                                        processingText.classList.remove('hidden');
                                        confirmAppointmentBtn.disabled = true;
                                        confirmAppointmentBtn.classList.add('opacity-70', 'cursor-not-allowed');

                                        // Send AJAX request to update appointment status
                                        $.ajax({
                                            url: `{{ secure_url(route('appointment-calendar.status', '', false)) }}/${currentAppointmentId}`,
                                            type: 'PATCH',
                                            data: {
                                                status: 'Confirmed'
                                            },
                                            success: function(response) {
                                                // Restaurar estado del botón
                                                btnText.classList.remove('hidden');
                                                processingText.classList.add('hidden');
                                                confirmAppointmentBtn.disabled = false;
                                                confirmAppointmentBtn.classList.remove('opacity-70',
                                                    'cursor-not-allowed');

                                                Swal.fire(translations.confirmed, response.message,
                                                    'success');
                                                calendar.refetchEvents(); // Refresh calendar events
                                                eventDetailModal.classList.add(
                                                    'hidden'); // Close modal
                                                eventDetailModal.style.display = 'none';
                                            },
                                            error: function(xhr) {
                                                // Restaurar estado del botón
                                                btnText.classList.remove('hidden');
                                                processingText.classList.add('hidden');
                                                confirmAppointmentBtn.disabled = false;
                                                confirmAppointmentBtn.classList.remove('opacity-70',
                                                    'cursor-not-allowed');

                                                console.error("Error updating appointment status:",
                                                    xhr.responseText);
                                                let errorMessage =
                                                    translations.could_not_confirm_appointment;
                                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                                    errorMessage += ` ${xhr.responseJSON.message}`;
                                                }
                                                Swal.fire(translations.error, errorMessage,
                                                    'error');
                                            }
                                        });
                                    }
                                });
                            });

                            // Handle decline appointment button
                            declineAppointmentBtn.addEventListener('click', function() {
                                if (!currentAppointmentId) return;

                                Swal.fire({
                                    title: translations.decline_appointment_title,
                                    text: translations.decline_appointment_text,
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ef4444',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: translations.yes_decline,
                                    cancelButtonText: translations.cancel
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Mostrar estado de procesamiento
                                        const btnText = declineAppointmentBtn.querySelector('.normal-btn-text');
                                        const processingText = declineAppointmentBtn.querySelector(
                                            '.processing-btn-text');
                                        btnText.classList.add('hidden');
                                        processingText.classList.remove('hidden');
                                        declineAppointmentBtn.disabled = true;
                                        declineAppointmentBtn.classList.add('opacity-70', 'cursor-not-allowed');

                                        // Send AJAX request to update appointment status
                                        $.ajax({
                                            url: `{{ secure_url(route('appointment-calendar.status', '', false)) }}/${currentAppointmentId}`,
                                            type: 'PATCH',
                                            data: {
                                                status: 'Declined'
                                            },
                                            success: function(response) {
                                                // Restaurar estado del botón
                                                btnText.classList.remove('hidden');
                                                processingText.classList.add('hidden');
                                                declineAppointmentBtn.disabled = false;
                                                declineAppointmentBtn.classList.remove('opacity-70',
                                                    'cursor-not-allowed');

                                                Swal.fire(translations.declined, response.message,
                                                    'success');
                                                calendar.refetchEvents(); // Refresh calendar events
                                                eventDetailModal.classList.add(
                                                    'hidden'); // Close modal
                                                eventDetailModal.style.display = 'none';
                                            },
                                            error: function(xhr) {
                                                // Restaurar estado del botón
                                                btnText.classList.remove('hidden');
                                                processingText.classList.add('hidden');
                                                declineAppointmentBtn.disabled = false;
                                                declineAppointmentBtn.classList.remove('opacity-70',
                                                    'cursor-not-allowed');

                                                console.error("Error updating appointment status:",
                                                    xhr.responseText);
                                                let errorMessage =
                                                    translations.could_not_decline_appointment;
                                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                                    errorMessage += ` ${xhr.responseJSON.message}`;
                                                }
                                                Swal.fire(translations.error, errorMessage,
                                                    'error');
                                            }
                                        });
                                    }
                                });
                            });
                        } catch (err) {
                            console.error("Error creating calendar:", err);
                        }
                    });

                    // Add map sharing functionality
                    function setupMapSharing(props) {
                        // Get the address and coordinates
                        const address = props.address || '';
                        const lat = props.latitude || '';
                        const lng = props.longitude || '';

                        // Store coordinates in hidden fields
                        document.getElementById('event-latitude').value = lat;
                        document.getElementById('event-longitude').value = lng;

                        // Create Google Maps URL
                        const mapsUrl = (lat && lng) ?
                            `https://www.google.com/maps?q=${lat},${lng}` :
                            `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;

                        // Set up sharing links
                        // WhatsApp
                        const whatsappLink = document.getElementById('share-whatsapp');
                        whatsappLink.href =
                            `https://wa.me/?text=Location for inspection: ${encodeURIComponent(address)} - ${encodeURIComponent(mapsUrl)}`;
                        whatsappLink.target = '_blank';

                        // Email
                        const emailLink = document.getElementById('share-email');
                        const subject = encodeURIComponent('Location for inspection');
                        const body = encodeURIComponent(
                            `The location for the inspection is: ${address}\n\nView in Google Maps: ${mapsUrl}`);
                        emailLink.href = `mailto:?subject=${subject}&body=${body}`;

                        // Google Maps
                        const mapsLink = document.getElementById('share-maps');
                        mapsLink.href = mapsUrl;
                        mapsLink.target = '_blank';

                        // Copy link button
                        const copyButton = document.getElementById('copy-address');
                        copyButton.onclick = function(e) {
                            e.preventDefault();
                            navigator.clipboard.writeText(mapsUrl).then(() => {
                                // Show confirmation message
                                const originalHTML = this.innerHTML;
                                this.innerHTML =
                                    '<svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
                                setTimeout(() => {
                                    this.innerHTML = originalHTML;
                                }, 2000);
                            });
                        };

                        // Enable/disable buttons based on whether we have an address
                        if (!address) {
                            whatsappLink.classList.add('opacity-50', 'cursor-not-allowed');
                            emailLink.classList.add('opacity-50', 'cursor-not-allowed');
                            mapsLink.classList.add('opacity-50', 'cursor-not-allowed');
                            copyButton.classList.add('opacity-50', 'cursor-not-allowed');
                        } else {
                            whatsappLink.classList.remove('opacity-50', 'cursor-not-allowed');
                            emailLink.classList.remove('opacity-50', 'cursor-not-allowed');
                            mapsLink.classList.remove('opacity-50', 'cursor-not-allowed');
                            copyButton.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }
                </script>
            @endpush
        </div>
    </div>

    {{-- Modal para crear nuevo lead --}}
    <div id="createLeadModal" class="fixed z-50 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 relative">
                    {{-- Modal Header --}}
                    <div class="flex justify-between items-center p-4 border-b dark:border-gray-700">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('Create New Lead') }}</h3>
                        <button type="button" id="closeCreateLeadModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6">
                        <form id="createLeadForm" method="POST" action="{{ route('appointment-calendar.store') }}">
                            @csrf
                            
                            {{-- Display validation errors --}}
                            <div id="leadFormErrors" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-300 hidden" role="alert">
                                <strong class="font-bold">Validation Error!</strong>
                                <ul id="leadErrorsList" class="mt-2 list-disc list-inside"></ul>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- First Name --}}
                                <div>
                                    <label for="lead_first_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('first_name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input id="lead_first_name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm capitalize" type="text" name="first_name" maxlength="50" pattern="[A-Za-z\s\\'-]+" required />
                                </div>

                                {{-- Last Name --}}
                                <div>
                                    <label for="lead_last_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('last_name') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input id="lead_last_name" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm capitalize" type="text" name="last_name" maxlength="50" pattern="[A-Za-z\s\\'-]+" required />
                                </div>

                                {{-- Phone --}}
                                <div>
                                    <label for="lead_phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('phone') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input id="lead_phone" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="tel" name="phone" placeholder="(XXX) XXX-XXXX" maxlength="14" required />
                                    <div id="phoneError" class="text-red-500 text-sm mt-1 hidden"></div>
                                    <div id="phoneSuccess" class="text-green-500 text-sm mt-1 hidden"></div>
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label for="lead_email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('email') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input id="lead_email" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="email" name="email" required />
                                    <div id="emailError" class="text-red-500 text-sm mt-1 hidden"></div>
                                    <div id="emailSuccess" class="text-green-500 text-sm mt-1 hidden"></div>
                                </div>

                                {{-- Address Map Input (for Google Maps Autocomplete) --}}
                                <div class="md:col-span-2">
                                    <label for="lead_address_map_input" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                        {{ __('address') }} <span class="text-red-500">*</span>
                                    </label>
                                    <input id="lead_address_map_input" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" type="text" name="address_map_input" placeholder="Enter complete address for autocomplete" autocomplete="off" required />
                                </div>

                                {{-- Map Display --}}
                                <div class="md:col-span-2 mt-2 mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location Map</label>
                                    <div id="lead-location-map" class="w-full h-48 bg-gray-200 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600"></div>
                                </div>

                                {{-- Hidden Address Fields --}}
                                <input type="hidden" id="lead_address" name="address">
                                <input type="hidden" id="lead_latitude" name="latitude">
                                <input type="hidden" id="lead_longitude" name="longitude">
                                <input type="hidden" id="lead_city" name="city">
                                <input type="hidden" id="lead_state" name="state">
                                <input type="hidden" id="lead_zipcode" name="zipcode">
                                <input type="hidden" id="lead_country" name="country" value="USA">
                            </div>

                            {{-- Modal Footer --}}
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" id="cancelCreateLead" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md transition-colors duration-200">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" id="submitCreateLead" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                                    <span id="submitLeadText">{{ __('Create Lead') }}</span>
                                    <span id="submitLeadSpinner" class="hidden">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ __('Creating...') }}
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript para el modal de crear lead --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal elements
                const createLeadModal = document.getElementById('createLeadModal');
                const openCreateLeadModalBtn = document.getElementById('openCreateLeadModal');
                const closeCreateLeadModalBtn = document.getElementById('closeCreateLeadModal');
                const cancelCreateLeadBtn = document.getElementById('cancelCreateLead');
                const createLeadForm = document.getElementById('createLeadForm');
                const submitLeadBtn = document.getElementById('submitCreateLead');
                const submitLeadText = document.getElementById('submitLeadText');
                const submitLeadSpinner = document.getElementById('submitLeadSpinner');
                const leadFormErrors = document.getElementById('leadFormErrors');
                const leadErrorsList = document.getElementById('leadErrorsList');

                // Google Maps variables for lead modal
                let leadMap;
                let leadMarker;
                let leadAutocomplete;

                // Open modal
                openCreateLeadModalBtn.addEventListener('click', function() {
                    createLeadModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    
                    // Initialize Google Maps for lead modal
                    setTimeout(() => {
                        initializeLeadMap();
                    }, 100);
                });

                // Close modal functions
                function closeLeadModal() {
                    createLeadModal.classList.add('hidden');
                    document.body.style.overflow = 'auto';
                    createLeadForm.reset();
                    leadFormErrors.classList.add('hidden');
                    leadErrorsList.innerHTML = '';
                    
                    // Reset submit button
                    submitLeadText.classList.remove('hidden');
                    submitLeadSpinner.classList.add('hidden');
                    submitLeadBtn.disabled = false;
                }

                closeCreateLeadModalBtn.addEventListener('click', closeLeadModal);
                cancelCreateLeadBtn.addEventListener('click', closeLeadModal);

                // Close modal when clicking outside
                createLeadModal.addEventListener('click', function(e) {
                    if (e.target === createLeadModal) {
                        closeLeadModal();
                    }
                });

                // Initialize Google Maps for lead modal
                function initializeLeadMap() {
                    const mapElement = document.getElementById('lead-location-map');
                    if (!mapElement) return;

                    // Default location (you can change this)
                    const defaultLocation = { lat: 40.7128, lng: -74.0060 }; // New York

                    leadMap = new google.maps.Map(mapElement, {
                        zoom: 13,
                        center: defaultLocation,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    leadMarker = new google.maps.Marker({
                        position: defaultLocation,
                        map: leadMap,
                        draggable: true
                    });

                    // Initialize autocomplete
                    const addressInput = document.getElementById('lead_address_map_input');
                    leadAutocomplete = new google.maps.places.Autocomplete(addressInput);
                    leadAutocomplete.bindTo('bounds', leadMap);

                    // Handle place selection
                    leadAutocomplete.addListener('place_changed', function() {
                        const place = leadAutocomplete.getPlace();
                        if (!place.geometry) return;

                        // Update map
                        if (place.geometry.viewport) {
                            leadMap.fitBounds(place.geometry.viewport);
                        } else {
                            leadMap.setCenter(place.geometry.location);
                            leadMap.setZoom(17);
                        }

                        // Update marker
                        leadMarker.setPosition(place.geometry.location);

                        // Extract address components
                        updateLeadAddressFields(place);
                    });

                    // Handle marker drag
                    leadMarker.addListener('dragend', function() {
                        const position = leadMarker.getPosition();
                        reverseGeocodeForLead(position.lat(), position.lng());
                    });
                }

                // Update address fields from place object
                function updateLeadAddressFields(place) {
                    const components = place.address_components;
                    let address = place.formatted_address;
                    let city = '';
                    let state = '';
                    let zipcode = '';
                    let country = '';

                    components.forEach(component => {
                        const types = component.types;
                        if (types.includes('locality')) {
                            city = component.long_name;
                        } else if (types.includes('administrative_area_level_1')) {
                            state = component.short_name;
                        } else if (types.includes('postal_code')) {
                            zipcode = component.long_name;
                        } else if (types.includes('country')) {
                            country = component.short_name;
                        }
                    });

                    // Update hidden fields
                    document.getElementById('lead_address').value = address;
                    document.getElementById('lead_latitude').value = place.geometry.location.lat();
                    document.getElementById('lead_longitude').value = place.geometry.location.lng();
                    document.getElementById('lead_city').value = city;
                    document.getElementById('lead_state').value = state;
                    document.getElementById('lead_zipcode').value = zipcode;
                    document.getElementById('lead_country').value = country || 'USA';
                }

                // Reverse geocoding for lead
                function reverseGeocodeForLead(lat, lng) {
                    const geocoder = new google.maps.Geocoder();
                    const latlng = { lat: lat, lng: lng };

                    geocoder.geocode({ location: latlng }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            const place = results[0];
                            document.getElementById('lead_address_map_input').value = place.formatted_address;
                            updateLeadAddressFields(place);
                        }
                    });
                }

                // Handle form submission
                createLeadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    submitLeadText.classList.add('hidden');
                    submitLeadSpinner.classList.remove('hidden');
                    submitLeadBtn.disabled = true;
                    
                    // Hide previous errors
                    leadFormErrors.classList.add('hidden');
                    leadErrorsList.innerHTML = '';

                    // Prepare form data
                    const formData = new FormData(createLeadForm);

                    // Submit via AJAX
                    fetch(createLeadForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            Swal.fire({
                                title: 'Success!',
                                text: data.message || 'Lead created successfully!',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                closeLeadModal();
                                // Refresh calendar if needed
                                if (typeof calendar !== 'undefined') {
                                    calendar.refetchEvents();
                                }
                            });
                        } else {
                            // Show validation errors
                            if (data.errors) {
                                leadErrorsList.innerHTML = '';
                                Object.values(data.errors).forEach(errorArray => {
                                    errorArray.forEach(error => {
                                        const li = document.createElement('li');
                                        li.textContent = error;
                                        leadErrorsList.appendChild(li);
                                    });
                                });
                                leadFormErrors.classList.remove('hidden');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while creating the lead.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    })
                    .finally(() => {
                        // Reset loading state
                        submitLeadText.classList.remove('hidden');
                        submitLeadSpinner.classList.add('hidden');
                        submitLeadBtn.disabled = false;
                    });
                });

                // Phone formatting function
                function formatPhoneNumber(value) {
                    // Remove all non-numeric characters
                    const phoneNumber = value.replace(/\D/g, '');
                    
                    // Format as (XXX) XXX-XXXX
                    if (phoneNumber.length >= 6) {
                        return `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(3, 6)}-${phoneNumber.slice(6, 10)}`;
                    } else if (phoneNumber.length >= 3) {
                        return `(${phoneNumber.slice(0, 3)}) ${phoneNumber.slice(3)}`;
                    } else {
                        return phoneNumber;
                    }
                }

                // Phone input formatting
                const phoneInput = document.getElementById('lead_phone');
                const emailInput = document.getElementById('lead_email');
                const phoneError = document.getElementById('phoneError');
                const phoneSuccess = document.getElementById('phoneSuccess');
                const emailError = document.getElementById('emailError');
                const emailSuccess = document.getElementById('emailSuccess');

                let phoneTimeout;
                let emailTimeout;

                // Format phone number on input
                phoneInput.addEventListener('input', function(e) {
                    const formatted = formatPhoneNumber(e.target.value);
                    e.target.value = formatted;
                    
                    // Clear previous timeout
                    clearTimeout(phoneTimeout);
                    
                    // Hide previous messages
                    phoneError.classList.add('hidden');
                    phoneSuccess.classList.add('hidden');
                    
                    // Only validate if we have a complete phone number
                    const phoneNumber = e.target.value.replace(/\D/g, '');
                    if (phoneNumber.length === 10) {
                        phoneTimeout = setTimeout(() => {
                            checkPhoneExists(e.target.value);
                        }, 500);
                    }
                });

                // Email validation on blur
                emailInput.addEventListener('blur', function(e) {
                    const email = e.target.value.trim();
                    if (email && email.includes('@')) {
                        clearTimeout(emailTimeout);
                        emailTimeout = setTimeout(() => {
                            checkEmailExists(email);
                        }, 300);
                    }
                });

                // Check if phone exists
                function checkPhoneExists(phone) {
                    fetch('{{ route("appointment-calendar.check-phone") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ phone: phone })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            phoneError.textContent = 'Este número de teléfono ya está registrado.';
                            phoneError.classList.remove('hidden');
                            phoneSuccess.classList.add('hidden');
                        } else {
                            phoneSuccess.textContent = 'Número de teléfono disponible.';
                            phoneSuccess.classList.remove('hidden');
                            phoneError.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking phone:', error);
                    });
                }

                // Check if email exists
                function checkEmailExists(email) {
                    fetch('{{ route("appointment-calendar.check-email") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            emailError.textContent = 'Este email ya está registrado.';
                            emailError.classList.remove('hidden');
                            emailSuccess.classList.add('hidden');
                        } else {
                            emailSuccess.textContent = 'Email disponible.';
                            emailSuccess.classList.remove('hidden');
                            emailError.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking email:', error);
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
