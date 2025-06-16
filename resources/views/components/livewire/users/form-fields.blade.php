@props(['modalAction', 'usernameAvailable' => null, 'roles' => []])

<!-- First Name -->
<x-name-input name="name" label="{{ __('first_name') }}" :error="$errors->first('name')" />

<!-- Last Name -->
<x-name-input name="last_name" label="{{ __('last_name') }}" :error="$errors->first('last_name')" />

<!-- Email -->
<x-email-input name="email" label="{{ __('email') }}" :error="$errors->first('email')" />

<!-- Username -->
<x-username-input name="username" label="{{ __('username') }}" mode="{{ $modalAction }}" :error="$errors->first('username')" />

<!-- Phone -->
<x-phone-input name="phone" label="{{ __('phone') }}" :error="$errors->first('phone')" />

<!-- Address -->
<x-text-input name="address" label="{{ __('address') }}" :error="$errors->first('address')" />

<!-- City -->
<x-text-input name="city" label="{{ __('city') }}" :error="$errors->first('city')" />

<!-- State field -->
<x-text-input name="state" label="{{ __('state') }}" :error="$errors->first('state')" />

<!-- Zip Code -->
<x-text-input name="zip_code" label="{{ __('zip_code') }}" :error="$errors->first('zip_code')" />

<!-- Country -->
<x-text-input name="country" label="{{ __('country') }}" :error="$errors->first('country')" />

<!-- Gender -->
<x-select-input name="gender" label="{{ __('gender') }}" :options="[
    'male' => __('male'),
    'female' => __('female'),
    'other' => __('other'),
]" :error="$errors->first('gender')" />

<!-- Role Selection -->
<x-select-input name="role" label="{{ __('role') }}" :options="$roles" :error="$errors->first('role')" required />

<!-- Date of birth - only shown in edit mode -->
@if ($modalAction === 'update')
    <x-date-input name="date_of_birth" label="{{ __('date_of_birth') }}" :error="$errors->first('date_of_birth')" />
@endif

<!-- Password Reset Toggle - only in edit mode -->
@if ($modalAction === 'update')
    <x-password-reset-toggle />
@endif
