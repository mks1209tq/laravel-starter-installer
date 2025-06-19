<?php

namespace Mks1209tq\LaravelStarterInstaller\Console;

use Illuminate\Console\Command;

class InstallStarter extends Command
{
    protected $signature = 'starter:install';
    protected $description = 'Scaffold Breeze and Spatie Permission (after packages are installed)';

    public function handle()
    {
        if (!class_exists(\Laravel\Breeze\BreezeServiceProvider::class)) {
            $this->error('Laravel Breeze is not installed. Please run: composer require laravel/breeze --dev');
            return;
        }

        if (!class_exists(\Spatie\Permission\PermissionServiceProvider::class)) {
            $this->error('Spatie Permission is not installed. Please run: composer require spatie/laravel-permission');
            return;
        }

        $this->info('Running Laravel Breeze scaffolding...');
        $this->call('breeze:install');

        $this->info('Running npm install and build...');
        exec('npm install && npm run build');

        $this->info('Publishing Spatie Permission config...');
        $this->call('vendor:publish', [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
            '--force' => true,
        ]);

        $this->info('Running migrations...');
        $this->call('migrate');

        $this->info('✅ Starter packages configured successfully.');
    }
}
