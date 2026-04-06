<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, sans-serif; color:#111827;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background-color:#f3f4f6; padding:24px 0;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:560px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#1d4ed8; color:#ffffff; padding:20px 24px; font-size:18px; font-weight:700; text-align:center;">
                            <img src="{{ asset('images/city_of_general_trias_seal.png') }}" alt="City of General Trias Seal" width="72" height="72" style="display:block; margin:0 auto 12px; width:72px; height:72px; border:0; outline:none; text-decoration:none;">
                            <div style="font-size:18px; font-weight:700; line-height:1.4;">
                                Password Reset Verification
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="margin:0 0 14px; font-size:15px; line-height:1.6; color:#374151;">
                                We received a request to reset your password. Use the one-time password below to continue.
                            </p>
                            <div style="margin:20px 0; text-align:center;">
                                <span style="display:inline-block; background:#eff6ff; color:#1e3a8a; border:1px solid #bfdbfe; border-radius:10px; padding:14px 20px; letter-spacing:8px; font-size:32px; font-weight:700;">
                                    {{ $otp }}
                                </span>
                            </div>
                            <p style="margin:0 0 8px; font-size:14px; color:#374151;">
                                This OTP expires in 10 minutes.
                            </p>
                            <p style="margin:0; font-size:13px; color:#6b7280;">
                                If you did not request this, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
