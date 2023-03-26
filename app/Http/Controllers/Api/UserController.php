<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService,
        )
    {
        $this->userService = $userService;
    }

    public function getUsers()
    {
        $users = $this->userService->getList();
        return response([
            'data' => $users,
        ], Response::HTTP_OK);
    }
}
