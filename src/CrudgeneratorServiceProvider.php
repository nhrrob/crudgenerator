<?php

namespace Nhrrob\Crudgenerator;

use Illuminate\Support\ServiceProvider;

class CrudgeneratorServiceProvider extends ServiceProvider
{

    protected $defer = false;

    public function boot(){
        // $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        // $this->loadViewsFrom(__DIR__ . '/views', 'crudgenerator');
        // $this->loadMigrationsFrom(__DIR__ . '/database/migrations/');

        // $this->mergeConfigFrom(
        //     __DIR__ . '/config/crudgenerator.php',
        //     'crudgenerator'
        // );

        // $this->publishes([
        //     __DIR__ . '/config/crudgenerator.php' => config_path('crudgenerator.php'),
        //     __DIR__ . '/views' => resource_path('views/vendor/crudgenerator'),
        // ]);

        // $this->publishes([
        //     __DIR__ . '/../config/crudgenerator.php' => config_path('crudgenerator.php'),
        // ]);

        // $this->publishes([
        //     __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        // ]);

        // $this->publishes([
        //     __DIR__ . '/stubs/' => base_path('resources/crud-generator/'),
        // ]);
    }

    public function register(){
        $this->commands(
            'Nhrrob\Crudgenerator\Commands\CrudGenerator',
            'Nhrrob\Crudgenerator\Commands\CrudGeneratorDelete',
        );
    }
}