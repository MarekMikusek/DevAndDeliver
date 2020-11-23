<?php

namespace App\Http\Controllers;

use App\Helpers\ReturnData;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    protected $userService;
    protected $loginAfterSignUp = true;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterUserRequest $credentials): JsonResponse
    {
        return response()->json($this->userService->register($credentials->validated()));
    }

    public function login(LoginRequest $credentials): JsonResponse
    {
        if(!$token = JWTAuth::attempt($credentials->validated())){
            return response()->json(
                ReturnData::create(['code' => 401, 'error' =>'unathorized'])
            );

        }

        return response()->json(ReturnData::create([
            'code' => 200,
            'message' => 'successful login',
            'data' => ['token' => $token]
        ]));

    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate($request['token']);

            return response()->json([
                "code" => 200,
                "message" => "User logged out successfully"
            ]);
        } catch (JWTException $exception) {
            return response()->json(ReturnData::create([
                "code" => 500,
                "message" => "bad token or server error"
            ]));
        }
    }
}
