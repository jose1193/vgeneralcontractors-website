@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div class="flex justify-between">
                <h2 class="text-2xl font-semibold leading-tight">{{ __('create_appointment') }}</h2>
                <a href="{{ route('appointments.index') }}"
                    class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('back_to_list') }}
                </a>
            </div>
            <div class="my-4 overflow-hidden sm:rounded-md">
                <form id="appointmentCreateForm" action="{{ secure_url(route('appointments.store', [], false)) }}"
                    method="POST" class=" dark:bg-gray-800 shadow-md rounded-lg p-6">
                    @csrf
                    @include('appointments._form')
                    <div class="mt-10 mb-3 flex justify-center">
                        <button type="submit" id="submit-button"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75 disabled:cursor-not-allowed">

                            {{-- Spinner (hidden initially) --}}
                            <svg id="submit-spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            <span id="submit-button-text">{{ __('create_appointment_btn') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('appointmentCreateForm');
            const submitButton = document.getElementById('submit-button');
            const submitSpinner = document.getElementById('submit-spinner');
            const submitButtonText = document.getElementById('submit-button-text');

            // Extra code to ensure insurance radio buttons styling works
            const insuranceRadios = document.querySelectorAll('input[name="insurance_property"]');
            const insuranceLabels = document.querySelectorAll('.insurance-label');

            // Initial setup - ensure selected radio has its label styled
            insuranceRadios.forEach(radio => {
                if (radio.checked) {
                    const label = document.querySelector(`label[for="${radio.id}"]`);
                    if (label) {
                        label.classList.add('selected');
                    }
                }
            });

            // Add click event listeners directly to the labels (more responsive)
            insuranceLabels.forEach(label => {
                label.addEventListener('click', function() {
                    // Find the associated radio button and check it
                    const radioId = this.getAttribute('for');
                    const radio = document.getElementById(radioId);
                    if (radio) {
                        radio.checked = true;

                        // Update styles immediately
                        insuranceLabels.forEach(l => l.classList.remove('selected'));
                        this.classList.add('selected');
                    }
                });
            });

            // Change event handlers as backup
            insuranceRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // Remove selected class from all labels
                    insuranceLabels.forEach(label => {
                        label.classList.remove('selected');
                    });

                    // Add selected class to the checked radio's label
                    if (this.checked) {
                        const label = document.querySelector(`label[for="${this.id}"]`);
                        if (label) {
                            label.classList.add('selected');
                        }
                    }
                });
            });

            // Function to set loading state
            function setLoadingState(isLoading) {
                submitButton.disabled = isLoading;
                if (isLoading) {
                    submitSpinner.classList.remove('hidden');
                    submitButtonText.textContent = '{{ __('sending') }}...';
                } else {
                    submitSpinner.classList.add('hidden');
                    submitButtonText.textContent = '{{ __('create_appointment_btn') }}';
                }
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Show spinner and disable button
                setLoadingState(true);

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: new FormData(form)
                    })
                    .then(response => {
                        const contentType = response.headers.get("content-type");
                        if (!response.ok && !(contentType && contentType.indexOf("application/json") !== -1 && response.status === 422)) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json().then(data => ({
                            status: response.status,
                            body: data
                        }));
                    })
                    .then(({ status, body }) => {
                        if (status === 422 && body.errors) {
                            // Display validation errors
                            displayErrors(body.errors);
                            
                            // Show notification with SweetAlert
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });

                            Toast.fire({
                                icon: 'warning',
                                title: '{{ __('swal_correct_errors') }}'
                            });
                        } else if (body.success) {
                            // Reset form before showing success message
                            form.reset();

                            // Reset button state as well
                            setLoadingState(false);

                            Swal.fire({
                                title: '{{ __('swal_success') }}',
                                text: body.message,
                                icon: 'success',
                                confirmButtonText: '{{ __('swal_ok') }}',
                                confirmButtonColor: '#f59e0b'
                            }).then(() => {
                                window.location.href = '{{ route('appointments.index') }}';
                            });
                        } else {
                            Swal.fire({
                                title: '{{ __('swal_error') }}',
                                text: body.message || '{{ __('swal_unknown_error') }}',
                                icon: 'error',
                                confirmButtonText: '{{ __('swal_ok') }}',
                                confirmButtonColor: '#f59e0b'
                            });

                            // Hide spinner and enable button on error
                            setLoadingState(false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: '{{ __('error_occurred') }}',
                            text: '{{ __('unexpected_error_occurred') }}',
                            icon: 'error',
                            confirmButtonText: '{{ __('swal_ok') }}'
                        });

                        // Hide spinner and enable button on error
                        setLoadingState(false);
                    })
                    .finally(() => {
                        // Hide loading spinner
                        setLoadingState(false);
                    });
            });

            // Function to display validation errors
            function displayErrors(errors) {
                clearAllErrors();
                let firstErrorField = null;
                for (const field in errors) {
                    const errorSpan = document.querySelector(`.error-message[data-field="${field}"]`);
                    const inputElement = document.querySelector(`[name="${field}"]`);

                    if (errorSpan && errors[field]?.[0]) {
                        errorSpan.textContent = errors[field][0];
                        errorSpan.classList.remove('hidden');
                    }
                    if (inputElement) {
                        inputElement.classList.add('border-red-500');
                        if (!firstErrorField) firstErrorField = inputElement;
                    }
                }
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            // Function to clear all errors
            function clearAllErrors() {
                document.querySelectorAll('.error-message').forEach(span => {
                    span.textContent = '';
                    span.classList.add('hidden');
                });
                document.querySelectorAll('.input-field, input, select, textarea').forEach(input => {
                    input.classList.remove('border-red-500');
                });
            }

            // Add event listeners to clear errors on input
            document.querySelectorAll('input, select, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    const errorSpan = document.querySelector(`.error-message[data-field="${this.name}"]`);
                    if (errorSpan) {
                        errorSpan.textContent = '';
                        errorSpan.classList.add('hidden');
                    }
                    this.classList.remove('border-red-500');
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Estilos para los radio buttons de insurance */
        .insurance-label {
            transition: all 0.2s ease;
            background-color: white;
            border: 2px solid #e5e7eb;
        }

        .insurance-label:hover {
            background-color: #facc15 !important;
            color: white !important;
            border-color: #eab308 !important;
        }

        .insurance-label.selected {
            background-color: #f59e0b !important;
            color: white !important;
            border-color: #d97706 !important;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush
