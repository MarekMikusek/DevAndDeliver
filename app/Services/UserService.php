<?php

namespace App\Services;

use App\Helpers\ReturnData;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $user): array
    {
        $user['hero'] = 1;
        $user['password'] = Hash::make($user['password']);
        return ReturnData::create([
            'data' => $this->userRepository->create($user),
            'message' => 'user was created'
        ]);
    }


}
