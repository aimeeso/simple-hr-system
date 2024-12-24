<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserLeaveRequest\UserLeaveRequestListResource;
use App\Models\UserLeaveRequest;
use Illuminate\Http\Request;

class UserLeaveRequestController extends Controller
{
    //
    public function index(Request $request)
    {
        $currentUser = $request->user();
        $this->authorize("view", $currentUser);

        $pageSize = $request->query("pageSize", 10);
        $statusFilter = $request->query("status");

        $query = $currentUser->leaveRequests()->filterStatus($statusFilter);

        return UserLeaveRequestListResource::collection($query->paginate($pageSize));
    }

    public function show(UserLeaveRequest $userLeaveRequest)
    {
        $this->authorize("view", $userLeaveRequest);
        return new UserLeaveRequestListResource($userLeaveRequest);
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();
        $this->authorize("create", UserLeaveRequest::class);

        $userLeaveRequest = new UserLeaveRequest();
        $userLeaveRequest->user_id = $currentUser->id;
        $userLeaveRequest->leave_type_id = $request->input("leave_type_id");
        $userLeaveRequest->start_date = $request->input("start_date");
        $userLeaveRequest->start_type = $request->input("start_type");
        $userLeaveRequest->end_date = $request->input("end_date");
        $userLeaveRequest->end_type = $request->input("end_type");
        $userLeaveRequest->number_of_leave_day = $this->countNumberOfDays($request->input("start_date"), $request->input("start_type"), $request->input("end_date"), $request->input("end_type"));
        $userLeaveRequest->status = $request->input("status");
        $userLeaveRequest->updated_by = "applicant";
        $userLeaveRequest->save();

        return new UserLeaveRequestListResource($userLeaveRequest);
    }

    protected function countNumberOfDays($startDate, $startType, $endDate, $endType)
    {
        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $diff = $startDate->diff($endDate);

        if ($startType === "AM" && $endType === "PM") {
            return $diff->days + 1;
        }
        else if ($startType === "AM" && $endType === "AM") {
            return $diff->days + 0.5;
        }
        else if ($startType === "PM" && $endType === "PM") {
            return $diff->days + 0.5;
        }
        else {
            return $diff->days - 1;
        }

    }
}
