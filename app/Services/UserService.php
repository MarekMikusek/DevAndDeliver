<?php

namespace App\Services;

use App\Helpers\ReturnData;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository, SwapiService $swapiService)
    {
        $this->userRepository = $userRepository;
        $this->swapiService = $swapiService;
    }

    public function register(array $user): array
    {
        $user['hero'] = $this->swapiService->getRandomHeroId();
        $user['password'] = Hash::make($user['password']);
        return ReturnData::create([
            'data' => $this->userRepository->create($user),
            'message' => 'user was created'
        ]);
    }

    public function updateEmail(string $newEmail): User
    {
        $user = User::find(Auth::user()->id);
        $user->email = $newEmail;
        return $user;
    }
}
