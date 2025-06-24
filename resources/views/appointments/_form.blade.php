@csrf
{{-- Display validation errors --}}
@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-300"
        role="alert">
        <strong class="font-bold">Validation Error!</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- First Name --}}
    <div>
        <label for="first_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('first_name') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="first_name" class="input-field block mt-1 w-full capitalize" type="text" name="first_name"
            :value="old('first_name', $appointment->first_name ?? '')" autofocus maxlength="50" pattern="[A-Za-z\s\\'-]+"
            title="{{ __('first_name_validation') }}" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="first_name"></span>
        <x-input-error for="first_name" class="mt-2" />
    </div>

    {{-- Last Name --}}
    <div>
        <label for="last_name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('last_name') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="last_name" class="input-field block mt-1 w-full capitalize" type="text" name="last_name"
            :value="old('last_name', $appointment->last_name ?? '')" maxlength="50" pattern="[A-Za-z\s\\'-]+" title="{{ __('last_name_validation') }}" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="last_name"></span>
        <x-input-error for="last_name" class="mt-2" />
    </div>

    {{-- Phone --}}
    <div>
        <label for="phone" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('phone') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="phone" class="input-field block mt-1 w-full" type="tel" name="phone"
            placeholder="(XXX) XXX-XXXX" :value="old('phone', $appointment->phone ?? '')" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="phone"></span>
        <x-input-error for="phone" class="mt-2" />
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('email') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="email" class="input-field block mt-1 w-full" type="email" name="email" :value="old('email', $appointment->email ?? '')" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="email"></span>
        <x-input-error for="email" class="mt-2" />
    </div>

    {{-- Address Map Input (for Google Maps Autocomplete) --}}
    <div class="md:col-span-2">
        <label for="address_map_input" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('address') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="address_map_input" class="input-field block mt-1 w-full" type="text" name="address_map_input"
            placeholder="Enter complete address for autocomplete" :value="old('address', $appointment->address ?? '')" autocomplete="off" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="address_map_input"></span>
        <x-input-error for="address_map_input" class="mt-2" />
    </div>

    {{-- Map Display --}}
    <div class="md:col-span-2 mt-2 mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Location Map</label>
        <div id="location-map"
            class="w-full h-48 bg-gray-200 rounded-lg border border-gray-300 dark:bg-gray-700 dark:border-gray-600">
        </div>

        {{-- Share location buttons --}}
        <div class="mt-2 flex flex-wrap gap-2">
            <button id="show-address-modal"
                class="inline-flex items-center px-3 py-2 bg-indigo-500 text-white text-sm font-medium rounded-md hover:bg-indigo-600">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('view_complete_address') }}
            </button>
            <button id="share-location-options"
                class="inline-flex items-center px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                </svg>
                {{ __('choose_how_share_location') }}
            </button>
        </div>

        {{-- Share options dropdown (hidden by default) --}}
        <div id="share-options" class="hidden mt-2">
            <a href="#" id="share-whatsapp"
                class="inline-flex items-center px-3 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-600">
                <svg class="h-5 w-5 mr-1" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.498 14.382l-1.87-1.147c-0.308-0.24-0.705-0.242-1.058-0.046l-1.103 0.69c-0.26 0.16-0.563 0.217-0.858 0.147c-0.893-0.216-2.404-1.511-3.122-2.251c-0.483-0.502-1.038-1.489-1.254-2.362c-0.09-0.351 0.014-0.721 0.269-0.974l0.74-0.73c0.243-0.241 0.37-0.567 0.354-0.904l-0.075-1.78c-0.035-0.843-0.913-1.384-1.693-1.041l-0.807 0.353c-0.905 0.405-1.467 1.268-1.457 2.241c0.01 0.935 0.307 3.375 2.301 6.123c2.035 2.809 4.372 3.526 5.338 3.628c0.975 0.103 1.926-0.621 2.251-1.505l0.282-0.861c0.256-0.788-0.343-1.623-1.238-1.724z" />
                </svg>
                WhatsApp
            </a>
            <a href="#" id="share-email"
                class="inline-flex items-center px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-md hover:bg-blue-600">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email
            </a>
            <a href="#" id="share-maps"
                class="inline-flex items-center px-3 py-2 bg-red-500 text-white text-sm font-medium rounded-md hover:bg-red-600">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('open_in_maps') }}
            </a>
            <button id="copy-address"
                class="inline-flex items-center px-3 py-2 bg-gray-500 text-white text-sm font-medium rounded-md hover:bg-gray-600">
                <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                {{ __('copy_address') }}
            </button>
        </div>
    </div>

    {{-- Hidden Address Fields --}}
    <input type="hidden" id="address" name="address" value="{{ old('address', $appointment->address ?? '') }}">
    <input type="hidden" id="latitude" name="latitude"
        value="{{ old('latitude', $appointment->latitude ?? '') }}">
    <input type="hidden" id="longitude" name="longitude"
        value="{{ old('longitude', $appointment->longitude ?? '') }}">

    {{-- Address 2 --}}
    <div class="md:col-span-2">
        <label for="address_2" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('address_2') }}
        </label>
        <x-input id="address_2" class="block mt-1 w-full" type="text" name="address_2"
            placeholder="Apt #, Suite #, etc." :value="old('address_2', $appointment->address_2 ?? '')" />
        <x-input-error for="address_2" class="mt-2" />
    </div>

    {{-- City --}}
    <div>
        <label for="city" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('city') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="city" class="input-field block mt-1 w-full" type="text" name="city"
            :value="old('city', $appointment->city ?? '')" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="city"></span>
        <x-input-error for="city" class="mt-2" />
    </div>

    {{-- State --}}
    <div>
        <label for="state" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('state') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="state" class="input-field block mt-1 w-full" type="text" name="state"
            :value="old('state', $appointment->state ?? '')" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="state"></span>
        <x-input-error for="state" class="mt-2" />
    </div>

    {{-- Zipcode --}}
    <div>
        <label for="zipcode" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('zip_code') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="zipcode" class="input-field block mt-1 w-full" type="text" name="zipcode"
            :value="old('zipcode', $appointment->zipcode ?? '')" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="zipcode"></span>
        <x-input-error for="zipcode" class="mt-2" />
    </div>

    {{-- Country --}}
    <div>
        <label for="country" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('country') }} <span class="text-red-500">*</span>
        </label>
        <x-input id="country" class="input-field block mt-1 w-full" type="text" name="country"
            :value="old('country', $appointment->country ?? 'USA')" />
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="country"></span>
        <x-input-error for="country" class="mt-2" />
    </div>

    {{-- Inspection Date --}}
    <div>
        <label for="inspection_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('inspection_date') }}
        </label>
        <x-input id="inspection_date" class="block mt-1 w-full" type="date" name="inspection_date"
            min="{{ date('Y-m-d') }}" :value="old(
                'inspection_date',
                isset($appointment->inspection_date) ? $appointment->inspection_date->format('Y-m-d') : '',
            )" />
        <x-input-error for="inspection_date" class="mt-2" />
    </div>

    {{-- Inspection Time --}}
    <div>
        <label for="inspection_time_hour" class="block font-medium text-sm text-gray-700 dark:text-gray-300"
            id="inspection_time_label">
            {{ __('inspection_time') }}
        </label>
        <div class="flex mt-1 space-x-2">
            <select id="inspection_time_hour" name="inspection_time_hour"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Hour</option>
                @for ($hour = 9; $hour <= 17; $hour++)
                    <option value="{{ sprintf('%02d', $hour) }}"
                        {{ old('inspection_time_hour', isset($appointment->inspection_time) ? $appointment->inspection_time->format('H') : '') == sprintf('%02d', $hour) ? 'selected' : '' }}>
                        {{ sprintf('%02d', $hour) }}
                    </option>
                @endfor
            </select>
            <span class="self-center">:</span>
            <select id="inspection_time_minute" name="inspection_time_minute"
                class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="">Min</option>
                <option value="00"
                    {{ old('inspection_time_minute', isset($appointment->inspection_time) ? $appointment->inspection_time->format('i') : '') == '00' ? 'selected' : '' }}>
                    00</option>
                <option value="30"
                    {{ old('inspection_time_minute', isset($appointment->inspection_time) ? $appointment->inspection_time->format('i') : '') == '30' ? 'selected' : '' }}>
                    30</option>
            </select>
            <!-- Hidden input to store the combined time value -->
            <input type="hidden" id="inspection_time" name="inspection_time"
                value="{{ old('inspection_time', isset($appointment->inspection_time) ? $appointment->inspection_time->format('H:i') : '') }}">
        </div>
        <x-input-error for="inspection_time" class="mt-2" />
        <span id="time_required_message" class="hidden text-red-500 text-xs mt-1">Time is required when date is
            selected</span>
        <span class="text-xs text-gray-500 mt-1">Available times: 9:00 AM - 17:00 PM</span>
    </div>

    {{-- Inspection Status --}}
    <div>
        <label for="inspection_status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('inspection_status') }} <span class="text-red-500">*</span>
        </label>
        <select id="inspection_status" name="inspection_status"
            class="input-field border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="">Select Status</option>
            <option value="Confirmed"
                {{ old('inspection_status', $appointment->inspection_status ?? '') == 'Confirmed' ? 'selected' : '' }}>
                Confirmed</option>
            <option value="Completed"
                {{ old('inspection_status', $appointment->inspection_status ?? '') == 'Completed' ? 'selected' : '' }}>
                Completed</option>
            <option value="Pending"
                {{ old('inspection_status', $appointment->inspection_status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>
                Pending</option>
            <option value="Declined"
                {{ old('inspection_status', $appointment->inspection_status ?? '') == 'Declined' ? 'selected' : '' }}>
                Declined</option>
        </select>
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="inspection_status"></span>
        <x-input-error for="inspection_status" class="mt-2" />
    </div>

    {{-- Status Lead --}}
    <div>
        <label for="status_lead" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('lead_status') }} <span class="text-red-500">*</span>
        </label>
        <select id="status_lead" name="status_lead"
            class="input-field border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="">Select Lead Status</option>
            <option value="New"
                {{ old('status_lead', $appointment->status_lead ?? 'New') == 'New' ? 'selected' : '' }}>
                New</option>
            <option value="Called"
                {{ old('status_lead', $appointment->status_lead ?? '') == 'Called' ? 'selected' : '' }}>
                Called</option>
            <option value="Pending"
                {{ old('status_lead', $appointment->status_lead ?? '') == 'Pending' ? 'selected' : '' }}>
                Pending</option>
            <option value="Declined"
                {{ old('status_lead', $appointment->status_lead ?? '') == 'Declined' ? 'selected' : '' }}>
                Declined</option>
        </select>
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="status_lead"></span>
        <x-input-error for="status_lead" class="mt-2" />
    </div>

    {{-- Lead Source --}}
    <div>
        <label for="lead_source" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('lead_source') }} <span class="text-red-500">*</span>
        </label>
        <select id="lead_source" name="lead_source"
            class="input-field border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
            <option value="">Select Lead Source</option>
            <option value="Website"
                {{ old('lead_source', $appointment->lead_source ?? '') == 'Website' ? 'selected' : '' }}>
                Website</option>
            <option value="Facebook Ads"
                {{ old('lead_source', $appointment->lead_source ?? '') == 'Facebook Ads' ? 'selected' : '' }}>
                Facebook Ads</option>
            <option value="Reference"
                {{ old('lead_source', $appointment->lead_source ?? '') == 'Reference' ? 'selected' : '' }}>
                Reference</option>
            <option value="Retell AI"
                {{ old('lead_source', $appointment->lead_source ?? '') == 'Retell AI' ? 'selected' : '' }}>
                Retell AI</option>
        </select>
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="lead_source"></span>
        <x-input-error for="lead_source" class="mt-2" />
    </div>

    {{-- Owner --}}
    <div>
        <label for="owner" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('owner') }}
        </label>
        <x-input id="owner" class="block mt-1 w-full capitalize-first" type="text" name="owner"
            :value="old('owner', $appointment->owner ?? '')" />
        <x-input-error for="owner" class="mt-2" />
    </div>

    {{-- Notes --}}
    <div class="md:col-span-2 mb-6">
        <label for="notes" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
            {{ __('notes') }}
        </label>
        <textarea id="notes" name="notes" rows="4"
            class="capitalize-first border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('notes', $appointment->notes ?? '') }}</textarea>
        <x-input-error for="notes" class="mt-2" />
    </div>
</div>

{{-- Damage Detail --}}
<div class="md:col-span-2 mb-6">
    <label for="damage_detail" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
        {{ __('damage_detail') }}
    </label>
    <textarea id="damage_detail" name="damage_detail" rows="4"
        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('damage_detail', $appointment->damage_detail ?? '') }}</textarea>
    <x-input-error for="damage_detail" class="mt-2" />
</div>

{{-- Additional Note --}}
<div class="md:col-span-2 mb-6">
    <label for="additional_note" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
        {{ __('additional_note') }}
    </label>
    <textarea id="additional_note" name="additional_note" rows="4"
        class="capitalize-first border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('additional_note', $appointment->additional_note ?? '') }}</textarea>
    <x-input-error for="additional_note" class="mt-2" />
</div>

{{-- Address Modal --}}
<div id="address-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('complete_address_details') }}
                </h3>
                <button id="close-address-modal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="space-y-3">
                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                    <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ __('formatted_address') }}</h4>
                    <p id="modal-formatted-address" class="text-gray-900 dark:text-gray-100 font-medium">-</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('city') }}</label>
                        <p id="modal-city" class="text-gray-900 dark:text-gray-100">-</p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('state') }}</label>
                        <p id="modal-state" class="text-gray-900 dark:text-gray-100">-</p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('zip_code') }}</label>
                        <p id="modal-zipcode" class="text-gray-900 dark:text-gray-100 font-bold text-lg">-</p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('country') }}</label>
                        <p id="modal-country" class="text-gray-900 dark:text-gray-100">-</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('latitude') }}</label>
                        <p id="modal-latitude" class="text-gray-600 dark:text-gray-400 text-sm font-mono">-</p>
                    </div>
                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('longitude') }}</label>
                        <p id="modal-longitude" class="text-gray-600 dark:text-gray-400 text-sm font-mono">-</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button id="copy-complete-address"
                    class="px-4 py-2 bg-indigo-500 text-white text-sm font-medium rounded-md hover:bg-indigo-600">
                    {{ __('copy_complete_address') }}
                </button>
                <button id="close-modal-btn"
                    class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-400">
                    {{ __('close') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Checkboxes section transformed to radio buttons for insurance_property --}}
<div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 my-10 py-5">
    {{-- Insurance Property as Checkbox --}}
    <div class="block">
        <label for="insurance_property" class="flex items-center">
            <x-checkbox id="insurance_property" name="insurance_property" :checked="old('insurance_property', $appointment->insurance_property ?? false)" value="1"
                class="checkbox-field" />
            <span
                class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('property_insurance') }}</span>
            <span class="text-red-500 ml-1">*</span>
        </label>
        <span class="error-message text-xs text-red-500 mt-1 block h-4" data-field="insurance_property"></span>
    </div>

    {{-- Other checkboxes --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="block">
            <label for="sms_consent" class="flex items-center">
                <x-checkbox id="sms_consent" name="sms_consent" :checked="old('sms_consent', $appointment->sms_consent ?? false)" />
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('sms_consent') }}</span>
            </label>
        </div>
        <div class="block">
            <label for="intent_to_claim" class="flex items-center">
                <x-checkbox id="intent_to_claim" name="intent_to_claim" :checked="old('intent_to_claim', $appointment->intent_to_claim ?? false)" />
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('intent_to_claim') }}</span>
            </label>
        </div>
    </div>
</div>
</div>

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places"
        defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global variables for map functionality
            let appointmentMap;
            let appointmentMarker;
            let autocomplete;

            // Initialize map if Google Maps API is loaded
            initAppointmentMap();

            // Handle inspection date and time relationship
            const inspectionDateField = document.getElementById('inspection_date');
            const inspectionTimeField = document.getElementById('inspection_time');
            const inspectionTimeHourField = document.getElementById('inspection_time_hour');
            const inspectionTimeMinuteField = document.getElementById('inspection_time_minute');
            const inspectionTimeLabel = document.getElementById('inspection_time_label');
            const timeRequiredMessage = document.getElementById('time_required_message');

            // Function to update the hidden inspection_time field with the combined hour and minute
            function updateHiddenTimeField() {
                const hour = inspectionTimeHourField.value;
                const minute = inspectionTimeMinuteField.value;

                if (hour && minute) {
                    inspectionTimeField.value = `${hour}:${minute}`;
                } else {
                    inspectionTimeField.value = '';
                }
            }

            // Add event listeners to the hour and minute selects
            if (inspectionTimeHourField && inspectionTimeMinuteField) {
                inspectionTimeHourField.addEventListener('change', updateHiddenTimeField);
                inspectionTimeMinuteField.addEventListener('change', updateHiddenTimeField);
            }

            // Function to manage time fields based on date selection:
            // - Enables/disables time fields based on date presence
            // - Makes time fields required when date is selected
            // - Resets time values when date is cleared
            function updateTimeFieldRequirement() {
                if (inspectionDateField.value) {
                    // If date is set, enable and make time required
                    inspectionTimeHourField.removeAttribute('disabled');
                    inspectionTimeMinuteField.removeAttribute('disabled');
                    inspectionTimeHourField.setAttribute('required', 'required');
                    inspectionTimeMinuteField.setAttribute('required', 'required');

                    // Add visual indicator to the label (red asterisk)
                    if (!inspectionTimeLabel.innerHTML.includes('*')) {
                        inspectionTimeLabel.innerHTML += ' <span class="text-red-500">*</span>';
                    }

                    // Show message if time is empty
                    if (!inspectionTimeHourField.value || !inspectionTimeMinuteField.value) {
                        timeRequiredMessage.classList.remove('hidden');
                        if (!inspectionTimeHourField.value) {
                            inspectionTimeHourField.classList.add('border-red-300');
                            inspectionTimeHourField.classList.remove('border-green-500');
                        }
                        if (!inspectionTimeMinuteField.value) {
                            inspectionTimeMinuteField.classList.add('border-red-300');
                            inspectionTimeMinuteField.classList.remove('border-green-500');
                        }
                    } else {
                        timeRequiredMessage.classList.add('hidden');
                        inspectionTimeHourField.classList.remove('border-red-300');
                        inspectionTimeHourField.classList.add('border-green-500');
                        inspectionTimeMinuteField.classList.remove('border-red-300');
                        inspectionTimeMinuteField.classList.add('border-green-500');

                        // Update the hidden field
                        updateHiddenTimeField();
                    }
                } else {
                    // If date is not set, disable and reset time fields
                    inspectionTimeHourField.setAttribute('disabled', 'disabled');
                    inspectionTimeMinuteField.setAttribute('disabled', 'disabled');
                    inspectionTimeHourField.removeAttribute('required');
                    inspectionTimeMinuteField.removeAttribute('required');

                    // Reset time values
                    inspectionTimeHourField.value = '';
                    inspectionTimeMinuteField.value = '';
                    inspectionTimeField.value = '';

                    // Reset styles
                    inspectionTimeHourField.classList.remove('border-red-300');
                    inspectionTimeHourField.classList.remove('border-green-500');
                    inspectionTimeMinuteField.classList.remove('border-red-300');
                    inspectionTimeMinuteField.classList.remove('border-green-500');

                    // Remove visual indicator from label
                    inspectionTimeLabel.innerHTML = inspectionTimeLabel.innerHTML.replace(
                        ' <span class="text-red-500">*</span>', '');

                    // Hide message
                    timeRequiredMessage.classList.add('hidden');
                }
            }

            // Add event listeners
            if (inspectionDateField) {
                inspectionDateField.addEventListener('change', updateTimeFieldRequirement);
                inspectionDateField.addEventListener('input', updateTimeFieldRequirement);
            }

            // Add handlers for the time fields
            if (inspectionTimeHourField && inspectionTimeMinuteField) {
                // Hour field change handler
                inspectionTimeHourField.addEventListener('change', function() {
                    if (inspectionDateField.value) {
                        if (!this.value) {
                            this.classList.add('border-red-300');
                            this.classList.remove('border-green-500');
                        } else {
                            this.classList.remove('border-red-300');
                            this.classList.add('border-green-500');

                            // If minute is also selected, hide the error
                            if (inspectionTimeMinuteField.value) {
                                timeRequiredMessage.classList.add('hidden');
                            }
                        }

                        // Update the hidden field
                        updateHiddenTimeField();
                    }
                });

                // Minute field change handler
                inspectionTimeMinuteField.addEventListener('change', function() {
                    if (inspectionDateField.value) {
                        if (!this.value) {
                            this.classList.add('border-red-300');
                            this.classList.remove('border-green-500');
                        } else {
                            this.classList.remove('border-red-300');
                            this.classList.add('border-green-500');

                            // If hour is also selected, hide the error
                            if (inspectionTimeHourField.value) {
                                timeRequiredMessage.classList.add('hidden');
                            }
                        }

                        // Update the hidden field
                        updateHiddenTimeField();
                    }
                });
            }

            // Validate both fields before form submission
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (inspectionDateField && inspectionTimeField &&
                        inspectionDateField.value && inspectionTimeField.value) {
                        if (!inspectionTimeHourField.value || !inspectionTimeMinuteField.value) {
                            e.preventDefault();
                            timeRequiredMessage.classList.remove('hidden');

                            if (!inspectionTimeHourField.value) {
                                inspectionTimeHourField.focus();
                            } else if (!inspectionTimeMinuteField.value) {
                                inspectionTimeMinuteField.focus();
                            }

                            // Scroll to the time field
                            inspectionTimeLabel.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        } else if (inspectionDateField.value) {
                            // Ensure hidden field is updated before submission
                            updateHiddenTimeField();
                        }
                    }
                });
            });

            // Run initial checks
            if (inspectionDateField && inspectionTimeField) {
                updateTimeFieldRequirement();
                updateStatusBasedOnDateTime(); // Run this function on page load as well
            }

            // Set minimum date for inspection date field to today
            if (inspectionDateField) {
                // Get today's date in YYYY-MM-DD format
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                const todayFormatted = `${year}-${month}-${day}`;

                // Set the min attribute
                inspectionDateField.setAttribute('min', todayFormatted);

                // Validate the current value
                inspectionDateField.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const todayDate = new Date(todayFormatted);

                    // Reset time portion for accurate comparison
                    selectedDate.setHours(0, 0, 0, 0);
                    todayDate.setHours(0, 0, 0, 0);

                    if (selectedDate < todayDate) {
                        alert('Please select today or a future date for the inspection.');
                        this.value = todayFormatted;
                    }
                });

                // Check current value on page load
                if (inspectionDateField.value) {
                    const selectedDate = new Date(inspectionDateField.value);
                    const todayDate = new Date(todayFormatted);

                    // Reset time portion for accurate comparison
                    selectedDate.setHours(0, 0, 0, 0);
                    todayDate.setHours(0, 0, 0, 0);

                    if (selectedDate < todayDate) {
                        inspectionDateField.value = todayFormatted;
                    }
                }
            }

            // Formatting for first_name and last_name fields
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const ownerInput = document.getElementById('owner');

            // Function to format name fields
            function formatNameField(input) {
                // Allow letters, spaces, apostrophes, and hyphens
                input.value = input.value.replace(/[^A-Za-z\s'\-]/g, '');
            }

            if (firstNameInput) {
                firstNameInput.addEventListener('input', function() {
                    formatNameField(this);
                });
                firstNameInput.addEventListener('blur', function() {
                    formatNameField(this);
                });
            }

            if (lastNameInput) {
                lastNameInput.addEventListener('input', function() {
                    formatNameField(this);
                });
                lastNameInput.addEventListener('blur', function() {
                    formatNameField(this);
                });
            }

            // Add formatting for owner field
            if (ownerInput) {
                ownerInput.addEventListener('input', function() {
                    // Capitaliza cada palabra, no solo la primera
                    this.value = this.value.replace(/\b\w/g, function(l) {
                        return l.toUpperCase();
                    });
                });
                ownerInput.addEventListener('blur', function() {
                    // Capitaliza cada palabra al perder el foco
                    this.value = this.value.replace(/\b\w/g, function(l) {
                        return l.toUpperCase();
                    });
                });
            }

            // Format Notes and Additional Notes fields to capitalize first letter
            const notesFields = document.querySelectorAll('.capitalize-first');
            notesFields.forEach(function(field) {
                field.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
                    }
                });
            });

            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(event) {
                    const isBackspace = event?.inputType === 'deleteContentBackward';
                    let value = this.value.replace(/\D/g, '');

                    value = value.substring(0, 10);

                    let formattedValue = '';
                    if (value.length === 0) {
                        formattedValue = '';
                    } else if (value.length <= 3) {
                        formattedValue = `(${value}`;
                    } else if (value.length <= 6) {
                        formattedValue = `(${value.substring(0, 3)}) ${value.substring(3)}`;
                    } else {
                        formattedValue =
                            `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
                    }
                    this.value = formattedValue;
                });
            }

            // Function to update inspection status and lead status based on date and time selection
            function updateStatusBasedOnDateTime() {
                // Check if both date and time are selected
                if (inspectionDateField && inspectionTimeField &&
                    inspectionDateField.value && inspectionTimeField.value) {

                    // Get the status fields
                    const inspectionStatusField = document.getElementById('inspection_status');
                    const statusLeadField = document.getElementById('status_lead');

                    if (inspectionStatusField) {
                        // Store the current value
                        const currentValue = inspectionStatusField.value;

                        // We'll allow only Confirmed and Completed options
                        const allowedStatuses = ['Confirmed', 'Completed'];

                        // If current value is not in allowed statuses, default to Confirmed
                        if (!allowedStatuses.includes(currentValue)) {
                            inspectionStatusField.value = 'Confirmed';
                        }

                        // Enable/disable options based on allowed statuses
                        Array.from(inspectionStatusField.options).forEach(option => {
                            if (option.value && !allowedStatuses.includes(option.value)) {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });
                    }

                    // Set status_lead to Called and limit to only this option
                    if (statusLeadField) {
                        // Set value to Called
                        statusLeadField.value = 'Called';

                        // Only allow "Called" option
                        Array.from(statusLeadField.options).forEach(option => {
                            if (option.value && option.value !== 'Called') {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });
                    }
                } else {
                    // If date or time is not selected, set inspection_status to Pending by default
                    // and only allow Pending and Declined options
                    const inspectionStatusField = document.getElementById('inspection_status');
                    const statusLeadField = document.getElementById('status_lead');

                    if (inspectionStatusField) {
                        // Default to Pending if not already set to Declined
                        if (inspectionStatusField.value !== 'Declined') {
                            inspectionStatusField.value = 'Pending';
                        }

                        // Only allow Pending and Declined options
                        const allowedStatuses = ['Pending', 'Declined'];
                        Array.from(inspectionStatusField.options).forEach(option => {
                            if (option.value && !allowedStatuses.includes(option.value)) {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });
                    }

                    // For status_lead, if inspection_status is not Declined, allow New and Pending
                    if (statusLeadField && inspectionStatusField) {
                        if (inspectionStatusField.value !== 'Declined') {
                            // Default to New if not already set to Pending
                            if (statusLeadField.value !== 'Pending') {
                                statusLeadField.value = 'New';
                            }

                            // Only allow New and Pending options
                            const allowedLeadStatuses = ['New', 'Pending'];
                            Array.from(statusLeadField.options).forEach(option => {
                                if (option.value && !allowedLeadStatuses.includes(option.value)) {
                                    option.disabled = true;
                                    option.style.display = 'none';
                                } else {
                                    option.disabled = false;
                                    option.style.display = '';
                                }
                            });
                        }
                    }
                }
            }

            // Add the function call to the date and time change events
            if (inspectionDateField) {
                inspectionDateField.addEventListener('change', function() {
                    updateTimeFieldRequirement();
                    updateStatusBasedOnDateTime();
                });
            }

            // Add the function call to the hour and minute change events
            if (inspectionTimeHourField && inspectionTimeMinuteField) {
                inspectionTimeHourField.addEventListener('change', function() {
                    updateHiddenTimeField();
                    updateStatusBasedOnDateTime();
                });

                inspectionTimeMinuteField.addEventListener('change', function() {
                    updateHiddenTimeField();
                    updateStatusBasedOnDateTime();
                });
            }

            // Function to update lead status options based on inspection status
            function updateLeadStatusBasedOnInspectionStatus() {
                const inspectionStatusField = document.getElementById('inspection_status');
                const statusLeadField = document.getElementById('status_lead');

                if (inspectionStatusField && statusLeadField) {
                    // Check if inspection status is selected
                    if (!inspectionStatusField.value) {
                        // Disable lead status until inspection status is selected
                        statusLeadField.disabled = true;
                        statusLeadField.value = ''; // Clear selection
                        return;
                    } else {
                        // Enable lead status field
                        statusLeadField.disabled = false;
                    }

                    // Get current values
                    const inspectionStatus = inspectionStatusField.value;

                    // If inspection status is Declined, set lead status to Declined
                    if (inspectionStatus === 'Declined') {
                        statusLeadField.value = 'Declined';

                        // Only allow Declined option
                        Array.from(statusLeadField.options).forEach(option => {
                            if (option.value && option.value !== 'Declined') {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });
                    }
                    // If inspection status is Confirmed or Completed, limit lead status to Called
                    else if (inspectionStatus === 'Confirmed' || inspectionStatus === 'Completed') {
                        statusLeadField.value = 'Called';

                        // Only allow Called option
                        Array.from(statusLeadField.options).forEach(option => {
                            if (option.value && option.value !== 'Called') {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });
                    }
                    // If inspection status is Pending, limit to New and Pending
                    else if (inspectionStatus === 'Pending') {
                        if (statusLeadField.value !== 'New' && statusLeadField.value !== 'Pending') {
                            statusLeadField.value = 'New';
                        }

                        // Only allow New and Pending options
                        const allowedStatuses = ['New', 'Pending'];
                        Array.from(statusLeadField.options).forEach(option => {
                            if (option.value && !allowedStatuses.includes(option.value)) {
                                option.disabled = true;
                                option.style.display = 'none';
                            } else {
                                option.disabled = false;
                                option.style.display = '';
                            }
                        });
                    }
                }
            }

            // Add an event listener to the inspection status dropdown
            const inspectionStatusField = document.getElementById('inspection_status');
            if (inspectionStatusField) {
                inspectionStatusField.addEventListener('change', updateLeadStatusBasedOnInspectionStatus);

                // Clear inspection status on page load to force selection
                if (!inspectionStatusField.value) {
                    // Default to Pending if empty
                    inspectionStatusField.value = 'Pending';
                }
            }

            // Disable lead status field initially if inspection status is not selected
            const statusLeadField = document.getElementById('status_lead');
            if (statusLeadField && inspectionStatusField) {
                if (!inspectionStatusField.value) {
                    statusLeadField.disabled = true;
                }
            }

            // Run initial status checks
            if (inspectionDateField && inspectionTimeField) {
                updateTimeFieldRequirement();
                updateStatusBasedOnDateTime();
            }
            if (inspectionStatusField) {
                updateLeadStatusBasedOnInspectionStatus();
            }

            // Set the time fields disabled by default when the page loads
            // Note: This initialization is redundant with updateTimeFieldRequirement,
            // but ensures correct initial state if the function doesn't run
            if (inspectionTimeHourField && inspectionTimeMinuteField && inspectionDateField) {
                // Only disable if no date is selected
                if (!inspectionDateField.value) {
                    inspectionTimeHourField.setAttribute('disabled', 'disabled');
                    inspectionTimeMinuteField.setAttribute('disabled', 'disabled');
                }
            }

            // Copy to clipboard utility
            function copyToClipboard(text, successMessage) {
                navigator.clipboard.writeText(text).then(function() {
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className =
                        'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                    toast.textContent = successMessage;
                    document.body.appendChild(toast);

                    setTimeout(() => {
                        document.body.removeChild(toast);
                    }, 3000);
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    alert('{{ __('copy_failed') }}');
                });
            }

            // ============= VALIDATION SYSTEM =============
            // Setup appointment form validation system similar to Facebook Lead Modal
            setupAppointmentValidation();
        });

        // Setup real-time validation system
        function setupAppointmentValidation() {
            const form = document.querySelector('form');
            if (!form || form.dataset.validationInitialized === 'true') return;

            console.log('Setting up appointment form validation...');

            const submitButton = document.getElementById('submit-button');
            const allInputs = form.querySelectorAll('.input-field, .checkbox-field');
            const requiredFields = ['first_name', 'last_name', 'phone', 'email', 'address_map_input', 'city', 'state',
                'zipcode', 'country', 'inspection_status', 'status_lead', 'lead_source', 'insurance_property'
            ];
            const appointmentUuid = '{{ $appointment->uuid ?? '' }}'; // For edit mode
            let validationTimeouts = {};

            // Helper Functions
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            function clearFieldError(fieldElement) {
                const fieldName = fieldElement.name;
                const errorSpan = form.querySelector(`.error-message[data-field="${fieldName}"]`);
                if (errorSpan) errorSpan.textContent = '';
                fieldElement.classList.remove('border-red-500');
                checkFormValidity();
            }

            function clearAllErrors() {
                document.querySelectorAll('.error-message').forEach(span => span.textContent = '');
                allInputs.forEach(input => input.classList.remove('border-red-500'));
                checkFormValidity();
            }

            function checkFormValidity() {
                let allRequiredFilled = true;
                let hasVisibleErrors = false;

                // Check required fields
                requiredFields.forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        let value = '';
                        if (field.type === 'checkbox') {
                            value = field.checked ? '1' : '';
                        } else {
                            value = field.value ? field.value.trim() : '';
                        }

                        if (!value) {
                            allRequiredFilled = false;
                        }
                    }
                });

                // Check for visible errors
                hasVisibleErrors = Array.from(form.querySelectorAll('.error-message'))
                    .some(span => span.textContent.trim() !== '');

                // Enable button only if all required fields are filled AND there are no errors
                if (submitButton) {
                    submitButton.disabled = !allRequiredFilled || hasVisibleErrors;
                }
            }

            function validateField(fieldElement) {
                const fieldName = fieldElement.name;
                let fieldValue = '';

                if (fieldElement.type === 'checkbox') {
                    fieldValue = fieldElement.checked ? '1' : '0';
                } else {
                    fieldValue = fieldElement.value;
                }

                // Clear previous timeout for this field
                if (validationTimeouts[fieldName]) {
                    clearTimeout(validationTimeouts[fieldName]);
                }

                const validateUrl = '{{ route('appointments.validate-field') }}';
                const csrfToken = document.querySelector('input[name="_token"]')?.value;

                const requestData = {
                    fieldName: fieldName,
                    fieldValue: fieldValue
                };

                // Add exclude_uuid for edit mode
                if (appointmentUuid) {
                    requestData.exclude_uuid = appointmentUuid;
                }

                fetch(validateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(requestData)
                    })
                    .then(response => {
                        if (!response.ok && response.status !== 422) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        const errorSpan = form.querySelector(`.error-message[data-field="${fieldName}"]`);
                        if (errorSpan) {
                            if (!data.valid && data.errors?.[0]) {
                                errorSpan.textContent = data.errors[0];
                                fieldElement.classList.add('border-red-500');
                            } else {
                                errorSpan.textContent = '';
                                fieldElement.classList.remove('border-red-500');
                            }
                        }
                        checkFormValidity();
                    })
                    .catch(error => {
                        console.error('Validation request failed:', error);
                        checkFormValidity();
                    });
            }

            // Debounced validation
            const debouncedValidateField = debounce(validateField, 500);

            // Name formatting functions
            function formatName(inputElement) {
                const cursorPosition = inputElement.selectionStart;
                let value = inputElement.value;

                if (typeof value === 'string' && value.length > 0) {
                    if (value.length > 50) {
                        value = value.substring(0, 50);
                    }

                    const endsWithSpace = value.endsWith(' ');
                    value = value.replace(/\s+/g, ' ');

                    let parts = value.trim().split(' ').filter(part => part.length > 0);
                    parts = parts.map(part => {
                        return part.charAt(0).toUpperCase() + part.slice(1).toLowerCase();
                    });

                    let formattedValue = parts.join(' ');
                    if (endsWithSpace && formattedValue.length < 50) {
                        formattedValue += ' ';
                    }

                    inputElement.value = formattedValue;

                    const newCursorPosition = Math.min(cursorPosition, formattedValue.length);
                    inputElement.setSelectionRange(newCursorPosition, newCursorPosition);
                }
            }

            function formatPhoneInput(inputElement, event) {
                const isBackspace = event?.inputType === 'deleteContentBackward';
                let value = inputElement.value.replace(/\D/g, '');

                value = value.substring(0, 10);

                let formattedValue = '';
                if (value.length === 0) {
                    formattedValue = '';
                } else if (value.length <= 3) {
                    formattedValue = `(${value}`;
                } else if (value.length <= 6) {
                    formattedValue = `(${value.substring(0, 3)}) ${value.substring(3)}`;
                } else {
                    formattedValue = `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6, 10)}`;
                }
                inputElement.value = formattedValue;
            }

            // Setup event listeners for inputs
            allInputs.forEach(input => {
                if (input.name === 'first_name' || input.name === 'last_name') {
                    input.addEventListener('input', (event) => {
                        formatName(event.target);
                        debouncedValidateField(event.target);
                    });
                    input.addEventListener('blur', (event) => {
                        formatName(event.target);
                        validateField(event.target);
                    });
                } else if (input.name === 'phone') {
                    input.addEventListener('input', (event) => {
                        formatPhoneInput(input, event);
                        debouncedValidateField(input);
                    });
                    input.addEventListener('blur', (event) => {
                        validateField(input);
                    });
                } else if (input.type === 'checkbox') {
                    input.addEventListener('change', (event) => {
                        validateField(event.target);
                    });
                } else {
                    input.addEventListener('input', (event) => {
                        debouncedValidateField(event.target);
                    });
                    input.addEventListener('blur', (event) => {
                        validateField(event.target);
                    });
                }
            });

            // Initial form validity check
            checkFormValidity();

            // Mark as initialized
            form.dataset.validationInitialized = 'true';
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Map height */
        #location-map {
            min-height: 200px;
        }

        /* Capitalize only first letter */
        .capitalize-first::first-letter {
            text-transform: uppercase;
        }

        /* Validation Error Styling - Similar to Facebook Lead Modal */
        .border-red-500 {
            border-color: #f56565 !important;
        }

        .error-message {
            min-height: 1rem;
            font-size: 0.75rem;
            line-height: 1rem;
        }

        /* Input field focus states */
        .input-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 1px #6366f1;
        }

        .input-field.border-red-500:focus {
            border-color: #f56565 !important;
            box-shadow: 0 0 0 1px #f56565 !important;
        }

        /* Checkbox field styling */
        .checkbox-field {
            accent-color: #6366f1;
        }

        .checkbox-field.border-red-500 {
            outline: 2px solid #f56565;
            outline-offset: 2px;
        }

        /* Submit button disabled state */
        #submit-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Success states */
        .border-green-500 {
            border-color: #10b981 !important;
        }
    </style>
@endpush
