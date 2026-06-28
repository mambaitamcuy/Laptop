<!DOCTYPE html>
<html>
<head>
    <title>Kode Keamanan OTP</title>
</head>
<body style="background-color: #f1f5f9; font-family: 'Segoe UI', Arial, sans-serif; padding: 40px 10px; margin: 0;">

    <div style="max-width: 500px; background-color: #ffffff; margin: 0 auto; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
        
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="color: #1e3a8a; margin: 0; font-size: 24px; font-weight: 700;">Arkadia Analytics</h2>
            <p style="color: #64748b; font-size: 14px; margin-top: 5px;">Sistem Keamanan Multi-Faktor (MFA)</p>
        </div>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-bottom: 25px;">

        <p style="color: #334155; font-size: 16px; line-height: 1.5;">Halo, Personel Arkadia.</p>
        <p style="color: #334155; font-size: 14px; line-height: 1.5;">Kami mendeteksi aktivitas login ke akun Anda. Gunakan kode OTP di bawah ini untuk menyelesaikan proses verifikasi keamanan:</p>

        <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; text-align: center; padding: 20px; margin: 25px 0; border-radius: 8px;">
            <span style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #3b82f6;">{{ $otpCode }}</span>
        </div>

        <p style="color: #ef4444; font-size: 12px; font-weight: 600;">⚠️ Kode ini hanya berlaku selama 10 menit. Jangan membagikan kode ini kepada siapa pun, termasuk tim manajemen pusat.</p>

        <hr style="border: 0; border-top: 1px solid #e2e8f0; margin-top: 30px; margin-bottom: 20px;">

        <div style="text-align: center; color: #94a3b8; font-size: 11px;">
            <p style="margin: 0;">Sistem Otomatis Arkadia LP &copy; 2026</p>
        </div>

    </div>

</body>
</html>