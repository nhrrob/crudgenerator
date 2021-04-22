## NHRROB Crud Generator Package

<p align="left">
<a href="https://github.com/nhrrob/crudgenerator/stargazers"><img src="https://img.shields.io/github/stars/nhrrob/crudgenerator?style=flat-square" alt="Stars"></a>
[![Github All Releases](https://img.shields.io/github/downloads/nhrrob/crudgenerator/total.svg)]()

<a href="https://packagist.org/packages/nhrrob/crudgenerator/stats"><img src="https://img.shields.io/packagist/dt/nhrrob/crudgenerator.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/nhrrob/crudgenerator"><img src="https://img.shields.io/packagist/v/nhrrob/crudgenerator" alt="Latest Stable Version"></a>
<a href="https://github.com/nhrrob/crudgenerator/blob/master/LICENSE.md"><img alt="GitHub license" src="https://img.shields.io/github/license/nhrrob/crudgenerator"></a>
</p>

### This package provides an artisan command to generate a basic crud

composer install command: 
<code>composer require nhrrob/crudgenerator</code>

## 

### Crud Generator Commands
- install: <code>php artisan crud:generator</code>
- Migration: Add title field and run migration
   - add field: <code>$table->string('title');</code>
   - run migration: <code>php artisan migrate</code>

#### Note: 
This package creates resource route.
Example:
- Model title: Post
- Resource route: example.com/posts 

## 
#### Remove Crud Generated Files:
- <code>php artisan crud:generator:delete</code>
- Manually delete migration file and remove route from web.php


Feel free to contact:  
<a href="https://www.nazmulrobin.com/">nazmulrobin.com</a> | <a href="https://twitter.com/nhr_rob">Twitter</a> | <a href="https://www.linkedin.com/in/nhrrob/">Linkedin</a> | <a href="mailto:robin.sust08@gmail.com">Email</a>


## 
#### Bonus 
Laravel 8 auth using laravel/ui:
- <code>composer require laravel/ui</code>
- <code>php artisan ui bootstrap —auth</code>
- <code>npm install & nom run dev</code>
