<?php

require_once __DIR__ . '/../models/User.php';

class HomeController
{
    public function index()
    {
        // 1. Pobierz dane z modelu
        $userModel = new User();
        $users = $userModel->getAllUsers();
        $pageTitle = "Strona główna";

        // 2. Załaduj widok i przekaż mu dane
        // W prawdziwej aplikacji użylibyśmy systemu szablonów (np. Twig)
        $viewPath = __DIR__ . '/../views/home.php';
        if (file_exists($viewPath)) {
            // Udostępnij zmienne dla widoku
            extract(compact('users', 'pageTitle'));

            require $viewPath;
        } else {
            echo "Błąd: Nie znaleziono pliku widoku!";
        }
    }
}
