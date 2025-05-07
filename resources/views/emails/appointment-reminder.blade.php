<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>⏰ Recordatorio de Cita - V General Contractors</title>
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

        .highlight-blue {
            color: #3b82f6;
            font-weight: bold;
        }

        .reminder-banner {
            background: #eff6ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .appointment-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .details td {
            padding: 8px 0;
            vertical-align: top;
        }

        .details strong {
            display: inline-block;
            width: 150px;
            color: #4a5568;
        }

        .status-tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: bold;
            background-color: #3b82f6;
            color: white;
        }

        .action-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="https://vgeneralcontractors.com/assets/logo/logo-png.png" width="180"
                alt="Logo V General Contractors">
        </div>

        <div class="details">
            <h2 style="color: #3b82f6; text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 10px;">
                ⏰ Recordatorio de Cita
            </h2>

            <div class="reminder-banner">
                <p style="text-align: center; margin: 0;">
                    <strong>Le recordamos su próxima cita programada.</strong>
                </p>
            </div>

            <p style="margin: 20px 0;">Hola <strong>{{ $appointment->client_first_name }}
                    {{ $appointment->client_last_name }}</strong>,</p>

            <p>Le recordamos que tiene una cita programada con <strong>{{ $companyData->company_name }}</strong>
                para el servicio de {{ $appointment->service->name }}.</p>

            <div class="appointment-details">
                <h3 style="color: #2d3748; margin-top: 0;">Detalles de la Cita:</h3>
                <table style="width: 100%;">
                    @if ($appointment->service)
                        <tr>
                            <td><strong>Servicio:</strong></td>
                            <td>{{ $appointment->service->name }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Estado:</strong></td>
                        <td><span class="status-tag">Confirmada</span></td>
                    </tr>
                    <tr>
                        <td><strong>Fecha y hora:</strong></td>
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
                            <small>Duración: 2 horas (hasta {{ $endDateTime->format('h:i A') }})</small>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Duración:</strong></td>
                        <td>2 horas</td>
                    </tr>
                    <tr>
                        <td><strong>Dirección:</strong></td>
                        <td>
                            {{ $appointment->address }}
                            @if ($appointment->address_2)
                                <br>{{ $appointment->address_2 }}
                            @endif
                            <br>{{ $appointment->city }}, {{ $appointment->state }} {{ $appointment->zipcode }}
                            <br>{{ $appointment->country }}
                        </td>
                    </tr>
                </table>
            </div>

            <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #2d3748;">Importante:</h4>
                <ul style="margin: 0; padding-left: 20px;">
                    <li>Por favor, llegue 5 minutos antes de su cita</li>
                    <li>Si necesita reprogramar o cancelar, contáctenos lo antes posible</li>
                    <li>Por favor, tenga a mano su <strong>póliza de cobertura de seguro</strong> de su propiedad el día
                        de la inspección</li>
                </ul>
            </div>

            @if ($companyData->website)
                <div style="text-align: center; margin: 20px 0;">
                    <p>Si necesita reprogramar su cita, por favor contáctenos por teléfono o email.</p>
                </div>
            @endif

            <p>Si tiene alguna pregunta o necesita asistencia adicional, no dude en contactarnos:</p>

            <div
                style="background-color: #f8f9fa; border-radius: 8px; padding: 10px; margin: 10px 0; text-align: center; border: 1px solid #e9ecef;">
                @if ($companyData->phone || $companyData->email)
                    <div style="font-size: 14px; display: flex; flex-direction: column; align-items: center;">
                        @if ($companyData->phone)
                            <div style="margin-bottom: 5px; display: flex; align-items: center; gap: 5px;">
                                <span>📞</span>
                                <strong style="margin-right: 5px;">Teléfono:</strong>
                                <span>
                                    @php
                                        $phone = $companyData->phone ?? '';
                                        $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
                                        if (strlen($digitsOnly) == 10) {
                                            $formattedPhone =
                                                '(' .
                                                substr($digitsOnly, 0, 3) .
                                                ') ' .
                                                substr($digitsOnly, 3, 3) .
                                                '-' .
                                                substr($digitsOnly, 6);
                                        } elseif (strlen($digitsOnly) == 11 && substr($digitsOnly, 0, 1) == '1') {
                                            $formattedPhone =
                                                '(' .
                                                substr($digitsOnly, 1, 3) .
                                                ') ' .
                                                substr($digitsOnly, 4, 3) .
                                                '-' .
                                                substr($digitsOnly, 7);
                                        } else {
                                            $formattedPhone = $phone;
                                        }
                                        echo $formattedPhone;
                                    @endphp
                                </span>
                            </div>
                        @endif

                        @if ($companyData->email)
                            <div style="display: flex; align-items: center; gap: 5px;">
                                <span>📧</span>
                                <strong style="margin-right: 5px;">Email:</strong>
                                <a href="mailto:{{ $companyData->email }}"
                                    style="text-decoration: none;">{{ $companyData->email }}</a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Redes Sociales -->
        <div class="social-icons">
            @if ($companyData->facebook_link)
                <a href="{{ $companyData->facebook_link }}" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" width="30" alt="Facebook">
                </a>
            @endif
            @if ($companyData->instagram_link)
                <a href="{{ $companyData->instagram_link }}" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" width="30" alt="Instagram">
                </a>
            @endif
            @if ($companyData->linkedin_link)
                <a href="{{ $companyData->linkedin_link }}" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" width="30" alt="LinkedIn">
                </a>
            @endif
            @if ($companyData->twitter_link)
                <a href="{{ $companyData->twitter_link }}" target="_blank">
                    <img src="https://cdn-icons-png.flaticon.com/512/733/733579.png" width="30" alt="Twitter">
                </a>
            @endif
        </div>

        <!-- Pie de Página -->
        <div class="footer">
            <p>Horario de atención:<br>
                Lunes a Viernes: 9:00 AM - 5:00 PM</p>
            <p style="margin-top: 10px; font-size: 12px;">© {{ date('Y') }} {{ $companyData->company_name }}.
                Todos los derechos reservados.</p>
            @if ($companyData->address)
                <p style="font-size: 10px; color: #999;">{{ $companyData->address }}</p>
            @endif
        </div>
    </div>
</body>

</html>
