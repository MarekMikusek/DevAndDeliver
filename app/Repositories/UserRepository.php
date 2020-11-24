<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function create($user): string
    {
        return  User::create($user);
    }

    public function save($user)
    {
        return $user->save();
    }
}
