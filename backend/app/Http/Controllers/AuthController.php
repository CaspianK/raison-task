<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{

    public function signup(SignupRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->get('remember');

        if (!auth()->attempt($credentials, $remember)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = auth()->user()->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => auth()->user(),
            'token' => $token
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
