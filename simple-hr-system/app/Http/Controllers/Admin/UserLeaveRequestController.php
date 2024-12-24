<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLeaveRequestController as BaseUserLeaveRequestController;
use App\Http\Resources\UserLeaveRequest\UserLeaveRequestListResource;
use App\Models\User;
use App\Models\UserLeaveRequest;
use Illuminate\Http\Request;

class UserLeaveRequestController extends BaseUserLeaveRequestController
{
    //
    public function indexAny(Request $request)
    {
        $this->authorize("viewAny", UserLeaveRequest::class);
        $pageSize = $request->query("pageSize", 10);
        $statusFilter = $request->query("status");

        $query = UserLeaveRequest::filterStatus($statusFilter);

        return UserLeaveRequestListResource::collection($query->paginate($pageSize));
    }

    public function indexOnly(User $user, Request $request)
    {
        $this->authorize("view", $user);

        $pageSize = $request->query("pageSize", 10);
        $statusFilter = $request->query("status");

        $query = $user->leaveRequests()->filterStatus($statusFilter);

        return UserLeaveRequestListResource::collection($query->paginate($pageSize));
    }

    public function store(Request $request)
    {
        $this->authorize("create", UserLeaveRequest::class);
        $currentUser = $request->user();

        $userLeaveRequest = new UserLeaveRequest();
        $userLeaveRequest->user_id = $request->input("user_id");
        $userLeaveRequest->leave_type_id = $request->input("leave_type_id");
        $userLeaveRequest->start_date = $request->input("start_date");
        $userLeaveRequest->start_type = $request->input("start_type");
        $userLeaveRequest->end_date = $request->input("end_date");
        $userLeaveRequest->end_type = $request->input("end_type");
        $userLeaveRequest->number_of_leave_day = $this->countNumberOfDays($request->input("start_date"), $request->input("start_type"), $request->input("end_date"), $request->input("end_type"));
        $userLeaveRequest->status = $request->input("status");
        $userLeaveRequest->count_annual_leave = $request->input("count_annual_leave");
        $userLeaveRequest->updated_by = $currentUser->name;
        $userLeaveRequest->save();

        return new UserLeaveRequestListResource($userLeaveRequest);
    }

    public function update(UserLeaveRequest $userLeaveRequest, Request $request)
    {
        $this->authorize("update", $userLeaveRequest);
        $userLeaveRequest->status = $request->input("status");
        $userLeaveRequest->count_annual_leave = $request->input("count_annual_leave");
        $userLeaveRequest->updated_by = $request->user()->name;
        if($request->input("status") === "approved" && $userLeaveRequest->status !== "approved") {
            $userLeaveRequest->approved_at = now();
        }
        $userLeaveRequest->save();
        return new UserLeaveRequestListResource($userLeaveRequest);
    }
}
