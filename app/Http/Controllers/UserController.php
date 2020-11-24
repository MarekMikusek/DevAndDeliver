<?php

namespace App\Http\Controllers;

use App\Helpers\ReturnData;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function register(RegisterUserRequest $credentials): JsonResponse
    {
        return response()->json($this->userService->register($credentials->validated()));
    }

    public function updateEmail(UpdateUserRequest $request)
    {
        return ReturnData::create([
            'data' => [$this->userService->updateEmail($request->validated()['email']),
            'message' => 'user was updated']
        ]);
    }
}
