<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StoreController;

Route::get('/', function () {
    return view('login');
});

/* User routes */
Route::controller(UserController::class)->group(function() {
    Route::get('users','index')->name('users');
    Route::post('storeUser','store')->name('storeUser');
    Route::post('userStatusChange','userStatus')->name('userStatusChange');
    Route::post('multipleDelete','deleteMultiple')->name('multipleDelete');
    Route::post('editUser','editUser')->name('editUser');
    Route::post('updateUser','update')->name('updateUser');
});
     
Route::prefix('warehouse')->name('warehouse.')->group(function () {
    Route::get('/', [WarehouseController::class, 'index'])->name('index');
    Route::get('/edit/{id}', [WarehouseController::class, 'edit'])->name('edit');
    Route::post('/editUser', [WarehouseController::class, 'editwarehouse'])->name('editUser');
    Route::post('/updateWarehouse',[WarehouseController::class, 'update'])->name('updateWarehouse');
    Route::post('/store-warehouse-user', [WarehouseController::class, 'store'])->name('storewarehouseuser');
    Route::post('/multipleDelete',[WarehouseController::class,'deleteMultiple'])->name('multipleDelete');
});

Route::prefix('category')->name('category.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::post('/editCategory', [CategoryController::class, 'edit'])->name('editCategory');
    Route::post('/storeCategory', [CategoryController::class, 'store'])->name('storeCategory');
    Route::post('/updateCategory',[CategoryController::class, 'update'])->name('updateCategory');
});

Route::prefix('store')->name('store.')->group(function () {
    Route::get('/', [StoreController::class, 'index'])->name('index');
    Route::post('/store-store-user', [WarehouseController::class, 'store'])->name('store-store-user');
});

/* Login Routes */
Route::controller(LoginController::class)->group(function() {
    Route::post('loginUser','login')->name('loginUser')->middleware('throttle:5,5');
    Route::get('logout','logout')->name('logout');
});
/* Login Home */
  Route::get('/',function() {
    if (Auth::check()) {
        return redirect('/dashboard'); 
    }
    return view('login');
  });


/* Category routes */
/*Route::controller(CategoryController::class)->group(function() {
    Route::get('category','index')->name('category');
    Route::post('storeCategory','storeCategory')->name('storeCategory');
    Route::get('categoryFac','categoryFac')->name('categoryFac');
});*/
/* Role Routes */
Route::controller(RoleController::class)->group(function() {
    Route::get('roles','index')->name('roles');
    Route::post('storeRole','store')->name('storeRole');
    Route::post('editRole','edit')->name('editRole');
});
