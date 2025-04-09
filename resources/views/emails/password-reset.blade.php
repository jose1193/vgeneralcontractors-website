<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Password Reset Notification</title>
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

        .details {
            line-height: 1.6;
            color: #333333;
        }

        .credentials {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #ffeeba;
        }

        .highlight {
            color: #856404;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="details">
            <h2 style="color: #856404; text-align: center; border-bottom: 2px solid #856404; padding-bottom: 10px;">
                Password Reset Notification 🔐
            </h2>

            <p>Hello {{ $user->name }},</p>

            <p>As requested, your password has been reset. Here are your new login credentials:</p>

            <div class="credentials">
                <p><strong>Username:</strong> {{ $user->username }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>New Password:</strong> {{ $password }}</p>
            </div>

            <p><strong>Important:</strong> For security reasons, please change this password after your next login.</p>

            <div style="text-align: center; margin-top: 25px;">
                <p>If you did not request this password reset, please contact us immediately.</p>
            </div>
        </div>

        <div class="footer" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <a href="https://facebook.com/vgeneralcontractors"
                    style="margin: 0 10px; text-decoration: none; color: #666;">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" width="30" alt="Facebook">
                </a>
                <a href="https://instagram.com/vgeneralcontractors"
                    style="margin: 0 10px; text-decoration: none; color: #666;">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" width="30" alt="Instagram">
                </a>
                <a href="https://twitter.com/vgeneralcontractors"
                    style="margin: 0 10px; text-decoration: none; color: #666;">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124021.png" width="30" alt="Twitter">
                </a>
                <a href="https://linkedin.com/company/vgeneralcontractors"
                    style="margin: 0 10px; text-decoration: none; color: #666;">
                    <img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" width="30" alt="LinkedIn">
                </a>
            </div>

            <div style="text-align: center; color: #666; font-size: 14px;">
                <p style="margin: 5px 0;">
                    <strong>Address:</strong> {{ $companyData->address }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Phone:</strong> {{ \App\Helpers\PhoneHelper::format($companyData->phone) }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Email:</strong> {{ $companyData->email }}
                </p>
                <p style="margin: 5px 0;">
                    <strong>Business Hours:</strong><br>
                    Monday to Friday: 9:00 AM - 5:00 PM
                </p>
            </div>

            <div style="text-align: center; margin-top: 20px;">
                <p style="color: #666; font-size: 12px;">
                    Copyright © {{ date('Y') }} {{ $companyData->company_name }} - All Rights Reserved
                </p>
                <p style="font-size: 10px; color: #999;">{{ $companyData->address ?? '' }}</p>
            </div>
        </div>
    </div>
</body>

</html>
