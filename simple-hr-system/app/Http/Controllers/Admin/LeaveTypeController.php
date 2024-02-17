<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeaveType\LeaveTypeDetailResource;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    //
    public function index(Request $request)
    {
        $pageSize = $request->query("pageSize");
        $filterInUse = $request->query("inUse", "");
        $query = LeaveType::filterInUse($filterInUse);

        if (empty($pageSize)) {
            return LeaveTypeDetailResource::collection($query->get());
        } else {
            return LeaveTypeDetailResource::collection($query->paginate($pageSize));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "in_use" => "present|boolean"
        ]);

        try {
            DB::beginTransaction();
            $leaveType = LeaveType::create($validated);
            $leaveType->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }
        return new LeaveTypeDetailResource($leaveType);
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "in_use" => "present|boolean"
        ]);

        try {
            DB::beginTransaction();
            $leaveType->update($validated);
            $leaveType->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }
        return response()->noContent();
    }

    public function destroy(Request $request, LeaveType $leaveType)
    {
        // check any leave requests attached
        if ($leaveType->userLeaveRequests->count() > 0) {
            return response()->json(["message" => "User Leave Requests already exists."], 400);
        }

        try {
            DB::beginTransaction();
            $leaveType->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }
        return response()->noContent();
    }
}
