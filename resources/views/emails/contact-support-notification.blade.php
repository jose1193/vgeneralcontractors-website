<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Support Request</title>
    <style>
        /* Add styles similar to your other emails (e.g., new-lead.blade.php) */
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .header img {
            max-height: 50px;
        }

        /* Adjust as needed */
        .content h2 {
            color: #0056b3;
        }

        /* Adjust color if needed */
        .details {
            margin-top: 15px;
        }

        .details strong {
            display: inline-block;
            width: 100px;
            color: #555;
        }

        .details p {
            margin: 8px 0;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #f59e0b;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        a {
            color: #f59e0b;
            text-decoration: none;
        }

        hr {
            border: none;
            border-top: 1px solid #eee;
            margin: 20px 0;
        }

        .message-box {
            white-space: pre-wrap;
            background-color: #ffffff;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 4px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{-- Optional: You can retrieve the logo path from CompanyData if stored there --}}
            {{-- @php $company = \App\Models\CompanyData::first(); @endphp
            @if ($company && $company->signature_path)
                <img src="{{ $message->embed(storage_path('app/public/' . $company->signature_path)) }}" alt="{{ $company->company_name ?? 'Company Logo' }}">
            @else
                <h2>{{ $company->company_name ?? 'New Contact Support Request' }}</h2>
            @endif --}}
            <h2>New Contact Support Request</h2>
        </div>

        <div class="content">
            <p>A new contact support request has been submitted via the website:</p>

            <div class="details">
                <p><strong>Name:</strong> {{ $contactSupport->first_name }} {{ $contactSupport->last_name }}</p>
                <p><strong>Email:</strong> <a href="mailto:{{ $contactSupport->email }}">{{ $contactSupport->email }}</a>
                </p>
                <p><strong>Phone:</strong> <a
                        href="tel:{{ preg_replace('/[^\d]/', '', $contactSupport->phone) }}">{{ $contactSupport->phone }}</a>
                </p>
                <p><strong>SMS Consent:</strong> {{ $contactSupport->sms_consent ? 'Yes' : 'No' }}</p>
                <p><strong>Submitted:</strong> {{ $contactSupport->created_at->format('M d, Y H:i A T') }}</p>
                <hr>
                <p><strong>Message:</strong></p>
                <div class="message-box">{{ $contactSupport->message }}</div>
            </div>

            {{-- Optional: Link to admin panel --}}
            {{--
            <p style="text-align: center;">
                <a href="{{-- route('admin.contacts.show', $contactSupport->id) --}}" class="button">View Request in Admin</a>
            </p>
            --}}
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }}
                {{ \App\Models\CompanyData::first()->company_name ?? 'V General Contractors' }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
