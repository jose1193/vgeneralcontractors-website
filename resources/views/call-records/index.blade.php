<x-crud.index-layout 
    title="{{ __('call_records_title') }}" 
    subtitle="{{ __('call_records_subtitle') }}"
    entity-name="{{ __('call_record_entity_name') }}" 
    entity-name-plural="{{ __('call_record_entity_plural') }}" 
    :search-placeholder="__('call_record_search_placeholder')" 
    :show-deleted-label="__('call_record_show_inactive')" 
    :add-new-label="__('call_record_add_new')"
    manager-name="callRecordManager" 
    table-id="callRecordTable" 
    create-button-id="createCallRecordBtn" 
    search-id="searchInput"
    show-deleted-id="showDeleted" 
    per-page-id="perPage" 
    pagination-id="pagination" 
    alert-id="alertContainer"
    :table-columns="[
        ['field' => 'start_timestamp', 'label' => __('call_record_start_time'), 'sortable' => true],
        ['field' => 'from_number', 'label' => __('call_record_from'), 'sortable' => true],
        ['field' => 'to_number', 'label' => __('call_record_to'), 'sortable' => true],
        ['field' => 'duration_ms', 'label' => __('call_record_duration'), 'sortable' => true],
        ['field' => 'call_status', 'label' => __('call_record_status'), 'sortable' => true],
        ['field' => 'user_sentiment', 'label' => __('call_record_sentiment'), 'sortable' => true],
        ['field' => 'actions', 'label' => __('call_record_actions'), 'sortable' => false],
    ]">
    
    @push('scripts')
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- CrudManagerModal -->
        <script src="{{ asset('js/crud-manager-modal.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Make the manager globally accessible
                window.callRecordManager = new CrudManagerModal({
                    entityName: "{{ __('call_record_entity_name') }}",
                    entityNamePlural: "{{ __('call_record_entity_plural') }}",
                    routes: {
                        index: "/api/call-records",
                        show: "/api/call-records/:id"
                    },
                    tableSelector: '#callRecordTable-body',
                    searchSelector: '#searchInput',
                    perPageSelector: '#perPage',
                    showDeletedSelector: '#showDeleted',
                    paginationSelector: '#pagination',
                    alertSelector: '#alertContainer',
                    createButtonSelector: '#createCallRecordBtn',
                    idField: 'call_id',
                    searchFields: ['from_number', 'to_number', 'call_status'],
                    showDeleted: false,
                    singleRecordMode: false, // Call records are read-only
                    tableHeaders: [
                        {
                            field: 'start_timestamp',
                            name: "{{ __('call_record_start_time') }}",
                            sortable: true,
                            getter: (entity) => {
                                if (!entity.start_timestamp) return 'N/A';
                                const date = new Date(entity.start_timestamp);
                                if (isNaN(date.getTime())) return 'Invalid Date';
                                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                                const day = date.getDate().toString().padStart(2, '0');
                                const year = date.getFullYear();
                                const hours = date.getHours().toString().padStart(2, '0');
                                const minutes = date.getMinutes().toString().padStart(2, '0');
                                return `${month}/${day}/${year} ${hours}:${minutes}`;
                            }
                        },
                        {
                            field: 'from_number',
                            name: "{{ __('call_record_from') }}",
                            sortable: true,
                            getter: (entity) => {
                                if (!entity.from_number) return 'N/A';
                                // Format phone number
                                const cleaned = entity.from_number.replace(/\D/g, '');
                                if (cleaned.length === 10) {
                                    return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
                                } else if (cleaned.length === 11 && cleaned[0] === '1') {
                                    return `+1 (${cleaned.slice(1, 4)}) ${cleaned.slice(4, 7)}-${cleaned.slice(7)}`;
                                }
                                return entity.from_number;
                            }
                        },
                        {
                            field: 'to_number',
                            name: "{{ __('call_record_to') }}",
                            sortable: true,
                            getter: (entity) => {
                                if (!entity.to_number) return 'N/A';
                                // Format phone number
                                const cleaned = entity.to_number.replace(/\D/g, '');
                                if (cleaned.length === 10) {
                                    return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
                                } else if (cleaned.length === 11 && cleaned[0] === '1') {
                                    return `+1 (${cleaned.slice(1, 4)}) ${cleaned.slice(4, 7)}-${cleaned.slice(7)}`;
                                }
                                return entity.to_number;
                            }
                        },
                        {
                            field: 'duration_ms',
                            name: "{{ __('call_record_duration') }}",
                            sortable: true,
                            getter: (entity) => {
                                if (!entity.duration_ms) return 'N/A';
                                return Math.round(entity.duration_ms / 1000) + 's';
                            }
                        },
                        {
                            field: 'call_status',
                            name: "{{ __('call_record_status') }}",
                            sortable: true,
                            getter: (entity) => {
                                const callSuccessful = entity.call_analysis && entity.call_analysis.call_successful;
                                const status = entity.call_status || 'Unknown';
                                const badgeClass = callSuccessful ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                                return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeClass}">${status}</span>`;
                            }
                        },
                        {
                            field: 'user_sentiment',
                            name: "{{ __('call_record_sentiment') }}",
                            sortable: true,
                            getter: (entity) => {
                                const sentiment = entity.call_analysis && entity.call_analysis.user_sentiment ? entity.call_analysis.user_sentiment : 'Unknown';
                                let badgeClass = 'bg-gray-100 text-gray-800';
                                if (sentiment === 'Positive') {
                                    badgeClass = 'bg-green-100 text-green-800';
                                } else if (sentiment === 'Negative') {
                                    badgeClass = 'bg-red-100 text-red-800';
                                }
                                return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeClass}">${sentiment}</span>`;
                            }
                        },
                        {
                            field: 'actions',
                            name: "{{ __('call_record_actions') }}",
                            sortable: false,
                            getter: (callRecord) => {
                                let actionsHtml = `
                                    <div class="flex justify-center space-x-2">
                                        <button data-id="${callRecord.call_id}" class="view-details-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105" title="View Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>`;
                                
                                if (callRecord.recording_url) {
                                    actionsHtml += `
                                        <button data-audio-url="${callRecord.recording_url}" class="play-audio-btn inline-flex items-center justify-center w-9 h-9 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105" title="Play Audio">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>`;
                                }
                                
                                actionsHtml += `</div>`;
                                return actionsHtml;
                            }
                        }
                    ],
                    defaultSortField: 'start_timestamp',
                    defaultSortDirection: 'desc',
                    translations: {
                        noRecordsFound: "{{ __('call_record_no_records_found') }}",
                        loading: "{{ __('call_record_loading') }}",
                        error: "{{ __('call_record_error') }}"
                    },
                    entityConfig: {
                        identifierField: 'call_id',
                        displayName: "{{ __('call_record_display_name') }}",
                        fallbackFields: ['from_number', 'to_number']
                    }
                });

                // Custom event handlers for call records
                $(document).on('click', '.view-details-btn', function(e) {
                    e.preventDefault();
                    const callId = $(this).data('id');
                    showCallDetails(callId);
                });

                $(document).on('click', '.play-audio-btn', function(e) {
                    e.preventDefault();
                    const audioUrl = $(this).data('audio-url');
                    playAudio(audioUrl);
                });

                // Function to show call details
                function showCallDetails(callId) {
                    $.ajax({
                        url: `/api/call-records/${callId}`,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        success: function(response) {
                            const call = response.call || response;
                            let detailsHtml = `
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">From</h3>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">${formatPhoneNumber(call.from_number)}</p>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">To</h3>
                                            <p class="mt-1 text-sm text-gray-900 dark:text-white">${formatPhoneNumber(call.to_number)}</p>
                                        </div>
                                    </div>`;

                            if (call.call_analysis && call.call_analysis.call_summary) {
                                detailsHtml += `
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Summary</h3>
                                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-200 leading-relaxed">${call.call_analysis.call_summary}</p>
                                    </div>`;
                            }

                            if (call.transcript) {
                                detailsHtml += `
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Transcript</h3>
                                        <div class="mt-2 space-y-2 max-h-60 overflow-y-auto">
                                            ${call.transcript.split('\n').map(line => `<p class="text-sm text-gray-700 dark:text-gray-200 leading-relaxed">${line}</p>`).join('')}
                                        </div>
                                    </div>`;
                            }

                            detailsHtml += `</div>`;

                            Swal.fire({
                                title: 'Call Details',
                                html: detailsHtml,
                                width: '800px',
                                showCloseButton: true,
                                showConfirmButton: false,
                                customClass: {
                                    container: 'swal-modal-container',
                                    popup: 'swal-modal-popup',
                                    content: 'swal-modal-content'
                                }
                            });
                        },
                        error: function(xhr) {
                            console.error('Error loading call details:', xhr);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error loading call details'
                            });
                        }
                    });
                }

                // Function to play audio
                function playAudio(audioUrl) {
                    const audioHtml = `
                        <div class="space-y-4">
                            <audio controls class="w-full" autoplay>
                                <source src="${audioUrl}" type="audio/mpeg">
                                <source src="${audioUrl}" type="audio/wav">
                                Your browser does not support the audio element.
                            </audio>
                            <div class="text-center">
                                <a href="${audioUrl}" download class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download Audio
                                </a>
                            </div>
                        </div>`;

                    Swal.fire({
                        title: 'Audio Player',
                        html: audioHtml,
                        width: '600px',
                        showCloseButton: true,
                        showConfirmButton: false,
                        customClass: {
                            container: 'swal-modal-container',
                            popup: 'swal-modal-popup',
                            content: 'swal-modal-content'
                        }
                    });
                }

                // Helper function to format phone numbers
                function formatPhoneNumber(phoneNumber) {
                    if (!phoneNumber) return 'N/A';
                    const cleaned = phoneNumber.replace(/\D/g, '');
                    if (cleaned.length === 10) {
                        return `(${cleaned.slice(0, 3)}) ${cleaned.slice(3, 6)}-${cleaned.slice(6)}`;
                    } else if (cleaned.length === 11 && cleaned[0] === '1') {
                        return `+1 (${cleaned.slice(1, 4)}) ${cleaned.slice(4, 7)}-${cleaned.slice(7)}`;
                    }
                    return phoneNumber;
                }

                // Hide create button since call records are read-only
                $('#createCallRecordBtn').hide();

                // Initialize loading of entities
                window.callRecordManager.loadEntities();
            });
        </script>
    @endpush
</x-crud.index-layout>
