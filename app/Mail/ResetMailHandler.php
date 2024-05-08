<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetMailHandler extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;
    private $token;
    public $view = "mail.verification";
    public $subject = 'Verification Mail';

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $token,$view,$subject)
    {
        $this->user = $user;
        $this->token = $token;
        if ($view) $this->view = $view;
        if ($view) $this->subject = $subject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,with: ["user"=>$this->user,"token"=>$this->token]
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
