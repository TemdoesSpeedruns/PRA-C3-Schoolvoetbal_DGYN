<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 30px;
        }
        .status-box {
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
            border: 2px solid #fbbf24;
        }
        .status-approved {
            background-color: #dcfce7;
            color: #166534;
            border: 2px solid #86efac;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
            border: 2px solid #fca5a5;
        }
        .info-box {
            background-color: #f0f4ff;
            padding: 15px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 5px 0;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #5568d3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🏫 Schoolvoetbal Toernooi</h1>
        </div>

        <!-- Content -->
        <div class="content">
            @if ($result === null)
                <!-- Initial Registration Confirmation -->
                <h2>Dank u voor uw registratie!</h2>
                
                <p>Beste {{ $school->contact_person }},</p>
                
                <p>We hebben uw schoolaanmelding ontvangen. Hier zijn uw ingevulde gegevens:</p>

                <div class="info-box">
                    <p><strong>Schoolnaam:</strong> {{ $school->name }}</p>
                    <p><strong>Contactpersoon:</strong> {{ $school->contact_person }}</p>
                    <p><strong>E-mailadres:</strong> {{ $school->email }}</p>
                    <p><strong>Indiendatum:</strong> {{ $school->created_at->format('d-m-Y H:i') }}</p>
                </div>

                <div class="status-box status-pending">
                    Status: In behandeling ⏳
                </div>

                <p>Uw aanmelding wordt binnenkort beoordeeld door onze administratie. U ontvangt binnenkort een e-mail met de uitslag.</p>

                <p>Met vriendelijke groeten,<br>
                Het Schoolvoetbal Toernooi Team</p>

            @elseif ($result === 'approved')
                <!-- Approval Email -->
                <h2>Gefeliciteerd! Uw aanmelding is goedgekeurd! 🎉</h2>
                
                <p>Beste {{ $school->contact_person }},</p>
                
                <p>We hebben het genoegen u mee te delen dat uw school <strong>{{ $school->name }}</strong> is goedgekeurd voor deelname aan het Schoolvoetbal Toernooi!</p>

                <div class="status-box status-approved">
                    Status: Goedgekeurd ✓
                </div>

                <div class="info-box">
                    <p><strong>Schoolnaam:</strong> {{ $school->name }}</p>
                    <p><strong>Contactpersoon:</strong> {{ $school->contact_person }}</p>
                    <p><strong>E-mailadres:</strong> {{ $school->email }}</p>
                    <p><strong>Goedkeuringsdatum:</strong> {{ now()->format('d-m-Y H:i') }}</p>
                </div>

                <p>Uw school kan nu deelnemen aan alle beschikbare toernooien. Veel succes!</p>

                <p>Met vriendelijke groeten,<br>
                Het Schoolvoetbal Toernooi Team</p>

            @elseif ($result === 'rejected')
                <!-- Rejection Email -->
                <h2>Bericht over uw schoolaanmelding</h2>
                
                <p>Beste {{ $school->contact_person }},</p>
                
                <p>Naar aanleiding van uw aanmelding, hebben wij helaas moeten besluiten dat school <strong>{{ $school->name }}</strong> op dit moment niet kan deelnemen aan het Schoolvoetbal Toernooi.</p>

                <div class="status-box status-rejected">
                    Status: Afgewezen ✗
                </div>

                <div class="info-box">
                    <p><strong>Schoolnaam:</strong> {{ $school->name }}</p>
                    <p><strong>Afwijzingsdatum:</strong> {{ now()->format('d-m-Y H:i') }}</p>
                </div>

                <p>Mocht u vragen hebben over deze beslissing, neem dan gerust contact met ons op.</p>

                <p>Met vriendelijke groeten,<br>
                Het Schoolvoetbal Toernooi Team</p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ now()->year }} Schoolvoetbal Toernooi. Alle rechten voorbehouden.</p>
            <p>Dit is een automatisch gegenereerde e-mail, antwoorden alstublieft niet op dit e-mailadres.</p>
        </div>
    </div>
</body>
</html>
