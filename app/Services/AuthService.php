<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function __construct() {}

    public function signup(array $userData)
    {

        $userData['password'] = bcrypt($userData['password']);
        $user = User::create($userData);

        $token = $user->createToken('authToken')->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token
        ];
    }

    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            /** @var User $user */
            $token = $user->createToken('authToken')->plainTextToken;

            return [
                'user' => $user,
                'access_token' => $token
            ];
        }

        return null;
    }

    public function logout($request)
    {
        Auth::user()->currentAccessToken()->delete();
        return;
    }

}
