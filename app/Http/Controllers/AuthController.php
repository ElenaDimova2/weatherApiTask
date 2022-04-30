<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['message'=>'Invalid login credentials'], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 200);
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['data' => 'User logged out.'], 200);
    }
}
