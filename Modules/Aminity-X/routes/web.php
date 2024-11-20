<?php

use Illuminate\Support\Facades\Route;
use Modules\Aminity\app\Http\Controllers\AminityController;

Route::middleware(['auth:admin', 'translation'])
    ->name('admin.')
    ->prefix('admin')
    ->group(function () {
        Route::resource('listing-aminity', AminityController::class)->names('listing.aminity');
        Route::put('/listing-aminity/status-update/{id}', [AminityController::class, 'statusUpdate'])->name('listing.aminity.status-update');
    });
