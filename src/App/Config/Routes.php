<?php

declare(strict_types=1);

namespace App\Config;


use App\Controllers\{
    HomeController,
    AboutController,
    AuthController
};

//Uruchamiamy metode klasy dla obiektu app 
//przekazując lokalizacje i tablice z nazwą klasy i metody 
function registerRoutes($app)
{
    $app->get('/', [HomeController::class, 'home']);
    $app->get('/about', [AboutController::class, 'about']);
    $app->get('/register', [AuthController::class, 'registerView']);
    $app->post('/register', [AuthController::class, 'register']);
}
