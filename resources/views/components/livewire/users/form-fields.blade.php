@props(['modalAction', 'usernameAvailable' => null, 'roles' => []])

<!-- First Name -->
<x-name-input name="name" label="First Name" model="form.name" :error="$errors->first('name')" />

<!-- Last Name -->
<x-name-input name="last_name" label="Last Name" model="form.last_name" :error="$errors->first('last_name')" />


<!-- Email -->
<x-email-input name="email" label="Email" model="form.email" :error="$errors->first('email')" />

<!-- Username -->
<x-username-input name="username" label="Username" model="form.username" mode="{{ $modalAction }}" :error="$errors->first('username')" />

<!-- Phone -->
<x-phone-input name="phone" label="Phone" model="form.phone" :error="$errors->first('phone')" />
<!-- Address -->
<x-text-input name="address" label="Address" model="form.address" :error="$errors->first('address')" />

<!-- City -->
<x-text-input name="city" label="City" model="form.city" :error="$errors->first('city')" />


<!-- State field (added as per reference) -->
<x-text-input name="state" label="State" model="form.state" :error="$errors->first('state')" />


<!-- Zip Code -->
<x-text-input name="zip_code" label="Zip Code" model="form.zip_code" :error="$errors->first('zip_code')" />


<!-- Country -->
<x-text-input name="country" label="Country" model="form.country" :error="$errors->first('country')" />


<!-- Gender -->
<x-select-input name="gender" label="Gender" model="form.gender" :options="['male' => 'Male', 'female' => 'Female', 'other' => 'Other']" :error="$errors->first('gender')" />

<!-- Role Selection -->
<div class="col-span-1 md:col-span-2">
    <x-select-input name="role" label="Role" model="form.role" :options="$roles" :error="$errors->first('role')" />
</div>

<!-- Date of birth - only shown in edit mode -->
@if ($modalAction === 'update')
    <x-date-input name="date_of_birth" label="Date of Birth" model="form.date_of_birth" :error="$errors->first('date_of_birth')" />
@endif

<!-- Password Reset Toggle - only in edit mode -->
@if ($modalAction === 'update')
    <x-password-reset-toggle model="form.send_password_reset" />
@endif
