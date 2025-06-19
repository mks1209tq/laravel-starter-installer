<?php

namespace Mks1209tq\LaravelStarterInstaller;

use Illuminate\Support\ServiceProvider;
use Mks1209tq\LaravelStarterInstaller\Console\InstallStarter;

class StarterInstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallStarter::class, // ← now fully qualified
            ]);
        }
    }

    public function register()
    {
        // You can merge configs here in future if needed
    }
}