<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recordatorio de Inspección</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #3490dc;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }

        .appointment-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9em;
            color: #6c757d;
        }

        .company-info {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .social-links {
            margin-top: 15px;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #3490dc;
            text-decoration: none;
        }

        .button {
            background-color: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 15px;
        }

        .logo {
            max-width: 200px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Recordatorio de Inspección de Techo</h1>
    </div>

    <div class="content">
        @if ($isInternal)
            <p>Hola Administrador,</p>
            <p>Este es un recordatorio para una inspección de techo programada para mañana con el cliente
                <strong>{{ $appointment->first_name }} {{ $appointment->last_name }}</strong>.
            </p>
        @else
            <p>Hola <strong>{{ $appointment->first_name }}</strong>,</p>
            <p>Este es un cordial recordatorio de que tu inspección de techo con
                {{ \App\Models\CompanyData::first()->company_name ?? 'V General Contractors' }} está programada para
                mañana.</p>
        @endif

        <div class="appointment-details">
            <h3>Detalles de la Inspección:</h3>
            <p><strong>Fecha:</strong>
                {{ \Carbon\Carbon::parse($appointment->inspection_date)->locale('es')->isoFormat('dddd D [de] MMMM [de] YYYY') }}
            </p>
            <p><strong>Hora:</strong> {{ \Carbon\Carbon::parse($appointment->inspection_time)->format('HH:mm') }}</p>
            <p><strong>Dirección:</strong> {{ $appointment->address }}
                @if ($appointment->address_2)
                    , {{ $appointment->address_2 }}
                @endif
                <br>{{ $appointment->city }}, {{ $appointment->state }} {{ $appointment->zipcode }}
            </p>
            @if ($appointment->damage_detail)
                <p><strong>Detalles del Daño:</strong> {{ $appointment->damage_detail }}</p>
            @endif
            @if ($appointment->notes)
                <p><strong>Notas Adicionales:</strong> {{ $appointment->notes }}</p>
            @endif
        </div>

        @if (!$isInternal)
            <p>Un inspector profesional llegará a la hora programada para evaluar tu techo. Por favor asegúrate de que
                haya acceso disponible a la propiedad.</p>
            <p>Si necesitas reprogramar o tienes alguna pregunta, contáctanos lo antes posible:</p>
            <p><strong>Teléfono:</strong>
                @php
                    $phone = \App\Models\CompanyData::first()->phone ?? '(346) 692-0757';
                    // Remove any non-digit characters
                    $digitsOnly = preg_replace('/[^0-9]/', '', $phone);
                    // Format the number based on length
                    if (strlen($digitsOnly) == 10) {
                        echo '(' .
                            substr($digitsOnly, 0, 3) .
                            ') ' .
                            substr($digitsOnly, 3, 3) .
                            '-' .
                            substr($digitsOnly, 6);
                    } elseif (strlen($digitsOnly) == 11 && substr($digitsOnly, 0, 1) == '1') {
                        // US number with country code
                        echo '(' .
                            substr($digitsOnly, 1, 3) .
                            ') ' .
                            substr($digitsOnly, 4, 3) .
                            '-' .
                            substr($digitsOnly, 7);
                    } else {
                        // Fallback to original
                        echo $phone;
                    }
                @endphp
            </p>
            <p><strong>Correo:</strong> {{ \App\Models\CompanyData::first()->email ?? 'info@vgeneralcontractors.com' }}
            </p>
        @else
            <div style="background-color: #f5f5f5; border-left: 4px solid #3490dc; padding: 15px; margin: 20px 0;">
                <h4 style="margin-top: 0; color: #3490dc;">Información del Cliente:</h4>
                <p><strong>Nombre Completo:</strong> {{ $appointment->getFullNameAttribute() }}</p>
                <p><strong>Teléfono:</strong> {{ $appointment->phone }}</p>
                <p><strong>Correo:</strong> {{ $appointment->email }}</p>

                <h4 style="margin-bottom: 5px;">Dirección Completa:</h4>
                <p style="margin-top: 0;">
                    {{ $appointment->address }}
                    @if ($appointment->address_2)
                        , {{ $appointment->address_2 }}
                    @endif
                    <br>
                    {{ $appointment->city }}, {{ $appointment->state }} {{ $appointment->zipcode }}<br>
                    @if ($appointment->country)
                        {{ $appointment->country }}
                    @endif
                </p>

                @if ($appointment->insurance_property)
                    <p><strong>Tiene Seguro de Propiedad:</strong> Sí</p>
                @else
                    <p><strong>Tiene Seguro de Propiedad:</strong> No</p>
                @endif

                @if ($appointment->intent_to_claim)
                    <p><strong>Intención de Reclamar:</strong> Sí</p>
                @endif

                @if ($appointment->lead_source)
                    <p><strong>Fuente del Lead:</strong> {{ $appointment->lead_source }}</p>
                @endif

                @if ($appointment->damage_detail)
                    <p><strong>Detalles del Daño:</strong> {{ $appointment->damage_detail }}</p>
                @endif

                @if ($appointment->additional_note)
                    <p><strong>Notas Adicionales:</strong> {{ $appointment->additional_note }}</p>
                @endif

                @if ($appointment->notes)
                    <p><strong>Notas:</strong> {{ $appointment->notes }}</p>
                @endif
            </div>
        @endif

        <div class="company-info">
            <p><strong>{{ \App\Models\CompanyData::first()->company_name ?? 'V General Contractors' }}</strong></p>
            <p>{{ \App\Models\CompanyData::first()->address ?? '1302 Waugh Dr # 810 Houston TX 77019' }}</p>

            <div class="social-links">
                <a href="https://facebook.com/vgeneralcontractors" target="_blank">Facebook</a>
                <a href="https://instagram.com/vgeneralcontractors" target="_blank">Instagram</a>
            </div>

            <p><a href="{{ \App\Models\CompanyData::first()->website ?? 'https://vgeneralcontractors.com' }}"
                    target="_blank">{{ \App\Models\CompanyData::first()->website ?? 'www.vgeneralcontractors.com' }}</a>
            </p>
        </div>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} {{ \App\Models\CompanyData::first()->company_name ?? 'V General Contractors' }}.
            Todos los derechos reservados.</p>
    </div>
</body>

</html>
