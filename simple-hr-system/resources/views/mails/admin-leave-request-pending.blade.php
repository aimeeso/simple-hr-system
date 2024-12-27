<x-mail::message>
Dear admins,

A leave request is submitted for review.

Please see the details below:
Employee: {{ $leaveRequest->user->name }} {{ $leaveRequest->user->last_name }}
Leave Type: {{ $leaveRequest->leaveType->name }}
From: {{ $leaveRequest->start_date }} {{ $leaveRequest->start_type }}
To: {{ $leaveRequest->end_date }} {{ $leaveRequest->end_type }}
Number of Days: {{ $leaveRequest->number_of_days }}


Best regards,<br>
config('app.name')
</x-mail::message>
