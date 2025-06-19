<?php

namespace Mks1209tq\LaravelStarterInstaller\Console;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class InstallStarter extends Command
{
    protected $signature = 'starter:install';
    protected $description = 'Automatically install Breeze and Spatie Permission into the Laravel project';

    public function handle()
    {
        $this->info('📦 Installing Laravel Breeze...');
        $this->runProcess(['composer', 'require', 'laravel/breeze', '--dev']);


        $this->call('breeze:install');

        $this->info('📦 Running npm install and build...');
        $this->runProcess(['npm', 'install']);
        $this->runProcess(['npm', 'run', 'build']);

        $this->info('📦 Installing Spatie Permission...');
        $this->runProcess(['composer', 'require', 'spatie/laravel-permission']);

        $this->call('vendor:publish', [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
            '--force' => true,
        ]);

        $this->info('📦 Updating autoloader to register commands...');
        $this->runProcess(['composer', 'dump-autoload']);

        $this->call('migrate');

        $this->info('✅ Starter packages installed successfully.');
    }

    protected function runProcess(array $command)
    {
        $process = new Process($command);
        $process->setTimeout(null); // disable timeout for large installs

        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if (!$process->isSuccessful()) {
            $this->error('❌ Command failed: ' . implode(' ', $command));
            exit(1);
        }
    }
}
