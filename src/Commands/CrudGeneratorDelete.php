<?php

namespace Nhrrob\Crudgenerator\Commands;

use App\Http\Traits\AllTraits;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class CrudGeneratorDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'crudv3:generator 
    //                         {name? : Class (singular) for example User}
    //                         {--type=both : Institute type for example uni or non_uni or both}';

    protected $signature = 'crud:generator:delete';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete CRUD generated files';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $version;
    protected $crudType;
    protected $name;
    protected $parent;
    protected $child;
    protected $modelTitle, $modelTitlePlural, $modelTitleLower, $modelTitleLowerPlural,
        $modelCamel, $modelCamelPlural, $modelPascal, $modelPascalPlural,
        $modelKebab, $modelKebabPlural, $modelSnake, $modelSnakePlural;

    protected $templateArr1, $templateArr2;
    protected $finder;

    public function __construct(Filesystem $finder)
    {
        parent::__construct();
        $this->version = 'v1';
        $this->crudType = 'normal';
        $this->finder = $finder;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle()
    // {
    //     return 0;
    // }

    public function handle()
    {
        $this->name = $this->ask('Model Title');

        //Generate Variables
        $this->modelTitle = $this->name;
        $this->modelTitlePlural = Str::plural($this->name);
        $this->modelTitleLower = Str::of($this->name)->lower();
        $this->modelTitleLowerPlural = Str::of($this->name)->lower()->plural();
        $this->modelCamel = Str::of($this->name)->camel();
        $this->modelCamelPlural = Str::of($this->name)->camel()->plural();
        $this->modelPascal = Str::of($this->name)->camel()->ucfirst();
        $this->modelPascalPlural = Str::of($this->name)->camel()->plural()->ucfirst();
        $this->modelKebab = Str::of($this->name)->kebab();
        $this->modelKebabPlural = Str::of($this->name)->kebab()->plural();
        $this->modelSnake = Str::of($this->name)->snake();
        $this->modelSnakePlural = Str::of($this->name)->snake()->plural();

        $this->templateArr1 = [
            '{{modelTitle}}',
            '{{modelTitlePlural}}',
            '{{modelTitleLower}}',
            '{{modelTitleLowerPlural}}',
            '{{modelCamel}}',
            '{{modelCamelPlural}}',
            '{{modelPascal}}',
            '{{modelPascalPlural}}',
            '{{modelKebab}}',
            '{{modelKebabPlural}}',
            '{{modelSnake}}',
            '{{modelSnakePlural}}',

        ];

        $this->templateArr2 = [
            $this->modelTitle,
            $this->modelTitlePlural,
            $this->modelTitleLower,
            $this->modelTitleLowerPlural,
            $this->modelCamel,
            $this->modelCamelPlural,
            $this->modelPascal,
            $this->modelPascalPlural,
            $this->modelKebab,
            $this->modelKebabPlural,
            $this->modelSnake,
            $this->modelSnakePlural,
        ];

        if ($this->confirm("Are you sure you wish to delete $this->name crud?") === false) {
            $this->info('Nothing was deleted');
            return;
        }

        $this->modelDelete();
        $this->controllerDelete();
        $this->requestDelete();
        $this->viewDelete();

        $this->info('CRUD files successfully deleted. No worries!');
        $this->info('Do not forget to delete your routes and migration file!');
    }

    protected function modelDelete()
    {
        //Version Check Code
        $modelFolder = app()->version() < 8 ? '' : '/Models'; //laravel 8 uses Models folder
        $path = app_path("$modelFolder/{$this->modelPascal}.php");

        $this->validatePath($path);
        $this->finder->delete($path);
    }

    protected function controllerDelete()
    {
        $path = app_path("/Http/Controllers/{$this->modelPascal}Controller.php");

        $this->validatePath($path);
        $this->finder->delete($path);
    }

    protected function requestDelete()
    {
        $path = app_path("/Http/Requests/{$this->modelPascal}Request.php");

        $this->validatePath($path);
        $this->finder->delete($path);
    }

    protected function viewDelete()
    {
        $modellower = strtolower($this->modelSnake);
        $path = resource_path("views/{$modellower}"); //view/backend or admin folder: get it from config

        $this->validatePath($path);
        $this->finder->deleteDirectory($path);
    }

    protected function validatePath($path)
    {
        $this->protectCorePath($path);
        if ($this->finder->exists($path) === false) {
            $this->error("This $path does not exist!");
            return;
        }
        return;
    }

    protected function protectCorePath($path)
    {
        if (
            $path == app_path()
            || $path == app_path('/Http')
            || $path == app_path('/Http/Controllers/')
            || $path == app_path('/Http/Requests/')
            || $path == resource_path('views/')
            // || $path == resource_path('views/backend/') //backend folder name: get it from config

        ) {
            $this->error("This $path is a core folder!");
            return;
        }
        return;
    }
}
