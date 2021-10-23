<?php

namespace Modules\Transaction\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TransactionProvider extends ServiceProvider
{
    protected string $namespace = 'Modules\Transaction\Controllers';

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('transaction')
            ->namespace($this->namespace)
            ->group(base_path(__DIR__  .  '../Routes/api.php'));
    }
}
