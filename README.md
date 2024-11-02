## NHRROB Crud Generator Package

<p align="left">
<a href="https://github.com/nhrrob/crudgenerator/stargazers"><img src="https://img.shields.io/github/stars/nhrrob/crudgenerator?style=flat-square" alt="Stars"></a>
<a href="https://packagist.org/packages/nhrrob/crudgenerator"><img src="https://img.shields.io/packagist/dt/nhrrob/crudgenerator.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/nhrrob/crudgenerator"><img src="https://img.shields.io/packagist/v/nhrrob/crudgenerator" alt="Latest Stable Version"></a>
<a href="https://github.com/nhrrob/crudgenerator/blob/master/LICENSE.md"><img alt="GitHub license" src="https://img.shields.io/github/license/nhrrob/crudgenerator"></a>
</p>

### This package provides an artisan command to generate a basic crud

composer install command: 
```
composer require nhrrob/crudgenerator
```

## 

### Crud Generator Commands
- install: 

```
php artisan crud:generator
```
- If you want to keep backend files under Admin folder
<br>File Structure: Check below (Section => Bonus : Admin File Structure)
```
php artisan crud:generator --admin
```

- Migration: Add title field and run migration
   - add field: 
   ```
   $table->string('title');
   ```
   - run migration: 
   ```
   php artisan migrate
   ```

<br>


#### Note: 
- This package creates resource route.
   Example:
   - Model title: Post
   - Resource route: example.com/posts 
- If you want to use Api, make sure Sanctum(or, Passport) is installed.
   <br>Link: <a href="https://github.com/nhrrob/laravelwiki">https://github.com/nhrrob/laravelwiki</a> 

## 

#### Loom Videos: 
- Laravel 8 project installation with auth: 
<br>https://www.loom.com/share/681f186c6f61490f8e2df97cfc86afdd 

- Laravel Crud using nhrrob/crudgenerator: 
<br>https://www.loom.com/share/b860fb8c3ad2406fbd8661f2946f5cd7 

## 

#### Modify Stubs:
- Publish vendor files 
```
php artisan vendor:publish
```

## 

#### Remove Crud Generated Files:
- Delete Crud
```
php artisan crud:generator:delete
```
- If you have generated crud under Admin folder:
```
php artisan crud:generator:delete --admin
```

- Manually delete migration file and remove route from web.php

<br>

Feel free to contact:  
<a href="https://www.nazmulrobin.com/">nazmulrobin.com</a> | <a href="https://twitter.com/nhr_rob">Twitter</a> | <a href="https://www.linkedin.com/in/nhrrob/">Linkedin</a> | <a href="mailto:robin.sust08@gmail.com">Email</a>


## 

#### Bonus 
Laravel 8 auth using laravel/ui:
- 
```
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev
php artisan migrate
``` 

## 


#### Bonus : API 
API Helpline:
- <code>https://github.com/nhrrob/laravelwiki</code>
- <code>After refreshing database re create personal access token for passport</code>
```
php artisan passport:install
```

##

#### Bonus : Admin File Structure
- When you add --admin in crud generator commands => 
<br>It adds admin folder for views and Admin folder for controllers (including Api)

- Sample File/Folder Structure:

```
#Controllers
app/Http/Controllers/
app/Http/Controllers/Admin

#Views
resources/views/
resources/views/admin

------------------------------

#API Controllers
app/Http/Controllers/Api
app/Http/Controllers/Api/Admin
```

## V2.3.0
- Config file added
- Api versioning support added
- Api versions will be managed in dedicated version folder (e.x. app/Http/Controllers/Api/V1 and app/Http/Controllers/Api/V1/Admin)
- Now delete command will also delete migration file. You dont need to manually remove it.
