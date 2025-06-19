<?php

namespace Mks1209\StarterInstaller;

use Illuminate\Support\ServiceProvider;

class StarterInstallerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

    public function register()
    {
        // $this->mergeConfigFrom(__DIR__.'/config/starter-installer.php', 'starter-installer');
    }
}