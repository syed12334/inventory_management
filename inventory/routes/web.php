<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\QrcodeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SubCategoryController;
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : view('login');
});
Route::post('loginUser', [LoginController::class, 'login'])->name('loginUser')->middleware('throttle:5,5');
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::controller(UserController::class)->group(function () {
        Route::get('users', 'index')->name('users');
        Route::post('storeUser', 'store')->name('storeUser');
        Route::post('userStatusChange', 'userStatus')->name('userStatusChange');
        Route::post('multipleDelete', 'deleteMultiple')->name('multipleDelete');
        Route::post('editUser', 'editUser')->name('editUser');
        Route::post('updateUser', 'update')->name('updateUser');
    });
    Route::prefix('warehouse')->name('warehouse.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::get('/edit/{id}', [WarehouseController::class, 'edit'])->name('edit');
        Route::post('/editUser', [WarehouseController::class, 'editwarehouse'])->name('editUser');
        Route::post('/updateWarehouse', [WarehouseController::class, 'update'])->name('updateWarehouse');
        Route::post('/store-warehouse-user', [WarehouseController::class, 'store'])->name('storewarehouseuser');
        Route::post('/multipleDelete', [WarehouseController::class, 'deleteMultiple'])->name('multipleDelete');
    });
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/editCategory', [CategoryController::class, 'edit'])->name('editCategory');
        Route::post('/storeCategory', [CategoryController::class, 'store'])->name('storeCategory');
        Route::post('/updateCategory', [CategoryController::class, 'update'])->name('updateCategory');
    });
    Route::prefix('subcategory')->name('subcategory.')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index'])->name('index');
        Route::post('/editCategory', [SubCategoryController::class, 'edit'])->name('editCategory');
        Route::post('/storeCategory', [SubCategoryController::class, 'store'])->name('storeCategory');
        Route::post('/updateCategory', [SubCategoryController::class, 'update'])->name('updateCategory');
    });
    Route::prefix('brand')->name('brand.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/edit', [BrandController::class, 'edit'])->name('edit');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::post('/update', [BrandController::class, 'update'])->name('update');
    });
    Route::prefix('color')->name('color.')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('index');
        Route::post('/edit', [ColorController::class, 'edit'])->name('edit');
        Route::post('/store', [ColorController::class, 'store'])->name('store');
        Route::post('/update', [ColorController::class, 'update'])->name('update');
    });
    Route::prefix('size')->name('size.')->group(function () {
        Route::get('/', [SizeController::class, 'index'])->name('index');
        Route::post('/edit', [SizeController::class, 'edit'])->name('edit');
        Route::post('/store', [SizeController::class, 'store'])->name('store');
        Route::post('/update', [SizeController::class, 'update'])->name('update');
    });
    Route::prefix('unit')->name('unit.')->group(function () {
        Route::get('/', [UnitController::class, 'index'])->name('index');
        Route::post('/edit', [UnitController::class, 'edit'])->name('edit');
        Route::post('/store', [UnitController::class, 'store'])->name('store');
        Route::post('/update', [UnitController::class, 'update'])->name('update');
    });
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', [StoreController::class, 'index'])->name('index');
        Route::post('/store-store-user', [WarehouseController::class, 'store'])->name('store-store-user');
    });
    Route::controller(RoleController::class)->group(function () {
        Route::get('roles', 'index')->name('roles');
        Route::post('storeRole', 'store')->name('storeRole');
        Route::post('editRole', 'edit')->name('editRole');
    });
    Route::get('qrcode',[QrcodeController::class,'index'])->name('qrcode');
});
Route::get('/phpinfo', function () {
    phpinfo();
});