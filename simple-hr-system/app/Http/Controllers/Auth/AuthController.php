<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(LoginRequest $request)
    {
        $request->authenticate();

        // generate sanctum token
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('api');

        return response()->json(["token" => $token, "expire_after" => config("sanctum.expiration")], 200);
    }

    public function profile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return new UserDetailResource($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
