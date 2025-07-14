<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;

use function App\Config\registerRoutes;

//Tworzymy obiekt klasy App
$app = new App();

registerRoutes($app);

//Zwracamy obiekt app
return $app;
