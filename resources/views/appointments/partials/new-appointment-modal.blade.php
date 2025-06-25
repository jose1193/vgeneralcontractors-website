{{-- New Appointment Modal Component --}}
<div id="newAppointmentModal" class="fixed inset-0 z-50 overflow-y-auto hidden" style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-medium text-white">
                        {{ __('create_new_appointment') }}
                    </h3>
                    <button id="closeNewAppointmentModalBtn" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form id="newAppointmentForm" class="bg-white px-6 py-4">
                @csrf
                
                <!-- Selected Date/Time Display -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('selected_date_time') }}
                    </label>
                    <input 
                        type="text" 
                        id="selectedDateTime" 
                        readonly 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-900 focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('select_date_time_from_calendar') }}"
                    >
                </div>

                <!-- Client Selection -->
                <div class="mb-6">
                    <label for="clientSelector" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('select_client') }}
                    </label>
                    <select 
                        id="clientSelector" 
                        name="client_id" 
                        required 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                    >
                        <option value="">{{ __('choose_client') }}</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('client_selector_help') }}
                    </p>
                </div>

                <!-- Additional Notes (Optional) -->
                <div class="mb-6">
                    <label for="appointmentNotes" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('additional_notes') }} <span class="text-gray-400">({{ __('optional') }})</span>
                    </label>
                    <textarea 
                        id="appointmentNotes" 
                        name="notes" 
                        rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
                        placeholder="{{ __('appointment_notes_placeholder') }}"
                    ></textarea>
                </div>

                <!-- Hidden fields for date/time -->
                <input type="hidden" id="appointmentDate" name="date" value="">
                <input type="hidden" id="appointmentTime" name="time" value="">
                
                <!-- Duration (fixed at 2 hours) -->
                <input type="hidden" name="duration" value="2">
                
                <!-- Default status -->
                <input type="hidden" name="status" value="Pending">
            </form>

            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-3">
                <div class="flex justify-end space-x-3">
                    <button
                        id="closeNewAppointmentModalBtn2"
                        type="button"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500"
                    >
                        {{ __('cancel') }}
                    </button>
                    <button
                        id="createAppointmentBtn"
                        type="button"
                        disabled
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span class="normal-btn-text flex items-center">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            {{ __('create_appointment') }}
                        </span>
                        <span class="loading-btn-text hidden flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('creating') }}...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Additional event listener for the second close button
document.addEventListener('DOMContentLoaded', function() {
    const closeBtn2 = document.getElementById('closeNewAppointmentModalBtn2');
    if (closeBtn2) {
        closeBtn2.addEventListener('click', function() {
            if (window.CalendarModals) {
                window.CalendarModals.closeNewAppointmentModal();
            }
        });
    }
});
</script>