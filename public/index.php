<?php

include __DIR__ . '/../src/App/functions.php';

//Uruchamiamy bootsrap.php i przypisujemy to co zwróc do $app (obiekt klasy App w tym przypadku)
$app = include __DIR__ . '/../src/App/boostrap.php';
//Na zwróconym obiekcie uruchamiamy metode run
$app->run();
