@props(['modalAction'])

<!-- Company Name Field -->
<div class="col-span-1 md:col-span-2">
    <label for="company_name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ __('company_name') }}:
    </label>
    <input type="text" x-model="form.company_name"
        @input="
            let words = $event.target.value.toLowerCase().split(' ');
            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            $event.target.value = words.join(' ');
            $wire.set('company_name', $event.target.value);
            validateField('company_name');
        "
        id="company_name"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        :class="{ 'border-red-500': errors.company_name }" placeholder="{{ __('enter_company_name') }}">
    <div class="text-red-500 text-xs mt-1" x-show="errors.company_name" x-text="errors.company_name"></div>
</div>

<!-- CEO Name Field -->
<div class="col-span-1 md:col-span-1">
    <label for="name" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ __('ceo_name') }}:
    </label>
    <input type="text" x-model="form.name"
        @input="
            validateField('name');
            // Only allow letters and spaces
            $event.target.value = $event.target.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            // Capitalize first letter of each word
            let words = $event.target.value.toLowerCase().split(' ');
            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            $event.target.value = words.join(' ');
            form.name = $event.target.value;
            $wire.set('name', $event.target.value);
        "
        @blur="validateField('name')"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:border-blue-300 dark:focus:border-blue-600"
        placeholder="{{ __('enter_ceo_name') }}">
    <div class="text-red-500 text-xs mt-1" x-show="errors.name" x-text="errors.name"></div>
</div>

<!-- Email Field -->
<div class="col-span-1 md:col-span-1">
    <label for="email" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ __('email') }}:
    </label>
    <input type="email" x-model="form.email"
        @input="$wire.set('email', $event.target.value); validateEmail($event.target.value);"
        @blur="checkEmailAvailability($event.target.value)" id="email"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        :class="{ 'border-red-500': errors.email }" placeholder="{{ __('enter_email') }}">
    <div class="text-red-500 text-xs mt-1" x-show="errors.email" x-text="errors.email"></div>
</div>

<!-- Phone Field -->
<div class="col-span-1 md:col-span-1">
    <label for="phone" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ __('phone') }}:
    </label>
    <input type="tel" x-model="form.phone"
        @input="import('/js/components/phoneFormat.js').then(module => {
            const phoneFormat = module.default;
            phoneFormat.formatPhoneInput($event, form, $wire);
            validatePhone(form.phone);
        });"
        @blur="validatePhone($event.target.value); checkPhoneAvailability($event.target.value);" id="phone"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        :class="{ 'border-red-500': errors.phone }" placeholder="(XXX) XXX-XXXX">
    <div class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></div>
</div>

<!-- Website Field -->
<div class="col-span-1 md:col-span-1">
    <label for="website" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ __('website') }}:
    </label>
    <input type="url" x-model="form.website"
        @input="$wire.set('website', $event.target.value); validateField('website');" id="website"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
        :class="{ 'border-red-500': errors.website }" placeholder="https://www.example.com">
    <div class="text-red-500 text-xs mt-1" x-show="errors.website" x-text="errors.website"></div>
    <span class="text-xs text-gray-500 mt-1">{{ __('enter_url_format') }}</span>
</div>

<!-- Address Field -->
<div class="col-span-1 md:col-span-2">
    <label for="address" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        {{ __('address') }}:
    </label>
    <input type="text" x-model="form.address"
        @input="$wire.set('address', $event.target.value); $event.target.value = $event.target.value.toUpperCase(); validateField('address');"
        id="address"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase"
        :class="{ 'border-red-500': errors.address }" placeholder="{{ __('enter_address') }}">
    <div class="text-red-500 text-xs mt-1" x-show="errors.address" x-text="errors.address"></div>
</div>

<!-- Hidden Fields -->
<input type="hidden" x-model="form.latitude" wire:model="latitude" id="latitude">
<input type="hidden" x-model="form.longitude" wire:model="longitude" id="longitude">

<!-- Alpine Script for Form Validation -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formValidation', formValidation);
    });

    document.addEventListener('DOMContentLoaded', () => {
        window.phoneFormat = phoneFormat;
    });
</script>
