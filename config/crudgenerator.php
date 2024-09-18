<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Support
    |--------------------------------------------------------------------------
    |
    | Crud generator generates all the necessary files including API related files
    | If you think you don't need api files or your application doesn't require any
    | API support, feel free to disable (to keep your files short and simple).
    |
    */

    'api_support' => true,

    /*
    |--------------------------------------------------------------------------
    | API Authentication
    |--------------------------------------------------------------------------
    |
    | Crud generator typically adds files related to api integration (if api support
    | is enabled). That means whenever you generate a crud, it will also add support
    | for REST API for that crud. You can instantly use POSTMAN to check the api routes
    | Currently crud generator uses sanctum for authentication. If you want to use 
    | passport instead then feel free to update (replace sanctum with passport)  
    |
    */

    'api_auth' => 'sanctum', // or passport

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | Version number will be used for route prefix as well as parent folder for 
    | the api related files.
    | 
    | For api_version 1, route prefix and parent folder name will be like this:
    | route prefix:         v1 (e.x. http://siteurl/api/v1/products)
    | and parent folder:    V1 (e.x. app/Http/Controllers/Api/V1) 
    */

    'api_version' => 1, // 0 indicates no versioning. Example: 1 or 2 or 3 etc

];
