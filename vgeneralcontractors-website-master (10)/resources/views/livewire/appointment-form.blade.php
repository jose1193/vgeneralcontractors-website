<div class="bg-white py-8 fade-in-section relative" x-data="{
    formData: {
        name: '',
        phone: '',
        email: '',
        city: '',
        zipcode: '',
        insurance: '',
        message: '',
        sms_consent: false
    },
    errors: {},
    validateField(field) {
        switch (field) {
            case 'name':
                if (!this.formData.name) {
                    this.errors.name = 'Name is required';
                } else if (!/^[A-Za-z\s]+$/.test(this.formData.name)) {
                    this.errors.name = 'Name must contain only letters';
                } else {
                    delete this.errors.name;
                }
                break;
            case 'phone':
                if (!this.formData.phone) {
                    this.errors.phone = 'Phone is required';
                } else if (!/^\(\d{3}\)\s\d{3}-\d{4}$/.test(this.formData.phone)) {
                    this.errors.phone = 'Phone must be in format (XXX) XXX-XXXX';
                } else {
                    delete this.errors.phone;
                }
                break;
            case 'email':
                if (!this.formData.email) {
                    this.errors.email = 'Email is required';
                } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) {
                    this.errors.email = 'Please enter a valid email';
                } else {
                    delete this.errors.email;
                }
                break;
            case 'city':
                if (!this.formData.city) {
                    this.errors.city = 'City is required';
                } else {
                    delete this.errors.city;
                }
                break;
            case 'zipcode':
                if (!this.formData.zipcode) {
                    this.errors.zipcode = 'Zip code is required';
                } else if (!/^\d{5}$/.test(this.formData.zipcode)) {
                    this.errors.zipcode = 'Please enter a valid 5-digit zip code';
                } else {
                    delete this.errors.zipcode;
                }
                break;
            case 'insurance':
                if (!this.formData.insurance) {
                    this.errors.insurance = 'Please select an option';
                } else {
                    delete this.errors.insurance;
                }
                break;
        }
    },
    validateForm() {
        this.errors = {};
        ['name', 'phone', 'email', 'city', 'zipcode', 'insurance'].forEach(field => this.validateField(field));
        return Object.keys(this.errors).length === 0;
    },
    formatPhone(e) {
        if (e.inputType === 'deleteContentBackward') {
            let value = this.formData.phone.replace(/\D/g, '');
            value = value.substring(0, value.length - 1);

            if (value.length === 0) {
                this.formData.phone = '';
            } else if (value.length <= 3) {
                this.formData.phone = `(${value}`;
            } else if (value.length <= 6) {
                this.formData.phone = `(${value.substring(0,3)}) ${value.substring(3)}`;
            } else {
                this.formData.phone = `(${value.substring(0,3)}) ${value.substring(3,6)}-${value.substring(6)}`;
            }
            this.validateField('phone');
            return;
        }

        let value = e.target.value.replace(/\D/g, '').substring(0, 10);
        if (value.length >= 6) {
            this.formData.phone = `(${value.substring(0,3)}) ${value.substring(3,6)}-${value.substring(6)}`;
        } else if (value.length >= 3) {
            this.formData.phone = `(${value.substring(0,3)}) ${value.substring(3)}`;
        } else if (value.length > 0) {
            this.formData.phone = `(${value}`;
        }
        this.validateField('phone');
    }
}">
    <!-- Close Button -->
    <button @click="showAppointmentModal = false"
        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors duration-200 shadow-lg z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-4 bg-yellow-500 py-4 rounded-lg shadow-lg">Get Your Free
                    Inspection</h2>
                <p class="text-lg text-gray-600">Fill out the form below and our team will contact you shortly to
                    schedule your free inspection.</p>
            </div>

            <form wire:submit.prevent="submit" @submit="if(!validateForm()) $event.preventDefault()" class="space-y-6">
                @csrf

                <!-- Success Message -->
                @if ($success)
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6"
                        role="alert">
                        <strong class="font-bold">Success!</strong>
                        <span class="block sm:inline">We've received your request and will contact you shortly.</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" id="name" x-model="formData.name" wire:model="name"
                            @input="validateField('name')"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition text-gray-900"
                            :class="{ 'border-red-500': errors.name }">
                        <div x-show="errors.name" x-text="errors.name" class="text-red-500 text-sm mt-1"></div>
                        @error('name')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="tel" id="phone" x-model="formData.phone" wire:model="phone"
                            @input="formatPhone($event)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition text-gray-900"
                            :class="{ 'border-red-500': errors.phone }">
                        <div x-show="errors.phone" x-text="errors.phone" class="text-red-500 text-sm mt-1"></div>
                        @error('phone')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" x-model="formData.email" wire:model="email"
                            @input="validateField('email')"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition text-gray-900"
                            :class="{ 'border-red-500': errors.email }">
                        <div x-show="errors.email" x-text="errors.email" class="text-red-500 text-sm mt-1"></div>
                        @error('email')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City Field -->
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <select id="city" x-model="formData.city" wire:model="city" @change="validateField('city')"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition text-gray-900"
                            :class="{ 'border-red-500': errors.city }">
                            <option value="">Select a city</option>
                            <option value="Houston">Houston</option>
                            <option value="Dallas">Dallas</option>
                        </select>
                        <div x-show="errors.city" x-text="errors.city" class="text-red-500 text-sm mt-1"></div>
                        @error('city')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Zip Code Field -->
                    <div>
                        <label for="zipcode" class="block text-sm font-medium text-gray-700 mb-1">Zip Code</label>
                        <input type="text" id="zipcode" x-model="formData.zipcode" wire:model="zipcode"
                            @input="validateField('zipcode')"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition text-gray-900"
                            :class="{ 'border-red-500': errors.zipcode }">
                        <div x-show="errors.zipcode" x-text="errors.zipcode" class="text-red-500 text-sm mt-1"></div>
                        @error('zipcode')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Insurance Property Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Property</label>
                        <div class="flex space-x-4">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" x-model="formData.insurance" wire:model="insurance" value="yes"
                                    @change="validateField('insurance')" class="form-radio text-yellow-500 h-5 w-5">
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" x-model="formData.insurance" wire:model="insurance"
                                    value="no" @change="validateField('insurance')"
                                    class="form-radio text-yellow-500 h-5 w-5">
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                        </div>
                        <div x-show="errors.insurance" x-text="errors.insurance" class="text-red-500 text-sm mt-1">
                        </div>
                        @error('insurance')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Message Field -->
                    <div class="md:col-span-2">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Comment or
                            Message</label>
                        <textarea id="message" x-model="formData.message" wire:model="message" rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition resize-none text-gray-900"></textarea>
                    </div>
                </div>

                <!-- SMS Consent Checkbox -->
                <div class="mt-6">
                    <label class="inline-flex items-start cursor-pointer">
                        <input type="checkbox" x-model="formData.sms_consent" wire:model="sms_consent"
                            class="form-checkbox text-yellow-500 mt-1 h-5 w-5">
                        <span class="ml-2 text-sm text-gray-600">
                            Yes, I would like to receive text messages from <span class="font-bold">V GENERAL
                                CONTRACTORS</span>
                            with offers, appointment reminders, and updates on roofing services.
                            <span class="font-bold">Messaging Frequency may vary</span>. I understand that I can cancel
                            my subscription at any time by replying <span class="font-bold">STOP. Reply HELP for
                                assistance</span>.
                            Message and data rates apply.
                            <a href="#" class="text-yellow-500 hover:text-yellow-600">Privacy Policy</a> and
                            <a href="#" class="text-yellow-500 hover:text-yellow-600">Terms of Service</a>.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-8">
                    <x-primary-button type="submit" :disabled="$errors->any()">
                        <span wire:loading.remove>Schedule Free Inspection</span>
                        <span wire:loading class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Processing...
                        </span>
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</div>
