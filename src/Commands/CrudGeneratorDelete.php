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

    protected $signature = 'crud:generator:delete
                            {--admin : If you want to delete a admin crud which has parent directory (Controllers and views) and prefix (routes) }
                            ';


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
    protected $api_version;
    protected $api_auth;
    protected $apiRouteMiddleware;
    
    protected $version;
    protected $crudType;
    protected $name;
    protected $adminCrud, $adminNamespace, $adminFolder, $adminPrefix, $adminRoutePrefix;

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
        $this->api_version = config('crudgenerator.api_version') ?? '1';
        $this->api_auth = config('crudgenerator.api_auth') ?? 'sanctum';
        $this->apiRouteMiddleware = $this->api_auth === 'sanctum' ? 'sanctum' : 'api';

        $this->version = $this->api_version ? "v{$this->api_version}" : ''; //it may change but command should not change. conmmand should not contain v1 or etc
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
        
        if (empty($this->name)) {
            $this->error("Model title is required!");
            return;
        }

        //Generate Variables
        $this->modelTitle = $this->name;
        $this->adminCrud = $this->option('admin');
        $this->adminNamespace = $this->adminCrud ? '\Admin' : ''; //For namespace
        $this->adminFolder = $this->adminCrud ? '/Admin' : '';  //For path
        $this->adminPrefix = $this->adminCrud ? 'admin' : '';  //For view name; also for blade files
        $this->adminRoutePrefix = $this->adminCrud ? 'admin.' : '';

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

        if ($this->confirm("Do you want to delete model, request and resource file as well") === false) {
            $this->info('Model, request and resource files are not deleted!');
        }else {
            //Admin and front common files: model, request, resource 
            $this->modelDelete();
            $this->requestDelete();
            $this->apiResourceDelete();
        }

        $this->controllerDelete();
        $this->viewDelete();
        $this->migrationDelete();

        //Api
        $this->apiControllerDelete();

        $this->info('CRUD files successfully deleted. No worries!');
        $this->info('Do not forget to delete route related codes on web.php and api.php!');
    }

    protected function modelDelete()
    {
        //Version Check Code
        $modelFolder = intval(app()->version()) < 8 ? '' : '/Models'; //laravel 8 uses Models folder
        $path = app_path("$modelFolder/{$this->modelPascal}.php");
        
        $validated = $this->validatePath($path);

        if ( $validated ) {
            $this->finder->delete($path);
            $this->info('Model deleted successfully!');
        }
    }

    protected function controllerDelete()
    {
        $path = app_path("/Http/Controllers{$this->adminFolder}/{$this->modelPascal}Controller.php");

        $validated = $this->validatePath($path);

        if ( $validated ) {
            $this->finder->delete($path);
            $this->info('Controller deleted successfully!');
        }
    }

    protected function requestDelete()
    {
        $path = app_path("/Http/Requests/{$this->modelPascal}Request.php");

        $validated = $this->validatePath($path);

        if ( $validated ) {
            $this->finder->delete($path);
            $this->info('Request deleted successfully!');
        }
    }

    protected function viewDelete()
    {
        $modelSnakeParent = $this->adminCrud ? '/admin' : '';

        $modellower = strtolower($this->modelSnake);
        $path = resource_path("views{$modelSnakeParent}/{$modellower}"); //view/backend or admin folder: get it from config

        $validated = $this->validatePath($path);

        if ( $validated ) {
            $this->finder->deleteDirectory($path);
            $this->info("views deleted successfully!");
        }
    }

    protected function migrationDelete()
    {
        $migrationFileName = $this->getMigrationFileName();

        if (empty($migrationFileName)) {
            $this->error("Migration file not found!");
            return false;
        }

        $path = database_path("migrations/{$migrationFileName}");

        $validated = $this->validatePath($path);

        if ($validated) {
            $this->finder->delete($path);
            $this->info("Migration file deleted successfully!");
        }
    }

    protected function getMigrationFileName()
    {
        $files = File::files(database_path('migrations'));
        foreach ($files as $file) {
            if (strpos($file->getFilename(), "create_{$this->modelSnakePlural}_table") !== false) {
                return $file->getFilename();
            }
        }

        return false;
    }

    protected function validatePath($path)
    {
        $coreFile = $this->protectCorePath($path);
        
        if ($coreFile === true) {
            // Core file found. So, invalid path!
            return false;
        }
        
        if ($this->finder->exists($path) === false) {
            $this->error("This $path does not exist!");
            return false;
        }
        return true;
    }

    protected function protectCorePath($path)
    {
        if (
            $path == app_path()
            || $path == app_path('/Http')
            || $path == app_path('/Http/Controllers/')
            || $path == app_path('/Http/Requests/')
            || $path == resource_path('views/')
            || $path == database_path('migrations/')
            // || $path == resource_path('views/backend/') //backend folder name: get it from config

        ) {
            $this->error("This $path is a core folder!");
            return true;
        }
        return false;
    }

    //Api
    protected function apiResourceDelete()
    {
        $path = app_path("/Http/Resources/{$this->modelPascal}Resource.php");

        $validated = $this->validatePath($path);
        
        if ( $validated ) {
            $this->finder->delete($path);
            $this->info('Api Resource deleted successfully!');
        }
    }

    protected function apiControllerDelete()
    {
        $apiVersion = ucfirst($this->version);
        $apiVersion = ! empty($apiVersion) ? '/' . $apiVersion : '';

        $path = app_path("/Http/Controllers/Api{$apiVersion}{$this->adminFolder}/{$this->modelPascal}Controller.php");

        $validated = $this->validatePath($path);

        if ( $validated ) {
            $this->finder->delete($path);
            $this->info('Api Controller deleted successfully!');
        }

    }
}
