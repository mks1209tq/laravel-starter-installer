<?php

namespace Mks1209tq\LaravelStarterInstaller;

use Illuminate\Support\ServiceProvider;

class StarterInstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Mks1209tq\LaravelStarterInstaller\Console\InstallStarter::class,
            ]);
        }
    }

    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/config/starter-installer.php', 'starter-installer');
    }
}