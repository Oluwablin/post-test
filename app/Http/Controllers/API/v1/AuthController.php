<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthenticationService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->registerUser($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful.',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->loginUser($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'data' => $result['user'],
                'token' => $result['token'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null,
            ], 401);
        }
    }

    public function logout(): JsonResponse
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful.',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error during logout: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to logout.',
            ], 500);
        }
    }
}
