<?php

namespace Mks1209\StarterInstaller\Console;

use Illuminate\Console\Command;

class InstallStarter extends Command
{
    protected $signature = 'starter:install';
    protected $description = 'Install starter packages like Breeze and Spatie Permission';

    public function handle()
    {
        $this->info('Installing Laravel Breeze...');
        exec('composer require laravel/breeze --dev');
        exec('php artisan breeze:install');
        exec('npm install && npm run build');

        $this->info('Installing Spatie Permission...');
        exec('composer require spatie/laravel-permission');
        exec('php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"');
        exec('php artisan migrate');

        $this->info('Starter packages installed successfully.');
    }
}
