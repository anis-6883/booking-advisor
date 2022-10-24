<?php

use App\Http\Controllers\SuperAdmin\AuthController as SuperAdminAuthController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BedTypeController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomFacilityController;
use App\Http\Controllers\Admin\RoomImageController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\HotelController;
use App\Http\Controllers\SuperAdmin\PermissionController;
use App\Http\Controllers\SuperAdmin\RoleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/update-status', [AjaxController::class, 'updateStatus'])->name('update.status');

// Super Admin Routes
Route::prefix('super-admin')->name('super_admin.')->group(function () {

    Route::middleware(['guest:super_admin'])->group(function () {
        Route::match(['get', 'post'], '/login', [SuperAdminAuthController::class, 'login'])->name('login');
    });

    Route::middleware(['auth:super_admin'])->group(function () {
        Route::post('/logout', [SuperAdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('hotels', HotelController::class);
        Route::resource('admins', AdminController::class);
        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);
    });
    
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['guest:admin'])->group(function () {
        Route::match(['get', 'post'], '/login', [AdminAuthController::class, 'login'])->name('login');
    });

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::delete('/destroy/room-images', [RoomImageController::class, 'destroyAll'])->name('room.images.destroy.all');
        Route::resource('staffs', StaffController::class);
        Route::resource('room-settings/bed-types', BedTypeController::class);
        Route::resource('room-settings/room-facilities', RoomFacilityController::class);
        Route::resource('room-settings/rooms', RoomController::class);
        Route::resource('room-settings/room-images', RoomImageController::class);
    });
    
});
