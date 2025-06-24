<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

/* User routes */
Route::controller(UserController::class)->group(function() {
    Route::get('users','index')->name('users');
    Route::post('storeUser','store')->name('storeUser');
    Route::post('userStatusChange','userStatus')->name('userStatusChange');
    Route::post('multipleDelete','deleteMultiple')->name('multipleDelete');
    Route::post('editUser','editUser')->name('editUser');
});
/* Category routes */
Route::controller(CategoryController::class)->group(function() {
    Route::get('category','index')->name('category');
    Route::post('storeCategory','storeCategory')->name('storeCategory');
    Route::get('categoryFac','categoryFac')->name('categoryFac');
});
/* Role Routes */
Route::controller(RoleController::class)->group(function() {
    Route::get('roles','index')->name('roles');
    Route::post('storeRole','store')->name('storeRole');
    Route::post('editRole','edit')->name('editRole');
});
