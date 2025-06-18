<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});


Route::controller(UserController::class)->group(function() {
    Route::get('users','index')->name('users');
    Route::get('login','login')->name('login');
    Route::post('store','store')->name('store');
    Route::get('adduser','adduser')->name('adduser');
});


Route::controller(CategoryController::class)->group(function() {
    Route::get('category','index')->name('category');
    Route::get('categoryFac','categoryFac')->name('categoryFac');
});
