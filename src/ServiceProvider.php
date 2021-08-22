<?php

namespace Gridonic\StatamicConsentManager;

use Gridonic\StatamicConsentManager\Tags\ConsentManagerTags;
use Statamic\Facades\Config;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        ConsentManagerTags::class
    ];

    public function register()
    {
        $this->app->singleton(ConsentManager::class, function() {
            $config = Config::get('consent_manager', []);
            return ConsentManager::fromConfig($config);
        });
    }

    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'consent_manager');
    }

    private function publishConfig()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('consent_manager.php'),
            ], 'consent_manager');
        }
    }
}
