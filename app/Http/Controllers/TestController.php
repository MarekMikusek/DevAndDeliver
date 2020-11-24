<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function test()
    {
        Log::info('test');
        dd(User::all());
    }
}
