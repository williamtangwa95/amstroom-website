<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Code</title>
</head>
<body style="font-family: 'Poppins', Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 40px 20px; color: #333333;">

    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border-top: 5px solid #0B4FB5;">
        
        <!-- Header Branding -->
        <div style="background-color: #f8fafc; padding: 25px; text-align: center; border-bottom: 1px solid #f1f5f9;">
            <h2 style="margin: 0; color: #0B4FB5; font-size: 20px; font-weight: 800; letter-spacing: 0.5px;">AMSTROOM COMPUTERS</h2>
            <span style="color: #39A8E8; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Administration Portal</span>
        </div>

        <!-- Body Message -->
        <div style="padding: 30px; line-height: 1.6;">
            <p style="margin-top: 0; font-size: 15px; color: #4b5563;">Hello,</p>
            <p style="font-size: 15px; color: #4b5563; margin-bottom: 25px;">We received a request to reset your administrative portal account password. Use the verification code below to authorize this request:</p>
            
            <!-- 6-digit Code block -->
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; text-align: center; margin-bottom: 25px;">
                <span style="font-size: 32px; font-weight: 800; color: #0B4FB5; letter-spacing: 6px;">{{ $code }}</span>
            </div>
            
            <p style="font-size: 13px; color: #ef4444; font-weight: 600; margin-bottom: 20px;">* This verification code is only valid for the next 15 minutes.</p>
            
            <hr style="border: none; border-top: 1px dashed #e2e8f0; margin: 25px 0;">
            
            <p style="font-size: 12px; color: #9ca3af; margin-bottom: 0;">If you did not request a password reset, please ignore this email or contact support if you suspect unauthorized access.</p>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #f1f5f9; font-size: 11px; color: #9ca3af;">
            &copy; {{ date('Y') }} AMSTROOM COMPUTERS. All rights reserved.
        </div>
        
    </div>

</body>
</html>
