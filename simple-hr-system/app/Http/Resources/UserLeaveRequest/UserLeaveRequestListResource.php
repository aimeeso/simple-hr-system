<?php

namespace App\Http\Resources\UserLeaveRequest;

use App\Http\Resources\User\UserListResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLeaveRequestListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "user" => new UserListResource($this->user),
            "leave_type" => $this->leave_type,
            "start_date" => $this->start_date,
            "start_type" => $this->start_type,
            "end_date" => $this->end_date,
            "end_type" => $this->end_type,
            "number_of_leave_day" => $this->number_of_leave_day,
            "status" => $this->status,
        ];
    }
}
