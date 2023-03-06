<?php

namespace AUnhurian\LaravelTestGenerator;

use AUnhurian\LaravelTestGenerator\Console\GenerateTestCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class TestGeneratorProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $configPath = __DIR__ . '/../config/test-generator.php';
        $this->mergeConfigFrom($configPath, 'test-generator');
    }

    public function boot()
    {
        $configPath = __DIR__ . '/../config/test-generator.php';
        if (function_exists('config_path')) {
            $publishPath = config_path('test-generator.php');
        } else {
            $publishPath = base_path('config/test-generator.php');
        }

        $this->publishes([$configPath => $publishPath], 'test-generator');

        $stubPath = __DIR__ . '/../stubs/';
        $this->publishes([
            $stubPath => base_path('stubs/')
        ], 'test-generator');

        $this->app->singleton(
            'command.test.generate',
            function ($app) {
                return new GenerateTestCommand($app['files']);
            }
        );

        $this->commands(
            'command.test.generate',
        );
    }

    public function provides(): array
    {
        return ['command.test.generate'];
    }
}
