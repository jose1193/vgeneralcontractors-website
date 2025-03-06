<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Users;
use App\Livewire\EmailDatas;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/users', Users::class)->name('users');
    Route::get('/email-datas', EmailDatas::class)->name('email-datas');
});
