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

    public function update(UpdateUserRequest $request){
        // dd($request->validated());
        // if(!$user = $this->userService->update($request->validated()){
        //     return ReturnData::create(['message' => 'error occured', 'code' => 500]);
        // }
        return ReturnData::create([
            'data'=> [$this->userService->update($request->validated()),
            'message' => 'user was updated']]);
    }
}
