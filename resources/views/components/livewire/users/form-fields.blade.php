@props(['modalAction', 'usernameAvailable' => null, 'roles' => []])

<!-- First Name -->
<x-name-input name="name" label="{{ __('first_name') }}" wire:model="name" :error="$errors->first('name')" />

<!-- Last Name -->
<x-name-input name="last_name" label="{{ __('last_name') }}" wire:model="last_name" :error="$errors->first('last_name')" />


<!-- Email -->
<x-email-input name="email" label="{{ __('email') }}" wire:model="email" :error="$errors->first('email')" />

<!-- Username -->
<x-username-input name="username" label="{{ __('username') }}" wire:model="username" mode="{{ $modalAction }}"
    :error="$errors->first('username')" />

<!-- Phone -->
<x-phone-input name="phone" label="{{ __('phone') }}" wire:model="phone" :error="$errors->first('phone')" />
<!-- Address -->
<x-text-input name="address" label="{{ __('address') }}" wire:model="address" :error="$errors->first('address')" />

<!-- City -->
<x-text-input name="city" label="{{ __('city') }}" wire:model="city" :error="$errors->first('city')" />


<!-- State field (added as per reference) -->
<x-text-input name="state" label="{{ __('state') }}" wire:model="state" :error="$errors->first('state')" />


<!-- Zip Code -->
<x-text-input name="zip_code" label="{{ __('zip_code') }}" wire:model="zip_code" :error="$errors->first('zip_code')" />


<!-- Country -->
<x-text-input name="country" label="{{ __('country') }}" wire:model="country" :error="$errors->first('country')" />


<!-- Gender - Ahora ocupa solo una columna -->
<x-select-input name="gender" label="{{ __('gender') }}" wire:model="gender" :options="[
    'male' => __('male'),
    'female' => __('female'),
    'other' => __('other'),
]" :error="$errors->first('gender')" />

<!-- Role Selection - Ahora está al lado de Gender -->
<x-select-input name="role" label="{{ __('role') }}" wire:model="role" :options="$roles" :error="$errors->first('role')"
    required />

<!-- Date of birth - only shown in edit mode -->
@if ($modalAction === 'update')
    <x-date-input name="date_of_birth" label="{{ __('date_of_birth') }}" wire:model="date_of_birth"
        :error="$errors->first('date_of_birth')" />
@endif

<!-- Password Reset Toggle - only in edit mode -->
@if ($modalAction === 'update')
    <x-password-reset-toggle wire:model="send_password_reset" />
@endif
