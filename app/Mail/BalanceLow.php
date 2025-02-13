<?php

namespace App\Mail;

use App\Models\Wallet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BalanceLow extends Mailable
{
    use Queueable, SerializesModels;

    public Wallet $wallet;

    /**
     * Create a new message instance.
     */
    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your balance is low',
            from: new Address(env('MAIL_FROM_ADDRESS', 'hello@example.com'), env('MAIL_FROM_NAME', 'Example'))
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.balance-low',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
