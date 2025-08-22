<?php

// To jest plik demonstracyjny, który pokazuje, jak mógłby wyglądać Front Controller.
// W docelowej aplikacji, ten plik (lub jego zawartość) powinien znaleźć się w `app/public/index.php`,
// aby serwer Nginx mógł go poprawnie obsłużyć.

// Prosty router
// W tym przykładzie symulujemy żądanie, ponieważ plik nie jest bezpośrednio dostępny przez web.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

// Usuwamy ewentualne parametry GET
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Ładowanie kontrolerów i modeli
require_once __DIR__ . '/controllers/HomeController.php';
require_once __DIR__ . '/models/User.php';

// Routing
switch ($requestPath) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    // Tutaj możesz dodać więcej ścieżek w przyszłości
    // case '/users':
    //     require_once __DIR__ . '/controllers/UserController.php';
    //     $controller = new UserController();
    //     $controller->list();
    //     break;
    default:
        http_response_code(404);
        echo "<h2>404 Not Found</h2><p>Strona nie została znaleziona.</p>";
        break;
}