<?php

namespace Mks1209\StarterInstaller;

use Illuminate\Support\ServiceProvider;

class StarterInstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        if($this->app->runningInConsole()){
            $this->commands([
                Mks1209\StarterInstaller\Console\InstallStarter::class,
            ]);
        }
    }

    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/config/starter-installer.php', 'starter-installer');
    }
}