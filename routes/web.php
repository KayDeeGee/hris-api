<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/temp-dir', function () {
    return sys_get_temp_dir();
});
 