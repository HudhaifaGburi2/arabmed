<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SPA mounts
Route::view('/admin', 'admin');
Route::view('/student', 'student');
