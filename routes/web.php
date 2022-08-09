<?php

use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [UserController::class, 'consultors']);
Route::get('earnings', [UserController::class, 'earnings']);
Route::get('netEarningsBar', [UserController::class,'netEarningsBar']);
Route::get('netEarningsPie', [UserController::class,'netEarningsPie']);
Route::get('cache', function(Request $request){
    Artisan::call('optimize');
    return '<h1>Application cached</h1>';
});
