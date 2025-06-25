{{-- Calendar Container Component --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ __('appointment_calendar_title') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('appointment_calendar_subtitle') }}
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-4">
                    <button type="button" onclick="window.calendarMain?.createNewAppointment()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('create_new_appointment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Widget -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <div id="calendar"></div>
        </div>
    </div>
</div>

{{-- Meta tags para rutas JavaScript --}}
<meta name="calendar-events-url" content="{{ url()->secure(route('appointment-calendar.events', [], false)) }}">
<meta name="calendar-create-url" content="{{ url()->secure(route('appointment-calendar.store', [], false)) }}">
<meta name="calendar-update-url" content="{{ url()->secure(route('appointment-calendar.update', ':id', [], false)) }}">
<meta name="calendar-status-url" content="{{ url()->secure(route('appointment-calendar.status', ':id', [], false)) }}">
<meta name="calendar-clients-url" content="{{ url()->secure(route('appointment-calendar.clients', [], false)) }}">
<meta name="calendar-create-appointment-url"
    content="{{ url()->secure(route('appointment-calendar.createAppointment', [], false)) }}">
