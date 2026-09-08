<?php

namespace App\Mail;

use App\Models\Transaksi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaksi $transaksi;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaksi $transaksi)
    {
        $this->transaksi = $transaksi->loadMissing(['mobil', 'user']);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Voucher Reservasi Resmi #' . $this->transaksi->nomor_booking . ' — Quantum Streamline',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_confirmation',
            with: [
                'transaksi' => $this->transaksi,
                'mobil' => $this->transaksi->mobil,
                'user' => $this->transaksi->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
