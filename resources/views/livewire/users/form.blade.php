<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Información Personal -->
    <x-inputs.text-input id="name" label="First Name:" wire:model="name" placeholder="Enter first name"
        x-model="form.name"
        @input="
            let words = $event.target.value.toLowerCase().split(' ');
            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            $event.target.value = words.join(' ');
            $wire.set('name', $event.target.value);
        "
        :error="$errors->first('name')" />

    <x-inputs.text-input id="last_name" label="Last Name:" wire:model="last_name" placeholder="Enter last name"
        x-model="form.last_name"
        @input="
            let words = $event.target.value.toLowerCase().split(' ');
            words = words.map(word => word.charAt(0).toUpperCase() + word.slice(1));
            $event.target.value = words.join(' ');
            $wire.set('last_name', $event.target.value);
        "
        :error="$errors->first('last_name')" />

    <!-- Username field - comportamiento condicional -->
    <div class="mb-4">
        <label for="username" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Username</label>
        <div class="relative">
            @if ($modalAction === 'store')
                <input type="text" id="username" disabled
                    placeholder="Will be automatically generated from name and lastname"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-100">
                <div class="mt-1 text-xs text-gray-500 italic">
                    Example: If name is "John Doe", username will be something like
                    "johnd123"
                </div>
            @else
                <input type="text" id="username" x-model="form.username" wire:model="username"
                    @input="$wire.set('username', $event.target.value); validateUsername($event.target.value);"
                    @blur="checkUsernameAvailability($event.target.value)"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    :class="{ 'border-red-500': errors.username }">
                <div class="mt-1 text-xs text-gray-500 italic">
                    Username must be at least 7 characters and contain at least 2 numbers
                </div>
                @error('username')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            @endif
        </div>
    </div>

    <x-inputs.email-input id="email" label="Email:" wire:model="email" placeholder="Enter email"
        x-model="form.email" @input="$wire.set('email', $event.target.value); validateEmail($event.target.value);"
        @blur="checkEmailAvailability($event.target.value)" :error="$errors->first('email')" />

    <!-- Información de Contacto -->
    <div class="mb-4">
        <label for="phone" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Phone:</label>
        <input type="tel" id="phone" x-model="form.phone" wire:model="phone"
            @input="
                const formattedPhone = formatPhone($event);
                $wire.set('phone', formattedPhone);
                form.phone = formattedPhone;
            "
            @blur="validatePhone($event.target.value); checkPhoneAvailability($event.target.value);"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            :class="{ 'border-red-500': errors.phone }" placeholder="Enter phone (XXX) XXX-XXXX">
        <div class="text-red-500 text-xs mt-1" x-show="errors.phone" x-text="errors.phone"></div>
        @error('phone')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Dirección -->
    <x-inputs.text-input id="address" label="Address:" wire:model="address" placeholder="Enter address"
        x-model="form.address" @input="$wire.set('address', $event.target.value);" :error="$errors->first('address')" />

    <x-inputs.text-input id="city" label="City:" wire:model="city" placeholder="Enter city" x-model="form.city"
        @input="$wire.set('city', $event.target.value);" :error="$errors->first('city')" />

    <x-inputs.text-input id="state" label="State:" wire:model="state" placeholder="Enter state" x-model="form.state"
        @input="$wire.set('state', $event.target.value);" :error="$errors->first('state')" />

    <x-inputs.text-input id="country" label="Country:" wire:model="country" placeholder="Enter country"
        x-model="form.country" @input="$wire.set('country', $event.target.value);" :error="$errors->first('country')" />

    <x-inputs.text-input id="zip_code" label="ZIP Code:" wire:model="zip_code" placeholder="Enter ZIP code"
        x-model="form.zip_code" @input="$wire.set('zip_code', $event.target.value);" :error="$errors->first('zip_code')" />

    <!-- Información Adicional -->
    <div class="mb-4">
        <label for="gender" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Gender:</label>
        <select x-model="form.gender" @change="$wire.set('gender', $event.target.value);" id="gender"
            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            :class="{ 'border-red-500': errors.gender }">
            <option value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
        </select>
        @error('gender')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
        @enderror
    </div>

    <!-- Date of birth - solo en edición -->
    @if ($modalAction === 'update')
        <div class="mb-4">
            <label for="date_of_birth" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                Date of Birth
            </label>
            <input type="date" id="date_of_birth" wire:model="date_of_birth"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :class="{ 'border-red-500': errors.date_of_birth }">
            @error('date_of_birth')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <!-- Password Reset Toggle - solo en edición -->
    @if ($modalAction === 'update')
        <div class="mb-4 col-span-2">
            <label for="send_password_reset" class="flex items-center cursor-pointer">
                <div class="relative">
                    <input type="checkbox" x-model="form.send_password_reset"
                        @change="$wire.set('send_password_reset', $event.target.checked)" id="send_password_reset"
                        class="sr-only">
                    <div class="block bg-gray-600 dark:bg-gray-700 w-14 h-8 rounded-full">
                    </div>
                    <div class="dot absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition transform"
                        :class="{ 'translate-x-6': form.send_password_reset }"></div>
                </div>
                <span class="ml-3 text-gray-700 dark:text-gray-300">Send password reset
                    email to user</span>
            </label>
        </div>
    @endif

    <!-- Añadir esto temporalmente para debug -->
    <div class="mb-4" x-show="modalAction === 'update'">
        <div class="text-xs text-gray-500">
            Debug - Username values:
            <ul>
                <li>Livewire: {{ $username }}</li>
                <li>Alpine: <span x-text="form.username"></span></li>
            </ul>
        </div>
    </div>
</div>
