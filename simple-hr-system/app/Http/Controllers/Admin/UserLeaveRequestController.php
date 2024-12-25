<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLeaveRequestController as BaseUserLeaveRequestController;
use App\Http\Requests\UserLeaveRequestAdminStoreRequest;
use App\Http\Requests\UserLeaveRequestAdminUpdateRequest;
use App\Http\Resources\UserLeaveRequest\UserLeaveRequestDetailResource;
use App\Http\Resources\UserLeaveRequest\UserLeaveRequestListResource;
use App\Models\User;
use App\Models\UserLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function adminStore(UserLeaveRequestAdminStoreRequest $request)
    {
        $this->authorize("create", UserLeaveRequest::class);
        $currentUser = $request->user();

        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $userLeaveRequest = new UserLeaveRequest();
            $userLeaveRequest->user_id = $validated["user_id"];
            $userLeaveRequest->leave_type_id = $validated["leave_type_id"];
            $userLeaveRequest->start_date = $validated["start_date"];
            $userLeaveRequest->start_type = $validated["start_type"];
            $userLeaveRequest->end_date = $validated["end_date"];
            $userLeaveRequest->end_type = $validated["end_type"];
            $userLeaveRequest->number_of_leave_day = $this->countNumberOfDays($validated["start_date"], $validated["start_type"], $validated["end_date"], $validated["end_type"]);
            $userLeaveRequest->status = $validated["status"];
            $userLeaveRequest->count_annual_leave = $validated["count_annual_leave"];
            $userLeaveRequest->updated_by = $currentUser->name;

            if($validated["status"] === "approved") {
                $userLeaveRequest->approved_at = now();
                if($validated["count_annual_leave"] > 0) {
                    // TODO: update annual leave
                }
            }

            $userLeaveRequest->save();

            DB::commit();  

            return UserLeaveRequestDetailResource::make($userLeaveRequest);

        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function adminUpdate(UserLeaveRequest $userLeaveRequest, UserLeaveRequestAdminUpdateRequest $request)
    {
        $this->authorize("update", $userLeaveRequest);

        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $userLeaveRequest->leave_type_id = $validated["leave_type_id"];
            $userLeaveRequest->start_date = $validated["start_date"];
            $userLeaveRequest->start_type = $validated["start_type"];
            $userLeaveRequest->end_date = $validated["end_date"];
            $userLeaveRequest->end_type = $validated["end_type"];
            $userLeaveRequest->number_of_leave_day = $this->countNumberOfDays($validated["start_date"], $validated["start_type"], $validated["end_date"], $validated["end_type"]);
            $userLeaveRequest->status = $validated["status"];
            $userLeaveRequest->count_annual_leave = $validated["count_annual_leave"];
            $userLeaveRequest->updated_by = $request->user()->name;

            if($validated["status"] === "APPROVED" && $userLeaveRequest->status !== "approved") {
                $userLeaveRequest->approved_at = now();
                // TODO: update annual leave
            }
            else if($validated["status"] === "CANCELED" && $userLeaveRequest->status === "approved") {
                $userLeaveRequest->approved_at = null;
                // TODO: update annual leave
            }

            $userLeaveRequest->save();
            DB::commit();

            return response()->noContent();
        }
        catch (\Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}
