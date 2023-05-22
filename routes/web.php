<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/file-import',[UserController::class,'listView'])->name('list.view');

Route::get('/import',[UserController::class,'importView'])->name('import');

Route::get('/import-users',[UserController::class,'import'])->name('import.user');

Route::get('/search',[UserController::class,'search'])->name('search');

Route::get('/delete{id?}',[UserController::class,'deleteAll'])->name('delete');

Route::get('/iteam{order_id}',[UserController::class,'iteam'])->name('iteam');
