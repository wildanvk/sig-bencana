<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tampilan', function () {
    return view('locations');
});
