<?php

namespace JamesMosquera\FiscalColombia;

use Illuminate\Support\ServiceProvider;

class FiscalColombiaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/fiscal-colombia.php', 'fiscal-colombia');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'fiscal-colombia');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/fiscal-colombia.php' => config_path('fiscal-colombia.php'),
            ], 'fiscal-colombia-config');

            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/fiscal-colombia'),
            ], 'fiscal-colombia-views');
        }
    }
}
