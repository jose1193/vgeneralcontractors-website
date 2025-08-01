@component('mail::message')
# Test Email

This is a test email to verify that email delivery is working correctly.

![Logo](https://vgeneralcontractors.com/assets/logo/logo-png.png)

@component('mail::button', ['url' => config('app.url')])
Visit Website
@endcomponent

Thanks,<br>
{{ config('app.name') }}

@endcomponent
