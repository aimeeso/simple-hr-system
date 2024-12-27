<?php

namespace App\Mail;

use App\Models\UserLeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GenericLeaveRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected UserLeaveRequest $leaveRequest;
    protected string $subject;
    protected string $markdown;

    /**
     * Create a new message instance.
     */
    public function __construct(UserLeaveRequest $leaveRequest, string $subject, string $markdown)
    {
        $this->leaveRequest = $leaveRequest;
        $this->subject = $subject;
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
            markdown: $this->markdown,
            with: ['leaveRequest' => $this->leaveRequest],
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
