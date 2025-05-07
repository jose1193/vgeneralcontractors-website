<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>🔄 Cita Reprogramada - V General Contractors</title>
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

        .highlight-purple {
            color: #8b5cf6;
            font-weight: bold;
        }

        .reschedule-banner {
            background: #f5f3ff;
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
            background-color: #8b5cf6;
            color: white;
        }

        .action-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #8b5cf6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
        }

        .time-change {
            background: #f5f3ff;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #8b5cf6;
        }
    </style>
</head>

<body>
    @php
        // Función para formatear números de teléfono españoles
        if (!function_exists('formatSpanishPhone')) {
            function formatSpanishPhone($phoneNumber)
            {
                // Eliminar cualquier espacio existente
                $cleaned = preg_replace('/\s+/', '', $phoneNumber);

                // Si el número comienza con +34, formatearlo correctamente
                if (str_starts_with($cleaned, '+34')) {
                    // Quitar el código de país para trabajar solo con los 9 dígitos
                    $nationalNumber = substr($cleaned, 3);

                    // Aplicar formato +34 XXX XX XX XX
                    if (strlen($nationalNumber) === 9) {
                        return '+34 ' .
                            substr($nationalNumber, 0, 3) .
                            ' ' .
                            substr($nationalNumber, 3, 2) .
                            ' ' .
                            substr($nationalNumber, 5, 2) .
                            ' ' .
                            substr($nationalNumber, 7, 2);
                    }
                }

                // Si no es un número español o no tiene el formato esperado, devolverlo sin cambios
                return $phoneNumber;
            }
        }
    @endphp

    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="https://vgeneralcontractors.com/assets/logo/logo-png.png" width="180"
                alt="Logo V General Contractors">
        </div>

        <div class="details">
            <h2 style="color: #8b5cf6; text-align: center; border-bottom: 2px solid #8b5cf6; padding-bottom: 10px;">
                🔄 Su cita ha sido reprogramada
            </h2>

            <div class="reschedule-banner">
                <p style="text-align: center; margin: 0;">
                    <strong>Hemos reprogramado su cita con éxito.</strong>
                </p>
            </div>

            <p style="margin: 20px 0;">Hola <strong>{{ $appointment->client_first_name }}
                    {{ $appointment->client_last_name }}</strong>,</p>

            <p>Le informamos que su cita con <strong>{{ $companyData->company_name }}</strong> ha sido reprogramada.
                A continuación encontrará los nuevos detalles de su cita.</p>

            <div class="time-change">
                <h4 style="margin-top: 0; color: #2d3748;">Cambio de Horario:</h4>
                <p style="margin: 5px 0;"><strong>Fecha y hora anterior:</strong><br>
                    {{ \Carbon\Carbon::parse($appointment->original_start_time)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY [a las] HH:mm') }}
                </p>
                <p style="margin: 5px 0;"><strong>Nueva fecha y hora:</strong></p>
                <p style="margin: 5px 0;">
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
                </p>
            </div>

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
                        <td><span class="status-tag">Reprogramada</span></td>
                    </tr>
                    <tr>
                        <td><strong>Duración:</strong></td>
                        <td>{{ $appointment->service ? $appointment->service->duration : '?' }} minutos</td>
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
                    <li>Por favor, confirme que la nueva fecha y hora le convienen</li>
                    <li>Si necesita hacer algún ajuste adicional, contáctenos lo antes posible</li>
                    <li>Por favor, tenga a mano su <strong>póliza de cobertura de seguro</strong> de su propiedad el día
                        de la inspección</li>
                </ul>
            </div>

            @if ($companyData->website)
                <div style="text-align: center; margin: 20px 0;">
                    <a href="{{ $companyData->website }}/appointments/reschedule/{{ $appointment->id }}"
                        class="action-button">Reprogramar Cita</a>
                </div>
            @endif

            <p>Si tiene alguna pregunta o necesita asistencia adicional, no dude en contactarnos:</p>

            <div style="text-align: center; margin: 20px 0;">
                @if ($companyData->phone)
                    <p><strong>📞 Teléfono:</strong> {{ formatSpanishPhone($companyData->phone) }}</p>
                @endif
                @if ($companyData->email)
                    <p><strong>📧 Email:</strong> {{ $companyData->email }}</p>
                @endif
            </div>
        </div>

        <!-- Redes Sociales -->
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
