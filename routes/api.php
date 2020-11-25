<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/register", "UserController@register");
Route::post("/login", "AuthController@login");

Route::group(["middleware" => "auth.jwt"], function () {
    Route::post("/logout", "AuthController@logout");
    Route::patch("/update-email", "UserController@updateEmail");
    Route::get("/hero/{resource}", "ResourceController@heroResources");
    Route::get("/resources/{resource}/{id}", "ResourceController@resources");
});
