<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\OrganizationController;
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
//public routes
Route::prefix('admin')->name('admin.')->middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.submit');
});


//authenticated route
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
    Route::post('/logout',[DashboardController::class,'logout'])->name('logout');

    Route::resource('roles',RoleController::class);
    Route::post('/roles/list',[RoleController::class,'rolesList'])->name('roles.list');

    Route::get('/organizations/list',[OrganizationController::class,'organizationList'])->name('organizations.list');
    Route::resource('organizations',OrganizationController::class);
});
