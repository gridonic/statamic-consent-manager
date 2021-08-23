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
            $config = Config::get('statamic.consent_manager', []);
            return ConsentManager::fromConfig($config);
        });
    }

    public function boot()
    {
        parent::boot();

        $this->publishConfig();
        $this->loadViews();
    }

    private function publishConfig()
    {
        if ($this->app->runningInConsole()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'statamic.consent_manager');
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('statamic/consent_manager.php'),
            ], 'statamic_consent_manager');
        }
    }

    private function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'statamic_consent_manager');
    }
}
