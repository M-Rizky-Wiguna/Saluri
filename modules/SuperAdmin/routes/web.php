<?php

use Illuminate\Support\Facades\Route;
use Modules\SuperAdmin\Http\Controllers\JurusanController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'super.admin'])
    ->group(function () {
        Route::resource('jurusan', JurusanController::class);
    });
