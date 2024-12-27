<?php

namespace App\Mail;

use App\Models\UserLeaveRequest;

class LeaveRequestPendingMail extends GenericLeaveRequestMail
{
    /**
     * Create a new message instance.
     */
    public function __construct(UserLeaveRequest $leaveRequest)
    {
        parent::__construct(
            leaveRequest: $leaveRequest,
            subject: 'Your leave request is submitted',
            markdown: 'mails.leave-request-pending',
        );
    }
}
