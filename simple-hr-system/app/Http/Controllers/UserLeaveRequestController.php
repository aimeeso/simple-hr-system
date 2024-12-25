<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLeaveRequestStoreRequest;
use App\Http\Requests\UserLeaveRequestUpdateRequest;
use App\Http\Resources\UserLeaveRequest\UserLeaveRequestDetailResource;
use App\Http\Resources\UserLeaveRequest\UserLeaveRequestListResource;
use App\Models\UserLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function store(UserLeaveRequestStoreRequest $request)
    {
        $currentUser = $request->user();
        $this->authorize("create", UserLeaveRequest::class);

        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $userLeaveRequest = new UserLeaveRequest();
            $userLeaveRequest->user_id = $currentUser->id;
            $userLeaveRequest->leave_type_id = $validated["leave_type_id"];
            $userLeaveRequest->start_date = $validated["start_date"];
            $userLeaveRequest->start_type = $validated["start_type"];
            $userLeaveRequest->end_date = $validated["end_date"];
            $userLeaveRequest->end_type = $validated["end_type"];
            $userLeaveRequest->number_of_leave_day = $this->countNumberOfDays($validated["start_date"], $validated["start_type"], $validated["end_date"], $validated["end_type"]);
            $userLeaveRequest->status = $validated["status"];
            $userLeaveRequest->updated_by = "applicant";
            $userLeaveRequest->save();
            DB::commit();

            // TODO: send email if it is pending

            return new UserLeaveRequestDetailResource($userLeaveRequest);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function update(UserLeaveRequest $userLeaveRequest, UserLeaveRequestUpdateRequest $request)
    {
        $this->authorize("update", $userLeaveRequest);

        // only allow to update if status is draft
        if ($userLeaveRequest->status !== "DRAFT") {
            return response()->json(["message" => "Only draft request can be updated"], 400);
        }

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
            $userLeaveRequest->updated_by = "applicant";
            $userLeaveRequest->updated_at = now();

            DB::commit();

            return response()->noContent();
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function destroy(UserLeaveRequest $userLeaveRequest)
    {
        $this->authorize("delete", $userLeaveRequest);

        // only allow to delete if status is draft
        if ($userLeaveRequest->status !== "DRAFT") {
            return response()->json(["message" => "Only draft request can be deleted"], 400);
        }

        $userLeaveRequest->delete();
        return response()->noContent();
    }

    public function cancel(UserLeaveRequest $userLeaveRequest)
    {
        $this->authorize("update", $userLeaveRequest);

        // only allow to cancel if status is pending
        if ($userLeaveRequest->status !== "PENDING") {
            return response()->json(["message" => "Only pending request can be canceled"], 400);
        }

        try {
            DB::beginTransaction();
            $userLeaveRequest->status = "CANCELED";
            $userLeaveRequest->updated_by = "applicant";
            $userLeaveRequest->updated_at = now();
            $userLeaveRequest->save();
            DB::commit();

            return response()->noContent();
        }
        catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    protected function countNumberOfDays($startDate, $startType, $endDate, $endType)
    {
        $startDate = new \DateTime($startDate);
        $endDate = new \DateTime($endDate);

        $diff = $startDate->diff($endDate);

        if ($startType === "AM" && $endType === "PM") {
            return $diff->days + 1;
        } else if ($startType === "AM" && $endType === "AM") {
            return $diff->days + 0.5;
        } else if ($startType === "PM" && $endType === "PM") {
            return $diff->days + 0.5;
        } else {
            return $diff->days - 1;
        }
    }
}
