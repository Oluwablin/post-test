<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationService
{
    public function registerUser($data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Error during registration: ' . $e->getMessage());
            throw new \Exception('Registration failed. Please try again.');
        }
    }

    public function loginUser($data)
    {
        try {
            if (!$token = JWTAuth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
                throw new \Exception('Invalid login credentials.');
            }

            return [
                'user' => auth()->user(),
                'token' => $token,
            ];
        } catch (\Exception $e) {
            Log::error('Error during login: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}