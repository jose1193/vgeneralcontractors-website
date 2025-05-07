<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>🎉 Appointment Confirmed Alert! 🔔 - V General Contractors</title>
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
            color: #10b981;
            font-weight: bold;
        }

        .appointment-banner {
            background: #e6f9e9;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .appointment-icon {
            display: inline-block;
            margin-right: 5px;
            font-size: 1.2em;
            color: #10b981;
        }

        .details td {
            padding: 5px 0;
            vertical-align: top;
        }

        .details strong {
            display: inline-block;
            width: 150px;
        }

        .status-tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
            background-color: #10b981;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="https://vgeneralcontractors.com/assets/logo/logo-png.png" width="180"
                alt="Logo V General Contractors">
        </div>

        <div class="details">
            <h2 style="color: #10b981; text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 10px;">
                🎉 Appointment Confirmed Alert! 🔔
            </h2>

            <div class="appointment-banner">
                <p style="text-align: center; margin: 0;">
                    A new appointment has been <span class="highlight">confirmed</span> for
                    <strong>{{ $companyData->company_name }}</strong>!
                </p>
            </div>

            <h3 style="margin-top: 25px; margin-bottom: 15px; color: #333;">Appointment Details:</h3>
            <table style="width: 100%; margin: 0 0 20px 0; border-collapse: collapse;">
                <tr>
                    <td><span class="appointment-icon">👤</span> <strong>Client Name:</strong></td>
                    <td>{{ $appointment->first_name }} {{ $appointment->last_name }}</td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">📧</span> <strong>Email:</strong></td>
                    <td><a href="mailto:{{ $appointment->email }}">{{ $appointment->email }}</a></td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">📞</span> <strong>Phone:</strong></td>
                    <td>
                        @php
                            $phone = $appointment->phone ?? '';
                            // Remove any non-digit characters
                            $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
                            // Format the number based on length
                            if (strlen($digitsOnly) == 10) {
                                $formattedPhone =
                                    '(' .
                                    substr($digitsOnly, 0, 3) .
                                    ') ' .
                                    substr($digitsOnly, 3, 3) .
                                    '-' .
                                    substr($digitsOnly, 6);
                            } elseif (strlen($digitsOnly) == 11 && substr($digitsOnly, 0, 1) == '1') {
                                // US number with country code
                                $formattedPhone =
                                    '(' .
                                    substr($digitsOnly, 1, 3) .
                                    ') ' .
                                    substr($digitsOnly, 4, 3) .
                                    '-' .
                                    substr($digitsOnly, 7);
                            } else {
                                // Fallback to original
                                $formattedPhone = $phone;
                            }
                            echo $formattedPhone;
                        @endphp
                    </td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">📍</span> <strong>Address:</strong></td>
                    <td>
                        {{ $appointment->address }}
                        @if ($appointment->address_2)
                            <br>{{ $appointment->address_2 }}
                        @endif
                        <br>{{ $appointment->city }}, {{ $appointment->state }} {{ $appointment->zipcode }}
                        <br>{{ $appointment->country }}
                    </td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">🕒</span> <strong>Appointment Time:</strong></td>
                    <td>
                        @php
                            $inspectionDate = \Carbon\Carbon::parse($appointment->inspection_date);
                            $inspectionTime = \Carbon\Carbon::parse($appointment->inspection_time);
                            $startDateTime = $inspectionDate->setTimeFrom($inspectionTime);
                            $endDateTime = $startDateTime->copy()->addHours(2);
                            $formattedDate = $startDateTime
                                ->locale('es')
                                ->isoFormat('dddd D [de] MMMM [de] YYYY [a las] hh:mm A');
                            echo ucfirst($formattedDate);
                        @endphp
                        <br>
                        <small>Duration: 2 hours (until {{ $endDateTime->format('h:i A') }})</small>
                    </td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">⏱️</span> <strong>Duration:</strong></td>
                    <td>2 hours</td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">🛡️</span> <strong>Has Insurance?:</strong></td>
                    <td>{{ $appointment->insurance_property ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">💬</span> <strong>SMS Consent:</strong></td>
                    <td>{{ $appointment->sms_consent ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">📝</span> <strong>Message:</strong></td>
                    <td>{{ $appointment->message ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">📅</span> <strong>Registration Date:</strong></td>
                    <td>{{ $appointment->registration_date ? $appointment->registration_date->format('M d, Y g:i A T') : 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <td><span class="appointment-icon">🔍</span> <strong>Lead Source:</strong></td>
                    <td>{{ $appointment->lead_source ?: 'N/A' }}</td>
                </tr>
            </table>

            <div style="padding: 15px; border-radius: 8px; margin-top: 25px; text-align: center;">
                <p>Please prepare for this appointment!</p>
                <p>Contact the client from:
                    <strong>📞
                        @php
                            $phone = $companyData->phone ?? '';
                            // Remove any non-digit characters
                            $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
                            // Format the number based on length
                            if (strlen($digitsOnly) == 10) {
                                $formattedPhone =
                                    '(' .
                                    substr($digitsOnly, 0, 3) .
                                    ') ' .
                                    substr($digitsOnly, 3, 3) .
                                    '-' .
                                    substr($digitsOnly, 6);
                            } elseif (strlen($digitsOnly) == 11 && substr($digitsOnly, 0, 1) == '1') {
                                // US number with country code
                                $formattedPhone =
                                    '(' .
                                    substr($digitsOnly, 1, 3) .
                                    ') ' .
                                    substr($digitsOnly, 4, 3) .
                                    '-' .
                                    substr($digitsOnly, 7);
                            } else {
                                // Fallback to original
                                $formattedPhone = $phone;
                            }
                            echo $formattedPhone;
                        @endphp
                    </strong>
                </p>
            </div>
        </div>

        <div class="social-icons">
            @if ($companyData->social_media_facebook)
                <a href="{{ $companyData->social_media_facebook }}" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" width="30" alt="Facebook">
                </a>
            @endif
            @if ($companyData->social_media_instagram)
                <a href="{{ $companyData->social_media_instagram }}" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" width="30" alt="Instagram">
                </a>
            @endif
        </div>

        <div class="footer">
            <p>Business Hours:<br>
                Monday to Friday: 9:00 AM - 5:00 PM</p>
            <p style="margin-top: 10px; font-size: 12px;">© {{ date('Y') }} {{ $companyData->company_name }}. All
                rights
                reserved.</p>
            <p style="font-size: 10px; color: #999;">{{ $companyData->address ?? '' }}</p>
        </div>
    </div>
</body>

</html>
