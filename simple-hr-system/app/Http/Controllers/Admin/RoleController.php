<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleDetailResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    //
    public function index(Request $request)
    {
        $pageSize = $request->query("pageSize");

        if (empty($pageSize)) {
            return RoleDetailResource::collection(Role::get());
        } else {
            return RoleDetailResource::collection(Role::paginate($pageSize));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255"
        ]);

        try {
            DB::beginTransaction();
            $role = Role::create($validated);
            $role->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }
        return new RoleDetailResource($role);
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255"
        ]);

        try {
            DB::beginTransaction();
            $role->update($validated);
            $role->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }
        return response()->noContent();
    }

    public function destroy(Request $request, Role $role)
    {
        try {
            DB::beginTransaction();
            $role->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }
        return response()->noContent();
    }
}
