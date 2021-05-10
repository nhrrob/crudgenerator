<?php

namespace Nhrrob\Crudgenerator\Commands;

use App\Http\Traits\AllTraits;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'crudv3:generator 
    //                         {name? : Class (singular) for example User}
    //                         {--type=both : Institute type for example uni or non_uni or both}';

    protected $signature = 'crud:generator';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $version;
    protected $crudType;
    protected $name;
    protected $modelTitle, $modelTitlePlural, $modelTitleLower, $modelTitleLowerPlural,
        $modelCamel, $modelCamelPlural, $modelPascal, $modelPascalPlural,
        $modelKebab, $modelKebabPlural, $modelSnake, $modelSnakePlural;
    protected $templateArr1, $templateArr2;
    protected $finder;
    protected $modelFolder;

    protected $stubDirectoryPath;

    public function __construct(Filesystem $finder)
    {
        parent::__construct();
        $this->version = 'v1'; //it may change but command should not change. conmmand should not contain v1 or etc
        $this->crudType = 'normal';
        $this->finder = $finder;
        $this->modelFolder = app()->version() < 8 ? '' : '\Models';
        $this->stubDirectoryPath = File::exists(resource_path('stubs/vendor/crudgenerator/'))
        ? resource_path('stubs/vendor/crudgenerator/')
        : __DIR__ . '/../stubs/';
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
            '{{modelFolder}}',

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
            $this->modelFolder,
        ];

        $this->model();
        $this->controller();
        $this->request();
        $this->view();
        $this->migration();
        $this->route();
        
        //Api
        $this->apiResource();
        $this->apiController();
        $this->apiRoute();

        $this->info('CRUD successfully generated. Cheers!');
    }

    protected function getStub($type, $isApi=0)
    {
        $apiFolder = $isApi ? '/Api' : '';
        $parentFolder = $this->version . '/' . $this->crudType. $apiFolder;
        
        $path = "{$this->stubDirectoryPath}$parentFolder/$type.stub";
        return file_get_contents($path);
    }

    protected function getViewStubRoot($folderName, $type)
    {
        $parentFolder = $this->version . '/' . $this->crudType;

        $path =  "{$this->stubDirectoryPath}$parentFolder/$folderName/$type.stub";
        return file_get_contents($path);
    }

    protected function model()
    {
        //soft deletes : true or false : get it from config
        $modelTemplate = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getStub('Model')
        );

        //Version Check Code
        $modelFolder = app()->version() < 8 ? '' : '/Models'; //laravel 8 uses Models folder

        $modelPath = app_path("$modelFolder/{$this->modelPascal}.php");
        $this->validatePath($modelPath);

        file_put_contents($modelPath, $modelTemplate);
        $this->info('Model generated!');
    }

    protected function controller()
    {
        //spatie permissions on controller or view: get it from config
        $controllerTemplate = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getStub('Controller')
        );

        $controllerPath = app_path("/Http/Controllers/{$this->modelPascal}Controller.php");
        $this->validatePath($controllerPath);

        file_put_contents($controllerPath, $controllerTemplate);
        $this->info('Controller generated!');
    }

    protected function request()
    {
        $requestTemplate = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getStub('Request')
        );

        if (!file_exists($path = app_path('/Http/Requests')))
            mkdir($path, 0777, true);

        $requestPath = app_path("/Http/Requests/{$this->modelPascal}Request.php");
        $this->validatePath($requestPath);

        file_put_contents($requestPath, $requestTemplate);
        $this->info('Request generated!');
    }

    protected function view()
    {
        //spatie permissions on controller or view: get it from config

        $folderName = 'model_snake';

        $rtIndex = $this->dynamicGetStubRoot($folderName, 'index');
        $rtCreate = $this->dynamicGetStubRoot($folderName, 'create');
        $rtEdit = $this->dynamicGetStubRoot($folderName, 'edit');

        //view/backend or admin folder : get it from config
        if (!file_exists($path = resource_path('views/' . strtolower($this->modelSnake))))
            mkdir($path, 0777, true);

        $modellower = strtolower($this->modelSnake);

        $rtIndexPath = resource_path("views/{$modellower}/index.blade.php");
        $rtCreatePath = resource_path("views/{$modellower}/create.blade.php");
        $rtEditPath = resource_path("views/{$modellower}/edit.blade.php");

        $this->validatePath($rtIndexPath);
        $this->validatePath($rtCreatePath);
        $this->validatePath($rtEditPath);

        file_put_contents($rtIndexPath, $rtIndex);
        file_put_contents($rtCreatePath, $rtCreate);
        file_put_contents($rtEditPath, $rtEdit);
        $this->info('View files generated!');
    }

    protected function migration()
    {
        Artisan::call('make:migration create_' . strtolower(Str::plural($this->modelSnake)) . '_table --create=' . strtolower(Str::plural($this->modelSnake)));
        $this->info('Migration generated!');
    }

    protected function route()
    {
        //route group : prefix: admin or backend : get it from config

        //version check code : laravel 8 route needs whole controller path
        $controllerNamespace = app()->version() < 8 ? '' : "\\App\\Http\\Controllers\\";

        $path_to_file  = base_path('routes/web.php');
        $append_route = "\n\n" . "Route::group(['middleware' => 'auth'], function () { \n  Route::resource('$this->modelKebabPlural', '{$controllerNamespace}{$this->modelPascal}Controller'); \n});";

        File::append($path_to_file, $append_route);

        $this->info('Route generated!');
    }

    //Helper
    protected function validatePath($path)
    {
        if ($this->finder->exists($path) === true) {
            $this->error("This $path already exists!");
            return;
        }
        return;
    }

    public function dynamicGetStubRoot($folderName, $fileName)
    {
        //rT = requestTemplate
        $rtIndex = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getViewStubRoot($folderName, $fileName)
        );
        return $rtIndex;
    }

    //API Starts
    protected function apiResource()
    {
        $requestTemplate = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getStub('Resource', 1)
        );

        if (!file_exists($path = app_path('/Http/Resources')))
            mkdir($path, 0777, true);

        $resourcePath = app_path("/Http/Resources/{$this->modelPascal}Resource.php");
        $this->validatePath($resourcePath);

        file_put_contents($resourcePath, $requestTemplate);
        $this->info('Api: Resource generated!');
    }

    protected function apiController()
    {
        $controllerTemplate = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getStub('Controller',1)
        );

        $authControllerTemplate = str_replace(
            $this->templateArr1,
            $this->templateArr2,
            $this->getStub('AuthController',1)
        );

        if (!file_exists($path = app_path('/Http/Controllers/Api')))
            mkdir($path, 0777, true);

        $controllerPath = app_path("/Http/Controllers/Api/{$this->modelPascal}Controller.php");
        $this->validatePath($controllerPath);
        file_put_contents($controllerPath, $controllerTemplate);
        $this->info('Api: Controller generated!');

        
        $authControllerPath = app_path("/Http/Controllers/Api/AuthController.php");

        if ($this->finder->exists($authControllerPath) === false) {
            file_put_contents($authControllerPath, $authControllerTemplate);
            $this->info('Api: AuthController generated!');
        }

        $this->info('Note: Laravel Passport configuration => https://github.com/nhrrob/laravelwiki');
        $this->info('Note: Api auth routes => Browse above link');
        
    } 

    protected function apiRoute()
    {
        $controllerNamespace = app()->version() < 8 ? '' : "\\App\\Http\\Controllers\\Api\\";

        $path_to_file  = base_path('routes/api.php');
        $append_route = "\n\n" . "Route::group(['middleware' => ['auth:api']], function () { \n  Route::get('/{$this->modelKebabPlural}/search/{title}', '{$controllerNamespace}{$this->modelPascal}Controller@search'); \n  Route::apiResource('$this->modelKebabPlural', '{$controllerNamespace}{$this->modelPascal}Controller'); \n});";
        File::append($path_to_file, $append_route);

        $this->info('Route generated!');
    }

    //Api Ends
}
