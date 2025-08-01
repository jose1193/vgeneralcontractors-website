@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div class="flex justify-between my-5">
                <h2 class="text-2xl font-semibold leading-tight text-white">
                    {{ isset($appointment->uuid) ? __('edit_appointment') : __('create_appointment') }}
                </h2>
                <a href="{{ route('appointments.index') }}"
                    class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('back_to_list') }}
                </a>
            </div>
            <div class="my-4 overflow-hidden sm:rounded-md">
                <form id="{{ isset($appointment->uuid) ? 'appointmentEditForm' : 'appointmentCreateForm' }}"
                    action="{{ isset($appointment->uuid) ? secure_url(route('appointments.update', $appointment->uuid, false)) : secure_url(route('appointments.store', [], false)) }}"
                    method="POST" class="glassmorphism-form-container shadow-md rounded-lg p-6">
                    @csrf
                    @if (isset($appointment->uuid))
                        @method('PUT')
                    @endif
                    @include('appointments._form')
                    <div class="mt-10 mb-3 flex justify-center">
                        <button type="submit" id="submit-button" disabled
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">

                            {{-- Spinner (hidden initially) --}}
                            <svg id="submit-spinner" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>

                            <span
                                id="submit-button-text">{{ isset($appointment->uuid) ? __('update_appointment_btn') : __('create_appointment_btn') }}</span>
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

    <style>
        /* Modern Dark Crystal Form 2025 with Purple Accents */
        .glassmorphism-form-container {
            position: relative;
            border-radius: 16px;
            overflow: hidden;

            /* Dark Crystal Background */
            background: linear-gradient(135deg, 
                rgba(17, 17, 17, 0.95) 0%,
                rgba(30, 30, 30, 0.92) 50%,
                rgba(20, 20, 20, 0.95) 100%);

            /* Elegant Border */
            border: 1px solid rgba(139, 69, 190, 0.3);
            
            /* Modern Shadow System */
            box-shadow:
                0 8px 32px rgba(139, 69, 190, 0.15),
                0 4px 16px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);

            /* Subtle Blur */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);

            /* Smooth Animation */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glassmorphism-form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, 
                transparent 0%,
                rgba(139, 69, 190, 0.8) 25%,
                rgba(168, 85, 247, 0.9) 50%,
                rgba(139, 69, 190, 0.8) 75%,
                transparent 100%);
            opacity: 0.8;
        }

        .glassmorphism-form-container:hover {
            transform: translateY(-2px);
            border-color: rgba(168, 85, 247, 0.5);
            box-shadow:
                0 12px 40px rgba(139, 69, 190, 0.25),
                0 6px 20px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
        }

        /* Input Fields Enhancement */
        .glassmorphism-form-container input,
        .glassmorphism-form-container select,
        .glassmorphism-form-container textarea {
            background: rgba(40, 40, 40, 0.8) !important;
            border: 1px solid rgba(139, 69, 190, 0.3) !important;
            color: #ffffff !important;
            border-radius: 8px !important;
            transition: all 0.3s ease !important;
        }

        .glassmorphism-form-container input:focus,
        .glassmorphism-form-container select:focus,
        .glassmorphism-form-container textarea:focus {
            border-color: rgba(168, 85, 247, 0.6) !important;
            box-shadow: 0 0 0 3px rgba(139, 69, 190, 0.2) !important;
            background: rgba(50, 50, 50, 0.9) !important;
        }

        /* Labels Enhancement */
        .glassmorphism-form-container label {
            color: #e5e7eb !important;
            font-weight: 500;
        }

        /* Custom styles for form validation feedback */
        .field-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2) !important;
        }

        .field-valid {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
        }

        .submit-button-disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .submit-button-enabled {
            opacity: 1 !important;
            cursor: pointer !important;
            pointer-events: auto;
        }

        .realtime-validation-message {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Loading spinner for real-time validation */
        .validation-loading {
            position: relative;
        }

        .validation-loading::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid rgba(139, 69, 190, 0.3);
            border-top: 2px solid #8b45be;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: translateY(-50%) rotate(0deg);
            }

            100% {
                transform: translateY(-50%) rotate(360deg);
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById(
                '{{ isset($appointment->uuid) ? 'appointmentEditForm' : 'appointmentCreateForm' }}');
            const submitButton = document.getElementById('submit-button');
            const submitSpinner = document.getElementById('submit-spinner');
            const submitButtonText = document.getElementById('submit-button-text');

            // Function to set loading state
            function setLoadingState(isLoading) {
                submitButton.disabled = isLoading;
                if (isLoading) {
                    submitSpinner.classList.remove('hidden');
                    submitButtonText.textContent = '{{ __('sending') }}...';
                } else {
                    submitSpinner.classList.add('hidden');
                    submitButtonText.textContent =
                        '{{ isset($appointment->uuid) ? __('update_appointment_btn') : __('create_appointment_btn') }}';
                }
            }

            // Form validation function
            function validateForm() {
                const requiredFields = [
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'address_map_input',
                    'city',
                    'state',
                    'zipcode',
                    'country',
                    'lead_source',
                    'inspection_status',
                    'status_lead'
                ];

                let isValid = true;

                // Check text/email/select fields
                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field && (!field.value.trim() || field.value === '')) {
                        isValid = false;
                    }
                });

                // Check radio buttons for insurance_property
                const insuranceRadios = document.querySelectorAll('input[name="insurance_property"]');
                const insuranceChecked = Array.from(insuranceRadios).some(radio => radio.checked);
                if (!insuranceChecked) {
                    isValid = false;
                }

                // Special validation for names (letters only)
                const firstName = document.getElementById('first_name');
                const lastName = document.getElementById('last_name');
                const namePattern = /^[A-Za-z\s\'-]+$/;

                if (firstName && firstName.value.trim() && !namePattern.test(firstName.value.trim())) {
                    isValid = false;
                }

                if (lastName && lastName.value.trim() && !namePattern.test(lastName.value.trim())) {
                    isValid = false;
                }

                // Email validation
                const email = document.getElementById('email');
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (email && email.value.trim() && !emailPattern.test(email.value.trim())) {
                    isValid = false;
                }

                // Check for duplicate indicators
                const emailField = document.getElementById('email');
                const phoneField = document.getElementById('phone');

                if (emailField && emailField.classList.contains('field-invalid')) {
                    isValid = false;
                }

                if (phoneField && phoneField.classList.contains('field-invalid')) {
                    isValid = false;
                }

                // Special date/time validation logic
                const inspectionDate = document.getElementById('inspection_date');
                const inspectionTimeHour = document.getElementById('inspection_time_hour');
                const inspectionTimeMinute = document.getElementById('inspection_time_minute');

                // If inspection date is selected, both hour and minute must be selected
                if (inspectionDate && inspectionDate.value) {
                    if (!inspectionTimeHour || !inspectionTimeHour.value ||
                        !inspectionTimeMinute || !inspectionTimeMinute.value) {
                        isValid = false;
                    }
                }

                return isValid;
            }

            // Function to update submit button state
            function updateSubmitButton() {
                const isFormValid = validateForm();
                submitButton.disabled = !isFormValid;

                if (isFormValid) {
                    submitButton.classList.remove('submit-button-disabled');
                    submitButton.classList.add('submit-button-enabled');
                } else {
                    submitButton.classList.remove('submit-button-enabled');
                    submitButton.classList.add('submit-button-disabled');
                }
            }

            // Function to validate individual field and provide visual feedback
            function validateField(fieldElement, value = null) {
                if (!fieldElement) return true;

                const fieldValue = value !== null ? value : fieldElement.value.trim();
                const fieldName = fieldElement.name || fieldElement.id;
                let isValid = true;

                // Remove existing validation classes
                fieldElement.classList.remove('field-valid', 'field-invalid');

                // Skip validation for optional fields or if field is empty and not required
                if (!fieldElement.hasAttribute('required') && !fieldValue) {
                    return true;
                }

                // Validate based on field type and name
                switch (fieldName) {
                    case 'first_name':
                    case 'last_name':
                        const namePattern = /^[A-Za-z\s\'-]+$/;
                        isValid = fieldValue && namePattern.test(fieldValue);
                        break;

                    case 'email':
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        isValid = fieldValue && emailPattern.test(fieldValue);
                        break;

                    case 'phone':
                        isValid = fieldValue && fieldValue.length >= 10;
                        break;

                    default:
                        // For other required fields, just check if they have a value
                        if (fieldElement.hasAttribute('required')) {
                            isValid = fieldValue !== '' && fieldValue !== null;
                        }
                        break;
                }

                // Apply visual feedback
                if (fieldValue) { // Only apply visual feedback if field has content
                    if (isValid) {
                        fieldElement.classList.add('field-valid');
                    } else {
                        fieldElement.classList.add('field-invalid');
                    }
                }

                return isValid;
            }

            // Function to check if email exists in real-time
            function checkEmailExists(email, excludeUuid = null) {
                if (!email || email.trim() === '') return Promise.resolve(false);

                const formData = new FormData();
                formData.append('email', email);
                if (excludeUuid) {
                    formData.append('exclude_uuid', excludeUuid);
                }

                return fetch('{{ secure_url(route('appointments.check-email', [], false)) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            return data.exists;
                        }
                        return false;
                    })
                    .catch(error => {
                        console.error('Error checking email:', error);
                        return false;
                    });
            }

            // Function to check if phone exists in real-time
            function checkPhoneExists(phone, excludeUuid = null) {
                if (!phone || phone.trim() === '') return Promise.resolve(false);

                const formData = new FormData();
                formData.append('phone', phone);
                if (excludeUuid) {
                    formData.append('exclude_uuid', excludeUuid);
                }

                return fetch('{{ secure_url(route('appointments.check-phone', [], false)) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            return data.exists;
                        }
                        return false;
                    })
                    .catch(error => {
                        console.error('Error checking phone:', error);
                        return false;
                    });
            }

            // Function to show validation message
            function showValidationMessage(fieldElement, message, isError = true) {
                // Remove existing messages
                const existingMessage = fieldElement.parentNode.querySelector('.realtime-validation-message');
                if (existingMessage) {
                    existingMessage.remove();
                }

                if (message) {
                    const messageElement = document.createElement('div');
                    messageElement.className =
                        `realtime-validation-message text-xs mt-1 ${isError ? 'text-red-500' : 'text-green-500'}`;
                    messageElement.textContent = message;
                    fieldElement.parentNode.appendChild(messageElement);
                }
            }

            // Add event listeners to all form fields
            const allInputs = form.querySelectorAll('input, select, textarea');
            allInputs.forEach(input => {
                // Real-time validation on input/change
                input.addEventListener('input', function(e) {
                    validateField(e.target);
                    updateSubmitButton();
                });

                input.addEventListener('change', function(e) {
                    validateField(e.target);
                    updateSubmitButton();
                });

                input.addEventListener('blur', function(e) {
                    validateField(e.target);
                    updateSubmitButton();
                });
            });

            // Special handling for email field - Real-time duplicate check
            const emailField = document.getElementById('email');
            if (emailField) {
                let emailTimeout;
                emailField.addEventListener('input', function(e) {
                    clearTimeout(emailTimeout);
                    const email = e.target.value.trim();

                    // Clear previous messages
                    showValidationMessage(e.target, '');
                    e.target.classList.remove('validation-loading');

                    if (email && email.includes('@')) {
                        // Show loading indicator
                        e.target.classList.add('validation-loading');

                        emailTimeout = setTimeout(() => {
                            const excludeUuid =
                                '{{ isset($appointment->uuid) ? $appointment->uuid : null }}';
                            checkEmailExists(email, excludeUuid).then(exists => {
                                e.target.classList.remove('validation-loading');
                                if (exists) {
                                    showValidationMessage(e.target,
                                        '{{ __('This email is already registered') }}',
                                        true);
                                    e.target.classList.add('field-invalid');
                                    e.target.classList.remove('field-valid');
                                } else {
                                    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                    if (emailPattern.test(email)) {
                                        showValidationMessage(e.target,
                                            '{{ __('Email is available') }}', false);
                                        e.target.classList.add('field-valid');
                                        e.target.classList.remove('field-invalid');
                                    }
                                }
                                updateSubmitButton();
                            }).catch(() => {
                                e.target.classList.remove('validation-loading');
                                updateSubmitButton();
                            });
                        }, 800); // Wait 800ms after user stops typing
                    }
                });
            }

            // Special handling for phone field - Real-time duplicate check
            const phoneField = document.getElementById('phone');
            if (phoneField) {
                let phoneTimeout;
                phoneField.addEventListener('input', function(e) {
                    clearTimeout(phoneTimeout);
                    const phone = e.target.value.trim();

                    // Clear previous messages
                    showValidationMessage(e.target, '');
                    e.target.classList.remove('validation-loading');

                    if (phone && phone.length >= 10) {
                        // Show loading indicator
                        e.target.classList.add('validation-loading');

                        phoneTimeout = setTimeout(() => {
                            const excludeUuid =
                                '{{ isset($appointment->uuid) ? $appointment->uuid : null }}';
                            checkPhoneExists(phone, excludeUuid).then(exists => {
                                e.target.classList.remove('validation-loading');
                                if (exists) {
                                    showValidationMessage(e.target,
                                        '{{ __('This phone number is already registered') }}',
                                        true);
                                    e.target.classList.add('field-invalid');
                                    e.target.classList.remove('field-valid');
                                } else {
                                    showValidationMessage(e.target,
                                        '{{ __('Phone number is available') }}', false);
                                    e.target.classList.add('field-valid');
                                    e.target.classList.remove('field-invalid');
                                }
                                updateSubmitButton();
                            }).catch(() => {
                                e.target.classList.remove('validation-loading');
                                updateSubmitButton();
                            });
                        }, 800); // Wait 800ms after user stops typing
                    }
                });
            } // Special handling for radio buttons (insurance_property)
            const insuranceRadios = document.querySelectorAll('input[name="insurance_property"]');
            insuranceRadios.forEach(radio => {
                radio.addEventListener('change', updateSubmitButton);
            });

            // Special handling for time fields that might be added dynamically
            document.addEventListener('change', function(e) {
                if (e.target.id === 'inspection_time_hour' || e.target.id === 'inspection_time_minute') {
                    updateSubmitButton();
                }
            });

            // Initial validation check
            updateSubmitButton();

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Final validation check before submission
                if (!validateForm()) {
                    Swal.fire({
                        title: '{{ __('validation_error') }}',
                        text: '{{ __('please_fill_required_fields') }}',
                        icon: 'warning',
                        confirmButtonText: '{{ __('swal_ok') }}'
                    });
                    return;
                }

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
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reset form before showing success message
                            form.reset();

                            // Reset button state as well
                            setLoadingState(false);

                            Swal.fire({
                                title: '{{ __('success_title') }}',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: '{{ __('swal_ok') }}'
                            }).then(() => {
                                // Use redirectUrl from response if available
                                window.location.href = data.redirectUrl ||
                                    "{{ route('appointments.index') }}";
                            });
                        } else {
                            // Check specifically for scheduling conflicts
                            if (data.errors && data.errors.schedule_conflict) {
                                Swal.fire({
                                    title: '{{ __('scheduling_conflict') }}',
                                    text: data.errors.schedule_conflict,
                                    icon: 'warning',
                                    confirmButtonText: '{{ __('swal_ok') }}'
                                });
                            } else {
                                let errorMessage = data.message;
                                if (data.errors) {
                                    errorMessage += '\n';
                                    Object.values(data.errors).forEach(error => {
                                        errorMessage += '\n• ' + error;
                                    });
                                }

                                Swal.fire({
                                    title: '{{ __('error_title') }}',
                                    text: errorMessage,
                                    icon: 'error',
                                    confirmButtonText: '{{ __('swal_ok') }}'
                                });
                            }

                            // Hide spinner and enable button on error
                            setLoadingState(false);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: '{{ __('error_title') }}',
                            text: '{{ __('unexpected_error_occurred') }}',
                            icon: 'error',
                            confirmButtonText: '{{ __('swal_ok') }}'
                        });

                        // Hide spinner and enable button on error
                        setLoadingState(false);
                    });
            });

            // Funcionalidad de compartir ubicación
            const shareWhatsApp = document.getElementById('share-whatsapp');
            const shareEmail = document.getElementById('share-email');
            const shareMaps = document.getElementById('share-maps');
            const copyAddress = document.getElementById('copy-address');

            if (shareWhatsApp && shareEmail && shareMaps && copyAddress) {
                const updateShareLinks = () => {
                    const lat = document.getElementById('latitude').value;
                    const lng = document.getElementById('longitude').value;
                    const address = document.getElementById('address_map_input').value;

                    if (!lat || !lng) {
                        // Deshabilitar botones si no hay coordenadas
                        [shareWhatsApp, shareEmail, shareMaps, copyAddress].forEach(btn => {
                            btn.classList.add('opacity-50', 'cursor-not-allowed');
                            btn.setAttribute('disabled', 'disabled');
                        });
                        return;
                    }

                    // Habilitar botones
                    [shareWhatsApp, shareEmail, shareMaps, copyAddress].forEach(btn => {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        btn.removeAttribute('disabled');
                    });

                    const mapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;

                    // WhatsApp
                    shareWhatsApp.href =
                        `https://wa.me/?text={{ __('location_for_inspection') }}: ${encodeURIComponent(address)} - ${encodeURIComponent(mapsUrl)}`;
                    shareWhatsApp.target = '_blank';

                    // Email
                    const subject = encodeURIComponent('{{ __('location_for_inspection') }}');
                    const body = encodeURIComponent(
                        `{{ __('location_for_inspection') }}: ${address}\n\n{{ __('view_google_maps') }}: ${mapsUrl}`
                    );
                    shareEmail.href = `mailto:?subject=${subject}&body=${body}`;

                    // Maps
                    shareMaps.href = mapsUrl;
                    shareMaps.target = '_blank';

                    // Copy link
                    copyAddress.addEventListener('click', function(e) {
                        e.preventDefault();
                        navigator.clipboard.writeText(mapsUrl).then(() => {
                            // Mostrar mensaje de confirmación
                            const originalText = this.innerHTML;
                            this.innerHTML =
                                '<svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> {{ __('copied') }}';
                            setTimeout(() => {
                                this.innerHTML = originalText;
                            }, 2000);
                        });
                    });
                };

                // Actualizar enlaces cuando cambie la dirección
                document.getElementById('address_map_input').addEventListener('change', updateShareLinks);

                // Inicializar enlaces
                updateShareLinks();

                // Actualizar enlaces cuando cambie el mapa
                if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                    // Si se usa autocomplete, escuchar ese evento también
                    if (typeof autocomplete !== 'undefined') {
                        google.maps.event.addListener(autocomplete, 'place_changed', updateShareLinks);
                    }
                }
            }
        });
    </script>
@endpush
