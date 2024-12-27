@component('mails.themes.message')
Dear admins,

A leave request is rejected.

Please see the details below:
Employee: {{ $leaveRequest->user->name }} {{ $leaveRequest->user->last_name }}
Leave Type: {{ $leaveRequest->leaveType->name }}
From: {{ $leaveRequest->start_date }} {{ $leaveRequest->start_type }}
To: {{ $leaveRequest->end_date }} {{ $leaveRequest->end_type }}
Number of Days: {{ $leaveRequest->number_of_days }}
Approved By: {{ $leaveRequest->approved_by }}
Approved At: {{ $leaveRequest->approved_at }}


Best regards,<br>
config('app.name')
@endcomponent