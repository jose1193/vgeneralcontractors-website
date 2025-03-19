@props(['modalAction'])

<!-- Description Field -->
<div class="col-span-1 md:col-span-2">
    <x-text-input name="description" label="Description" model="form.description" :error="$errors->first('description')"
        placeholder="Enter a description" :required="true" maxlength="255" />
</div>

<!-- Email Field -->
<div class="col-span-1 md:col-span-1">
    <x-email-input name="email" label="Email Address" model="form.email" :error="$errors->first('email')"
        placeholder="example@domain.com" :required="true" maxlength="255" />
</div>

<!-- Phone Number Field -->
<div class="col-span-1 md:col-span-1">
    <x-phone-input name="phone" label="Phone Number" model="form.phone" :error="$errors->first('phone')" placeholder="(123) 456-7890"
        :required="false" />
</div>

<!-- Type Field -->
<div class="col-span-1 md:col-span-2">
    <x-select-input name="type" label="Type" model="form.type" :error="$errors->first('type')" :options="[
        'Appointment' => 'Appointment',
        'Info' => 'Info',
        'Collections' => 'Collections',
        'Personal' => 'Personal',
        'Work' => 'Work',
        'Business' => 'Business',
        'Other' => 'Other',
    ]" />
</div>



<!-- Alpine Script for Form Validation -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formValidation', formValidation);
    });

    document.addEventListener('DOMContentLoaded', () => {
        window.phoneFormat = phoneFormat;
    });
</script>
