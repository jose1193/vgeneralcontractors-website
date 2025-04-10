<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Support Request</title>
    <style>
        /* Add styles similar to your other emails (e.g., new-lead.blade.php) */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffffff;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header img {
            max-width: 180px;
            height: auto;
        }

        .content h2 {
            color: #28a745;
            text-align: center;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }

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
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
            color: #666666;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 15px;
            background-color: #28a745;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        a {
            color: #28a745;
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

        .social-icons {
            margin: 20px 0;
            text-align: center;
        }

        .social-icons a {
            margin: 0 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="https://vgeneralcontractors.com/wp-content/uploads/2021/06/v-general-contractors-logo.png"
                width="180" alt="Logo V General Contractors">
        </div>

        <div class="content">
            <h2>New Contact Support Request</h2>
            <p>A new contact support request has been submitted via the website:</p>

            <div class="details">
                <p><strong>Name:</strong> {{ $contactSupport->first_name }} {{ $contactSupport->last_name }}</p>
                <p><strong>Email:</strong> <a
                        href="mailto:{{ $contactSupport->email }}">{{ $contactSupport->email }}</a>
                </p>
                <p><strong>Phone:</strong> <a href="tel:{{ preg_replace('/[^\d]/', '', $contactSupport->phone) }}">
                        @php
                            $digits = preg_replace('/[^\d]/', '', $contactSupport->phone);
                            if (strlen($digits) === 10) {
                                echo '(' .
                                    substr($digits, 0, 3) .
                                    ') ' .
                                    substr($digits, 3, 3) .
                                    '-' .
                                    substr($digits, 6, 4);
                            } else {
                                echo $contactSupport->phone;
                            }
                        @endphp
                    </a>
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
                <a href="{{ route('admin.contacts.show', $contactSupport->id) }}" class="button">View Request in Admin</a>
            </p>
            --}}
        </div>

        <div class="social-icons">
            <a href="[URL_FACEBOOK]" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" width="30" alt="Facebook">
            </a>
            <a href="https://www.instagram.com/vgeneralcontractors/" target="_blank">
                <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" width="30" alt="Instagram">
            </a>
        </div>

        <div class="footer">
            <p>Business Hours:<br>
                Monday to Friday: 9:00 AM - 5:00 PM</p>
            <p style="margin-top: 10px; font-size: 12px;">&copy; {{ date('Y') }}
                {{ \App\Models\CompanyData::first()->company_name ?? 'V General Contractors' }}. All rights reserved.
            </p>
            <p style="font-size: 10px; color: #999;">{{ \App\Models\CompanyData::first()->address ?? '' }}</p>
        </div>
    </div>
</body>

</html>
