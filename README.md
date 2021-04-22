# NHRROB Crud Generator Package

<p align="left">
<a href="https://github.com/nhrrob/crudgenerator-package/stargazers"><img src="https://img.shields.io/github/stars/nhrrob/crudgenerator-package?style=flat-square" alt="Stars"></a>
<a href="https://packagist.org/packages/nhrrob/crudgenerator"><img src="https://img.shields.io/packagist/dt/nhrrob/crudgenerator.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/nhrrob/crudgenerator"><img src="https://img.shields.io/packagist/v/nhrrob/crudgenerator" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/nhrrob/crudgenerator"><img src="https://img.shields.io/packagist/l/nhrrob/crudgenerator" alt="License"></a>
</p>


## This package provides an artisan command to generate a basic crud

composer install command: 
<code>composer require nhrrob/crudgenerator</code>


### Crud Generate Commands

#### Install Crud:
- install: <code>php artisan crud:generator</code>
- Migration: Add title field and run migration
   add field: <code>$table->string('title');</code>
   run migration: <code>php artisan migrate</code>

#### Delete Crud:
- <code>php artisan crud:generator:delete</code>
- Manually delete migration file and remove route from web.php


#### Note: 
This package creates resource route.
Example:
- Model title: Post
- Resource route: example.com/posts 

Feel free to contact: 
<a href="https://www.nazmulrobin.com/">nazmulrobin.com</a> | <a href="https://twitter.com/nhr_rob">Twitter</a> | <a href="https://www.linkedin.com/in/nhrrob/">Linkedin</a> | <a href="mailto:robin.sust08@gmail.com">Email</a>
