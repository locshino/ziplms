<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('welcome');

Route::get('/landing', function () {
    return view('landing');
});

if (method_exists(Route::class, 'passkeys')) {
    Route::passkeys();
}
