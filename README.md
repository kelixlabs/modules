###Laravel 4.* Modules Package

This package makes laravel can implement HMVC or modular. 
With this package, you can create a web application that is structured and easier to manage a large web application. 

### Installation 
Open your composer.json file, and add the new required package. 

  ```
  "pingpong/modules": "dev-master" 
  ```

Next, open a terminal and run. 

  ```
  composer update 
  ```

After the composer updated to add new providers in `app/config/app.php`. 

  ```
  'Pingpong\Modules\ModulesServiceProvider' 
  ```

Finish. 

### Folder Structure

```
laravel/
|-- app
|-- bootstrap
|-- modules
    |-- blog
        |-- config
        |-- controllers
        |-- database
            |-- migrations
            |-- seeds
        |-- models
        |-- tests
        |-- views
        |-- filters.php
        |-- routes.php
|-- vendors
```

### Introduction 
After the installation is finished you will get a new features artisan. 

1. Creating new module.
2. Run migration from specified module.
3. Run database seeder from specified module.
4. Creating Controller
5. Creating migration

Note: Before creating a new module, run 

  ```
  php artisan module:setup
  ```
it will set the path and folder configuration module. 

### Artisan Command Line 
1. Create a new module. 

  ```
  php artisan module:make blog 
  ```
  
2. Creating Migration 
  Format: 
  `php artisan module:migrate-make <module-name> <table-name> --fields="<optional>"`
  ```
  php artisan module:migrate-make blog user --fields="username:string, password:string" 
  ```
3. Creating Controller
  
  ```
  php artisan module:controller-make blog Site 
  ```
  It will be created `SiteController` on blog module.
4. Running migration

  ```
  php artisan module:migrate blog 
  ```
5. Seeding database

  ```
  php artisan module:db-seed blog 
  ```
