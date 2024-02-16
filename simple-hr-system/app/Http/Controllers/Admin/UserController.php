<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Resources\UserDetailResource;
use App\Http\Resources\UserListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);
        $pageSize = $request->query("pageSize", 10);
        $nameFilter = $request->query("name");

        $query = User::filterName($nameFilter);

        return UserListResource::collection($query->paginate($pageSize));
    }

    public function store(UserStoreRequest $request)
    {
        $this->authorize("create", User::class);

        $validated = $request->validated();

        try {
            DB::beginTransaction();
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->birthday = $validated['birthday'];
            $user->geneder = $validated['geneder'];
            $user->on_board_date = $validated['on_board_date'];
            $user->is_active = $validated['is_active'];
            $user->email = $validated['email'];
            $user->exit_date = $validated['exit_date'];
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }

        return new UserDetailResource($user);
    }

    public function show(User $user) {
        $this->authorize("view", $user);
        return new UserDetailResource($user);
    }

    public function update(UserStoreRequest $request, User $user)
    {
        $this->authorize("update", $user);
        $validated = $request->validated();
        try {
            DB::beginTransaction();
            // only update the given fields
            foreach ($validated as $key => $value) {
                if($key == "password") {
                    $user->password = Hash::make($value);
                }
                else {
                    $user->{$key} = $value;
                }                
            }
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage()], 400);
        }

        return response()->noContent();
    }
}
