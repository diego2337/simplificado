<?php

declare(strict_types=1);

namespace Modules\Transaction\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Transaction\Console\EmailResendCommand;

class TransactionProvider extends ServiceProvider
{
    protected string $namespace = 'Modules\Transaction\Controllers';

    public function boot()
    {
        $this->registerConfigs();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerCommands();
    }

    public function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/client.php', 'client');
    }

    public function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '../Resources/views', 'transaction');
    }

    public function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/api.php');
    }

    public function registerCommands()
    {
        $this->commands([
            EmailResendCommand::class,
        ]);
    }
}
