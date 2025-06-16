@props(['modalAction', 'roles' => []])

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- First Name -->
    <x-simple-text-input name="name" label="{{ __('first_name') }}" :error="$errors->first('name')" required />

    <!-- Last Name -->
    <x-simple-text-input name="last_name" label="{{ __('last_name') }}" :error="$errors->first('last_name')" required />

    <!-- Email -->
    <x-simple-text-input name="email" label="{{ __('email') }}" type="email" :error="$errors->first('email')" required />

    <!-- Username -->
    <x-simple-username-input name="username" label="{{ __('username') }}" :mode="$modalAction" :error="$errors->first('username')" required />

    <!-- Phone -->
    <x-simple-phone-input name="phone" label="{{ __('phone') }}" :error="$errors->first('phone')" />

    <!-- Address -->
    <x-simple-text-input name="address" label="{{ __('address') }}" :error="$errors->first('address')" />

    <!-- City -->
    <x-simple-text-input name="city" label="{{ __('city') }}" :error="$errors->first('city')" />

    <!-- State -->
    <x-simple-text-input name="state" label="{{ __('state') }}" :error="$errors->first('state')" />

    <!-- Zip Code -->
    <x-simple-text-input name="zip_code" label="{{ __('zip_code') }}" :error="$errors->first('zip_code')" />

    <!-- Country -->
    <x-simple-text-input name="country" label="{{ __('country') }}" :error="$errors->first('country')" />

    <!-- Gender -->
    <x-simple-select-input name="gender" label="{{ __('gender') }}" :options="[
        'male' => __('male'),
        'female' => __('female'),
        'other' => __('other'),
    ]" :error="$errors->first('gender')"
        placeholder="Select Gender" />

    <!-- Role Selection -->
    <x-simple-select-input name="role" label="{{ __('role') }}" :options="$roles" :error="$errors->first('role')"
        placeholder="Select Role" required />

    <!-- Date of birth - only shown in edit mode -->
    @if ($modalAction === 'update')
        <x-simple-date-input name="date_of_birth" label="{{ __('date_of_birth') }}" :error="$errors->first('date_of_birth')" />
    @endif

    <!-- Password Reset Toggle - only in edit mode -->
    @if ($modalAction === 'update')
        <div class="md:col-span-2">
            <x-simple-checkbox name="send_password_reset" label="Send password reset email to user"
                :error="$errors->first('send_password_reset')" />
        </div>
    @endif
</div>
