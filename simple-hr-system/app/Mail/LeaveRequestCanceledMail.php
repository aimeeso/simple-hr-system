<?php

namespace App\Mail;

use App\Models\UserLeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LeaveRequestCanceledMail extends GenericLeaveRequestMail
{
    /**
     * Create a new message instance.
     */
    public function __construct(UserLeaveRequest $leaveRequest)
    {
        parent::__construct(
            leaveRequest: $leaveRequest,
            subject: 'Your leave request is canceled',
            markdown: 'mails.leave-request-canceled',
        );
    }
}
