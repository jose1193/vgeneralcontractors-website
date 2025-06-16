@props(['modalAction', 'usernameAvailable' => null, 'roles' => []])

<!-- First Name -->
<x-name-input name="name" label="{{ __('first_name') }}" model="form.name" :error="$errors->first('name')" />

<!-- Last Name -->
<x-name-input name="last_name" label="{{ __('last_name') }}" model="form.last_name" :error="$errors->first('last_name')" />


<!-- Email -->
<x-email-input name="email" label="{{ __('email') }}" model="form.email" :error="$errors->first('email')" />

<!-- Username -->
<x-username-input name="username" label="{{ __('username') }}" model="form.username" mode="{{ $modalAction }}"
    :error="$errors->first('username')" />

<!-- Phone -->
<x-phone-input name="phone" label="{{ __('phone') }}" model="form.phone" :error="$errors->first('phone')" />
<!-- Address -->
<x-text-input name="address" label="{{ __('address') }}" model="form.address" :error="$errors->first('address')" />

<!-- City -->
<x-text-input name="city" label="{{ __('city') }}" model="form.city" :error="$errors->first('city')" />


<!-- State field (added as per reference) -->
<x-text-input name="state" label="{{ __('state') }}" model="form.state" :error="$errors->first('state')" />


<!-- Zip Code -->
<x-text-input name="zip_code" label="{{ __('zip_code') }}" model="form.zip_code" :error="$errors->first('zip_code')" />


<!-- Country -->
<x-text-input name="country" label="{{ __('country') }}" model="form.country" :error="$errors->first('country')" />


<!-- Gender - Ahora ocupa solo una columna -->
<x-select-input name="gender" label="{{ __('gender') }}" model="form.gender" :options="[
    'male' => __('male'),
    'female' => __('female'),
    'other' => __('other'),
]" :error="$errors->first('gender')" />

<!-- Role Selection - Ahora está al lado de Gender -->
<x-select-input name="role" label="{{ __('role') }}" model="form.role" :options="$roles" :error="$errors->first('role')"
    required />

<!-- Date of birth - only shown in edit mode -->
@if ($modalAction === 'update')
    <x-date-input name="date_of_birth" label="{{ __('date_of_birth') }}" model="form.date_of_birth"
        :error="$errors->first('date_of_birth')" />
@endif

<!-- Password Reset Toggle - only in edit mode -->
@if ($modalAction === 'update')
    <x-password-reset-toggle model="form.send_password_reset" />
@endif
