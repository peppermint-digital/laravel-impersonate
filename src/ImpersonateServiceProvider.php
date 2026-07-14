<?php

namespace Peppermint\Impersonate;

use Illuminate\Support\ServiceProvider;

class ImpersonateServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/peppermint-impersonate.php',
            'peppermint-impersonate'
        );
    }

    public function boot(): void
    {
        if (config('peppermint-impersonate.register_routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/peppermint-impersonate.php' => config_path('peppermint-impersonate.php'),
            ], 'peppermint-impersonate-config');

            $this->publishes([
                __DIR__.'/../stubs/impersonation-banner.tsx' => resource_path('js/components/impersonation-banner.tsx'),
            ], 'peppermint-impersonate-react');

            $this->publishes([
                __DIR__.'/../stubs/ImpersonationBanner.vue' => resource_path('js/components/ImpersonationBanner.vue'),
            ], 'peppermint-impersonate-vue');
        }
    }
}
