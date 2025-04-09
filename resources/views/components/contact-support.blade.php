<!-- Contact Support Form -->
@php use App\Helpers\PhoneHelper; @endphp
<div class="bg-white rounded-lg shadow-lg p-8" x-data="{
    formData: {
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        message: '',
        sms_consent: false
    },
    errors: {},
    loading: false,
    validateField(field) {
        this.errors[field] = '';
        switch (field) {
            case 'first_name':
                if (!this.formData.first_name) this.errors.first_name = 'First name is required';
                else if (this.formData.first_name.length < 2) this.errors.first_name = 'First name must be at least 2 characters';
                else if (this.formData.first_name.includes(' ')) this.errors.first_name = 'First name cannot contain spaces';
                else if (this.formData.first_name[0] !== this.formData.first_name[0].toUpperCase()) this.errors.first_name = 'First name must be capitalized';
                break;
            case 'last_name':
                if (!this.formData.last_name) this.errors.last_name = 'Last name is required';
                else if (this.formData.last_name.length < 2) this.errors.last_name = 'Last name must be at least 2 characters';
                else if (this.formData.last_name.includes(' ')) this.errors.last_name = 'Last name cannot contain spaces';
                else if (this.formData.last_name[0] !== this.formData.last_name[0].toUpperCase()) this.errors.last_name = 'Last name must be capitalized';
                break;
            case 'email':
                if (!this.formData.email) this.errors.email = 'Email is required';
                else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) this.errors.email = 'Please enter a valid email';
                break;
            case 'phone':
                if (!this.formData.phone) this.errors.phone = 'Phone number is required';
                else if (!/^\(\d{3}\) \d{3}-\d{4}$/.test(this.formData.phone)) this.errors.phone = 'Please enter a valid phone number (XXX) XXX-XXXX';
                break;
            case 'message':
                if (!this.formData.message) this.errors.message = 'Message is required';
                else if (this.formData.message.length < 10) this.errors.message = 'Message must be at least 10 characters';
                break;
        }
        return !this.errors[field];
    },
    validateForm() {
        let isValid = true;
        ['first_name', 'last_name', 'email', 'phone', 'message'].forEach(field => {
            if (!this.validateField(field)) isValid = false;
        });
        return isValid;
    },
    formatName(fieldName) {
        let value = this.formData[fieldName];
        if (typeof value === 'string' && value.length > 0) {
            let parts = value.trim().split(' ');
            let firstWord = parts[0];
            // Capitalize first letter, lowercase rest, ensure only first word
            this.formData[fieldName] = firstWord.charAt(0).toUpperCase() + firstWord.slice(1).toLowerCase();
            // Optionally validate immediately
            // this.validateField(fieldName); 
        }
    },
    formatPhone() {
        let value = this.formData.phone.replace(/\D/g, '');
        value = value.substring(0, 10); // Limit to 10 digits

        let formattedValue = '';
        if (value.length === 0) {
            formattedValue = '';
        } else if (value.length <= 3) {
            formattedValue = `(${value}`;
        } else if (value.length <= 6) {
            formattedValue = `(${value.substring(0, 3)}) ${value.substring(3)}`;
        } else {
            formattedValue = `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
        }
        this.formData.phone = formattedValue;
        this.validateField('phone'); // Validate after formatting
    }
}"
    @support-request-success.window="
        loading = false;
        Swal.fire({
            title: 'Success!',
            text: $event.detail.message || 'Your message has been sent successfully!',
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f59e0b' // Optional: Match theme color
        });
        // Reset Alpine form data manually if needed after Livewire reset
        formData.first_name = '';
        formData.last_name = '';
        formData.email = '';
        formData.phone = '';
        formData.message = '';
        formData.sms_consent = false;
        errors = {}; // Clear Alpine errors
    "
    @support-request-error.window="
        loading = false;
        Swal.fire({
            title: 'Error',
            text: $event.detail.message || 'There was a problem sending your message. Please try again later.',
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: '#f59e0b'
        });
    "
    class="space-y-6">
    @csrf <!-- Add CSRF token -->
    {{-- Hidden Input for reCAPTCHA v3 Token --}}
    <input type="hidden" name="g-recaptcha-response" id="contact-g-recaptcha-response">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- First Name -->
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
            <input type="text" x-model="formData.first_name" @input="formatName('first_name')"
                @blur="validateField('first_name')" wire:model="first_name" id="first_name" name="first_name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                :class="{ 'border-red-500': errors.first_name }">
            <p class="mt-1 text-sm text-red-600" x-show="errors.first_name" x-text="errors.first_name"></p>
        </div>

        <!-- Last Name -->
        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type="text" x-model="formData.last_name" @input="formatName('last_name')"
                @blur="validateField('last_name')" wire:model="last_name" id="last_name" name="last_name"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                :class="{ 'border-red-500': errors.last_name }">
            <p class="mt-1 text-sm text-red-600" x-show="errors.last_name" x-text="errors.last_name"></p>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" x-model="formData.email" @blur="validateField('email')" wire:model="email"
                id="email" name="email"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                :class="{ 'border-red-500': errors.email }">
            <p class="mt-1 text-sm text-red-600" x-show="errors.email" x-text="errors.email"></p>
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" x-model="formData.phone" @blur="validateField('phone')" @input="formatPhone()"
                wire:model="phone" id="phone" name="phone"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                :class="{ 'border-red-500': errors.phone }">
            <p class="mt-1 text-sm text-red-600" x-show="errors.phone" x-text="errors.phone"></p>
        </div>
    </div>

    <!-- Message -->
    <div>
        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
        <textarea x-model="formData.message" @blur="validateField('message')" wire:model="message" id="message" name="message"
            rows="4"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
            :class="{ 'border-red-500': errors.message }"></textarea>
        <p class="mt-1 text-sm text-red-600" x-show="errors.message" x-text="errors.message"></p>
    </div>

    <!-- SMS Consent Checkbox -->
    <div class="mt-6">
        <label class="inline-flex items-start cursor-pointer">
            <input type="checkbox" x-model="formData.sms_consent" wire:model="sms_consent" id="sms_consent"
                name="sms_consent" value="1"
                class="form-checkbox text-yellow-500 mt-1 h-5 w-5 border-gray-300 rounded focus:ring-yellow-500">
            <span class="ml-2 text-sm text-gray-600">
                Yes, I would like to receive text messages from <span class="font-semibold">V GENERAL
                    CONTRACTORS</span> with offers, appointment reminders, and updates on roofing services. <span
                    class="font-semibold">Messaging Frequency may vary</span>. I understand that I can cancel my
                subscription at any time by replying <span class="font-semibold">STOP</span>. Reply <span
                    class="font-semibold">HELP {{ PhoneHelper::format($companyData->phone) }}</span> for assistance.
                Message and data rates apply. Information obtained as part of the SMS consent process will not be
                shared with third parties.
                <a href="{{ route('privacy-policy') }}" target="_blank"
                    class="text-yellow-500 hover:text-yellow-600">Privacy Policy</a>
                and <a href="{{ route('terms-and-conditions') }}" target="_blank"
                    class="text-yellow-500 hover:text-yellow-600">Terms
                    of Service</a>.
            </span>
        </label>
        <p class="mt-1 text-sm text-red-600" x-show="errors.sms_consent" x-text="errors.sms_consent"></p>
    </div>

    <!-- Submit Button -->
    <div class="flex justify-center py-10 mt-5">
        <x-primary-button type="submit" class="w-full md:w-auto"
            x-bind:disabled="loading || Object.values(errors).some(e => e !== '')"
            @click.prevent="
                if(validateForm()) {
                    loading = true;
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ config('captcha.sitekey') }}', {action: 'submit_contact_support'}).then(function(token) {
                            document.getElementById('contact-g-recaptcha-response').value = token;
                            // Now submit the Livewire action
                            $wire.set('captcha', token); // Pass token to Livewire
                            $wire.submit()
                                .then(() => {
                                    // Success handler is managed by the event listener
                                    console.log('Form submission success');
                                })
                                .catch((error) => {
                                    console.error('Form submission error:', error);
                                    // Fallback error handling in case event isn't triggered
                                    loading = false;
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'There was a problem submitting your form. Please try again.',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        confirmButtonColor: '#f59e0b'
                                    });
                                })
                                .finally(() => loading = false);
                        }).catch(function(error){
                            console.error('reCAPTCHA error:', error);
                            Swal.fire({
                                title: 'Verification Error',
                                text: 'reCAPTCHA verification failed. Please try again.',
                                icon: 'error',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#f59e0b' // Optional: Match theme color
                            });
                            loading = false;
                        });
                    });
                }
            ">
            <span x-show="!loading">Send Message</span>
            <span x-show="loading" class="flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Processing...
            </span>
        </x-primary-button>
    </div>
</div>

@pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('captcha.sitekey') }}"></script>
@endPushOnce
