<?php

use Illuminate\Support\Facades\Route;
use Peppermint\Impersonate\Http\Controllers\ImpersonateController;

Route::middleware(config('peppermint-impersonate.route_middleware', ['web', 'auth']))->group(function () {
    Route::get('impersonate/take/{id}', [ImpersonateController::class, 'take'])
        ->name('impersonate.take');

    Route::get('impersonate/leave', [ImpersonateController::class, 'leave'])
        ->name('impersonate.leave');
});
