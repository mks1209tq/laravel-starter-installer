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
        $this->runProcess(['composer', 'require', 'laravel/breeze']);


        $this->info('📦 Dumping autoload after Breeze installation...');
        $this->runProcess(['composer', 'dump-autoload']);

        $this->info('📦 Refreshing Laravel commands...');
        \Artisan::call('clear-compiled');
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');

        $this->info('📦 Now installing Breeze scaffolding...');
        $this->call('breeze:install');


        $this->info('📦 Running npm install and build...');
        $this->runProcess(['npm', 'install']);
        $this->runProcess(['npm', 'run', 'build']);


        $this->info('📦 Installing Laravel Blueprint...');
        $this->runProcess(['composer', 'require', '-W', '--dev', 'laravel-shift/blueprint']);

        $this->info('📦 Dumping autoload for Blueprint...');
        $this->runProcess(['composer', 'dump-autoload']);

        $this->info('📦 Refreshing Laravel commands...');
        \Artisan::call('clear-compiled');
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');

        $this->info('📦 Publishing Laravel Blueprint...');
        $this->call('vendor:publish', [
            '--provider' => "Laravel\\Blueprint\\BlueprintServiceProvider",
            '--force' => true,
        ]);

        $this->info('📦 Installing Laravel Test Assertions...');
        $this->runProcess(['composer', 'require', '--dev', 'jasonmccreary/laravel-test-assertions']);

        $this->info('📦 Adding .gitignore entries for Blueprint...');
        file_put_contents(base_path('.gitignore'), "/draft.yaml\n", FILE_APPEND);
        file_put_contents(base_path('.gitignore'), "/.blueprint\n", FILE_APPEND);

        // ✅ Manually register provider
        if (!app()->getProvider(\Laravel\Blueprint\BlueprintServiceProvider::class)) {
            app()->register(\Laravel\Blueprint\BlueprintServiceProvider::class);
        }

        // ✅ Check if command exists
        if (\Artisan::has('blueprint:new')) {
            $this->info('📦 Running Blueprint:new...');
            $this->call('blueprint:new');
        } else {
            $this->warn('⚠️ blueprint:new command not found. Skipping.');
        }



        $this->info('📦 Installing Spatie Permission...');
        $this->runProcess(['composer', 'require', 'spatie/laravel-permission']);

        $this->call('vendor:publish', [
            '--provider' => "Spatie\Permission\PermissionServiceProvider",
            '--force' => true,
        ]);

        $this->info('📦 Installing Laravel Sanctum...');
        $this->runProcess(['composer', 'require', 'laravel/sanctum']);

        $this->call('vendor:publish', [
            '--provider' => "Laravel\Sanctum\SanctumServiceProvider",
            '--force' => true,
        ]);

        $this->info('📦 Installing Laravel Flysystem S3...');
        $this->runProcess(['composer', 'require', 'league/flysystem-aws-s3-v3', '--with-all-dependencies']);





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