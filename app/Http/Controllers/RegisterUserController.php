<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;

class RegisterUserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(RegisterUserRequest $credentials)
    {
        return response()->json($this->userService->register($credentials->validated()));
    }
}
