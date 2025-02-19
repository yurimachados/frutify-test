<?php

use App\Http\Controllers\CreateContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/contacts', CreateContactController::class);
