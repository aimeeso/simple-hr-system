<?php

namespace App\Mail;

use App\Models\UserLeaveRequest;

class LeaveRequestApprovedMail extends GenericLeaveRequestMail
{
    /**
     * Create a new message instance.
     */
    public function __construct(UserLeaveRequest $leaveRequest)
    {
        parent::__construct(
            leaveRequest: $leaveRequest,
            subject: 'Your leave request is approved',
            markdown: 'mails.leave-request-approved',
        );
    }
}
