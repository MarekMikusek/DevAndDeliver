<?php

namespace App\Services;

use App\Helpers\ReturnData;
use App\Repositories\UserRepository;

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
        return ReturnData::create(['data' => $this->userRepository->create($user), 'message' => 'user was created']);
    }
}
