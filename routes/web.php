<?php

use App\Http\Controllers\Contacts\CreateContactController;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Contacts\DeleteContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Contact routes
Route::group(['prefix' => 'contacts'], function () {
    Route::post('/', CreateContactController::class)->name('contacts.store');
    Route::delete('/{contactId}', DeleteContactController::class)->name('contacts.destroy');


    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::get('/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('/{contact}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
});

