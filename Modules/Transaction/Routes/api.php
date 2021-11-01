<?php

use Illuminate\Support\Facades\Route;
use Modules\Transaction\Http\Controllers\TransactionController;

Route::prefix('transaction')
    ->group(function () {
        Route::post('/', [TransactionController::class, 'transaction']);
    });
