<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $userService;
    protected $authService;

    public function __construct(
        AuthService $authService,
        UserService $userService,
        )
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

    public function signup(SignupRequest $request)
    {
        $userData = $request->validated();
        $result = $this->authService->signup($userData);
        return response($result, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials);

        if (!$result) {
            return response([
                'error' => 'Invalid email or password'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response($result, Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return response([
            'message' => 'Logged out',
        ], Response::HTTP_OK);
    }

    public function getLoggedInUserData(Request $request)
    {

        return response([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
        ], Response::HTTP_OK);
    }
}
