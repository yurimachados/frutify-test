<?php

use App\Http\Controllers\Contacts\CreateContactController;
use App\Http\Controllers\Contacts\ContactController;
use App\Http\Controllers\Contacts\DeleteContactController;
use App\Http\Controllers\Contacts\UpdateContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Contact routes
Route::group(['prefix' => 'contacts', 'middleware' => 'throttle:60,1'], function () {
    Route::post('/', CreateContactController::class)->name('contacts.store');
    Route::put('/{id}', UpdateContactController::class)->name('contacts.update');
    Route::delete('/{contactId}', DeleteContactController::class)->name('contacts.destroy');

    Route::get('/', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('/create', [ContactController::class, 'create'])->name('contacts.create');
    Route::get('/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('/{id}/edit', [ContactController::class, 'edit'])->name('contacts.edit');
});

