<?php

use App\Http\Controllers\MstJabatanController;
use App\Http\Controllers\MstPegawaiController;
use App\Http\Controllers\RekamanUserController;
use App\Http\Controllers\RiwayatPangkatController;
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
Route::resource('/riwayat-pangkat', RiwayatPangkatController::class);
Route::get('/riwayat-pangkat/proses/{id}', [RiwayatPangkatController::class, 'proses'])->name('riwayat-pangkat.proses');
Route::get('/riwayat-pangkat/cetak/{id}', [RiwayatPangkatController::class, 'cetak'])->name('riwayat-pangkat.cetak');
Route::get('/riwayat-pangkat/create/{id}', [RiwayatPangkatController::class, 'create'])->name('riwayat-pangkat.create');
