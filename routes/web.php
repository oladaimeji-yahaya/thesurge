<?php
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */


//Load web route files
$routeGroup = basename(__FILE__, '.php');
$files = scandir(__DIR__ . DS . $routeGroup);
foreach ($files as $file) {
    if ($file === '.' || $file === '..' || $file === 'app.php' || !ends_with($file, '.php')) {
        continue;
    }
    
    require(__DIR__ . DS . $routeGroup . DS . $file);
}

//Load the app routes last to avoid "catch all" routes from catching all :)
require __DIR__ . '/web/app.php';
