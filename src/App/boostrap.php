<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Config\Paths;

use function App\Config\{registerRoutes, registerMiddleware};

//Tworzymy obiekt klasy App
$app = new App(Paths::SOURCE . "app/container-definition.php");

//Rejestrujemy liste routingową
registerRoutes($app);
//Rejestrujemy liste middleware
registerMiddleware($app);

//Zwracamy obiekt app z załadowanymi listami
return $app;
