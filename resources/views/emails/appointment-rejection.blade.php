<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Importante Sobre Su Solicitud de Inspección</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .header {
            background-color: #1a365d;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            padding: 20px;
            background-color: #f9fafb;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }

        .footer {
            background-color: #f3f4f6;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-radius: 0 0 5px 5px;
            border: 1px solid #e5e7eb;
        }

        .logo {
            max-width: 150px;
            margin: 0 auto 15px;
            display: block;
        }

        h1 {
            color: #ffffff;
            font-size: 24px;
            margin: 0;
            font-weight: 600;
        }

        h2 {
            color: #1a365d;
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .details {
            margin-bottom: 25px;
        }

        .details p {
            margin: 8px 0;
        }

        .highlight {
            color: #1a365d;
            font-weight: 600;
        }

        .btn {
            display: inline-block;
            background-color: #1a365d;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 15px;
        }

        .reason-list {
            padding-left: 20px;
            margin-bottom: 20px;
        }

        .reason-list li {
            margin-bottom: 10px;
        }

        .contact-info {
            margin-top: 25px;
            background-color: #e6f2ff;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #1a365d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo-white.png') }}" alt="VGeneralContractors Logo" class="logo">
            <h1>Información Importante Sobre Su Solicitud de Inspección</h1>
        </div>

        <div class="content">
            <h2>Hola {{ $appointment->first_name }} {{ $appointment->last_name }},</h2>

            <div class="details">
                <p>Gracias por su interés en nuestros servicios. Después de revisar su solicitud de inspección,
                    lamentamos informarle que actualmente no podemos proceder con su cita por la(s) siguiente(s)
                    razón(es):</p>

                <ul class="reason-list">
                    @if ($noContact)
                        <li>Hemos intentado contactarle varias veces con respecto a su solicitud de inspección pero no
                            hemos podido comunicarnos con usted. Por favor, si sigue interesado en nuestros servicios,
                            no dude en llamarnos al número que aparece a continuación.</li>
                    @endif

                    @if ($noInsurance)
                        <li>Nuestros registros indican que su propiedad no cuenta con la cobertura de seguro necesaria
                            para que podamos realizar los servicios de inspección.</li>
                    @endif

                    @if ($otherReason)
                        <li>{{ $otherReason }}</li>
                    @endif
                </ul>

                <p>Detalles de su solicitud:</p>
                <p><span class="highlight">Fecha de Cita:</span>
                    {{ $appointment->inspection_date ? date('j \d\e F \d\e Y', strtotime($appointment->inspection_date)) : 'No especificada' }}
                </p>
                <p><span class="highlight">Dirección de Propiedad:</span>
                    {{ $appointment->address ?? 'No especificada' }}</p>

                <div class="contact-info">
                    <p>Si cree que esta decisión se tomó por error o si sus circunstancias han cambiado, no dude en
                        contactarnos:</p>
                    @if ($companyData)
                        <p><span class="highlight">Teléfono:</span> {{ $companyData->phone ?? '(No disponible)' }}</p>
                        <p><span class="highlight">Email:</span>
                            {{ $companyData->email ?? 'support@vgeneralcontractors.com' }}</p>
                        @if ($companyData->address)
                            <p><span class="highlight">Dirección:</span> {{ $companyData->address }}</p>
                        @endif
                    @else
                        <p><span class="highlight">Teléfono:</span> (555) 123-4567</p>
                        <p><span class="highlight">Email:</span> support@vgeneralcontractors.com</p>
                    @endif
                </div>

                <p>Valoramos su interés en nuestros servicios y estaremos encantados de ayudarle en el futuro si estas
                    circunstancias cambian.</p>

                <p>Atentamente,<br>
                    El Equipo de VGeneralContractors</p>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} VGeneralContractors. Todos los derechos reservados.</p>
            <p>Este es un mensaje automático, por favor no responda a este correo.</p>
        </div>
    </div>
</body>

</html>
