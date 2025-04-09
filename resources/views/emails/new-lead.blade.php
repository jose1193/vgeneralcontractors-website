<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>🎉 New Lead Alert! 🔔 - V General Contractors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            max-width: 180px;
            height: auto;
        }

        .details {
            line-height: 1.6;
            color: #333333;
        }

        .footer {
            margin-top: 25px;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }

        .social-icons {
            margin: 20px 0;
            text-align: center;
        }

        .social-icons a {
            margin: 0 10px;
            display: inline-block;
        }

        .highlight {
            color: #28a745;
            font-weight: bold;
        }

        .lead-banner {
            background: #e6f9e9;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .lead-icon {
            display: inline-block;
            margin-right: 5px;
            font-size: 1.2em;
            color: #28a745;
        }

        .details td {
            padding: 5px 0;
            vertical-align: top;
        }

        .details strong {
            display: inline-block;
            width: 150px;
        }

        /* Adjust width as needed */
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="https://vgeneralcontractors.com/wp-content/uploads/2021/06/v-general-contractors-logo.png"
                width="180" alt="Logo V General Contractors">
        </div>

        <div class="details">
            <h2 style="color: #28a745; text-align: center; border-bottom: 2px solid #28a745; padding-bottom: 10px;">🎉
                New Lead Alert! 🔔</h2>

            <div class="lead-banner">
                <p style="text-align: center; margin: 0;">
                    You have received a <span class="highlight">new potential customer</span> inquiry!
                </p>
            </div>

            <h3 style="margin-top: 25px; margin-bottom: 15px; color: #333;">Lead Details:</h3>
            <table style="width: 100%; margin: 0 0 20px 0; border-collapse: collapse;">
                <tr>
                    <td><span class="lead-icon">👤</span> <strong>First Name:</strong></td>
                    <td>{{ $appointment->first_name }}</td>
                </tr>
                <tr>
                    <td><span class="lead-icon">👤</span> <strong>Last Name:</strong></td>
                    <td>{{ $appointment->last_name }}</td>
                </tr>
                <tr>
                    <td><span class="lead-icon">📧</span> <strong>Email:</strong></td>
                    <td><a href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a></td>
                </tr>
                <tr>
                    <td><span class="lead-icon">📞</span> <strong>Phone:</strong></td>
                    <td>{{ $appointment->phone }}</td>
                </tr>
                <tr>
                    <td><span class="lead-icon">📍</span> <strong>Address:</strong></td>
                    <td>
                        {{ $appointment->address }}<br>
                        @if ($appointment->address_2)
                            {{ $appointment->address_2 }}<br>
                        @endif
                        {{ $appointment->city }}, {{ $appointment->state }} {{ $appointment->zipcode }}<br>
                        {{ $appointment->country }}
                    </td>
                </tr>
                <tr>
                    <td><span class="lead-icon">🛡️</span> <strong>Has Insurance?:</strong></td>
                    <td>{{ $appointment->insurance_property }}</td>
                </tr>
                <tr>
                    <td><span class="lead-icon">💬</span> <strong>SMS Consent:</strong></td>
                    <td>{{ $appointment->sms_consent ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td><span class="lead-icon">📝</span> <strong>Message:</strong></td>
                    <td>{{ $appointment->message ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td><span class="lead-icon">📅</span> <strong>Submitted:</strong></td>
                    <td>{{ $appointment->registration_date ? $appointment->registration_date->format('M d, Y g:i A T') : 'N/A' }}
                    </td>
                </tr>
            </table>

            <div style="padding: 15px; border-radius: 8px; margin-top: 25px; text-align: center;">
                <p>Act quickly to connect with this potential client!</p>
            </div>
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
            <p style="margin-top: 10px; font-size: 12px;">© {{ date('Y') }} V General Contractors. All rights
                reserved.</p>
            <p style="font-size: 10px; color: #999;">{{ \App\Models\CompanyData::first()->address ?? '' }}</p>
        </div>
    </div>
</body>

</html>
