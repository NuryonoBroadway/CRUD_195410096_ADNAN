<?php

use App\Http\Controllers\MstJabatanController;
use App\Http\Controllers\MstPegawaiController;
use App\Http\Controllers\RekamanUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::resource('/mst-jabatan', MstJabatanController::class);
Route::resource('/mst-pegawai', MstPegawaiController::class);
Route::resource('/user', RekamanUserController::class);
