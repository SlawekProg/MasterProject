<?php

declare(strict_types=1);

namespace Framework;

class App
{
    //Tworzymy zmienną router klasy Router
    private Router $router;

    //W konstruktorze klasy dla zmiennej router przypisujemy obiekt klasy Router
    public function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        //Odczytujemy adres z przeglądarki i wycinamy tylko ścieżkę
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        //Odczytujemy z adresu metode HTTP (GET lub POST lub ....)
        $method = $_SERVER['REQUEST_METHOD'];
        //Dla tego obiektu uruchamiamy metode dispatch klasy router przekazując odczytane parametry
        $this->router->dispatch($path, $method);
    }

    public function get(string $path, array $controller)
    {
        // Do otrzymanych parametrów dodajemy metode HTTP i
        //uruchamiamy w obiekcie router klasy Router metode dodawania trasy do listy routingu
        $this->router->add('GET', $path, $controller);
    }
}
