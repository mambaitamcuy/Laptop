<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;

    /**
     * Membuat instance mailable baru.
     */
    public function __construct($otpCode)
    {
        // Lempar data OTP agar bisa dibaca di file blade email
        $this->otpCode = $otpCode;
    }

    /**
     * Mengatur Judul / Subjek Email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔒 KODE OTP: Verifikasi Dua Langkah Arkadia LP',
        );
    }

    /**
     * Menghubungkan ke file tampilan HTML email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }
}