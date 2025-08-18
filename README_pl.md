# PHP+Nginx+Xdebug+Cron Docker Stack

Kompletny stos deweloperski z najnowszym PHP, Nginx, Xdebug, Supervisor, Cron i kompleksowym logowaniem. Czysta struktura katalogów - `docker/` na konfigurację, `app/` na kod aplikacji.

## Struktura Katalogów

```
project/
├── docker/
│   ├── Dockerfile
│   ├── nginx/
│   ├── php/
│   ├── supervisor/
│   ├── cron/
│   └── logs/
├── app/
│   ├── cron/
│   └── (application code)
├── logs/
└── docker-compose.yml
```

## Funkcjonalności

→ PHP 8.3-FPM with extensions and Xdebug (debugging, profiling)    
→ Nginx with full PHP support as reverse proxy    
→ Supervisor manages all services (php-fpm, nginx, cron)    
→ Cron with custom crontab, automatic logging, PHP test job    
→ Comprehensive logging for PHP, Nginx, Cron, Supervisor and custom application logs    
→ IDE-friendly structure (PhpStorm, VSCode)    

## Szybki Start

1.  **Konfiguracja Projektu**
    ```bash
    git clone <repo-url> your-project-name
    cd your-project-name
    ```

2.  **Uruchomienie Stosu**
    ```bash
    docker-compose up --build
    ```
    Aplikacja będzie dostępna pod adresem: `http://localhost:8080`

3.  **Konfiguracja Xdebug w PhpStorm**
    *   Ustaw port debuggera: `9003`
    *   Dodaj serwer: `localhost`, port `8080`
    *   Klucz IDE (IDE key): `PHPSTORM`

4.  **Dostęp do Logów**
    *   Na maszynie hosta: katalog `logs/`
    *   Wewnątrz kontenera: katalog `/var/log/app/`
    *   Przez przeglądarkę: `http://localhost:8080/logs/`
    *   Przeglądarka logów PHP: `app/logs.php`

5.  **Zarządzanie Zadaniami Cron**
    *   Edytuj plik: `docker/cron/crontab`
    *   Przykładowe zadanie: `app/cron/example-cron-job.php`

### Podstawowe Komendy

#### Monitorowanie Logów
```bash
# Logi PHP
docker-compose exec web tail -f /var/log/app/php_errors.log

# Logi Cron
docker-compose exec web tail -f /var/log/app/cron-jobs.log

# Wszystkie logi supervisora (stdout/stderr usług)
docker-compose logs -f web
```

#### Zarządzanie Cronem
```bash
# Sprawdź listę zadań cron
docker-compose exec web crontab -l

# Ręczne uruchomienie zadania
docker-compose exec web php /var/www/html/cron/example-cron-job.php
```

#### Debugowanie i Zarządzanie Kontenerem
```bash
# Wejście do powłoki kontenera
docker-compose exec web bash

# Sprawdzenie statusu procesów zarządzanych przez supervisor
docker-compose exec web supervisorctl status
```

### Dostępne Logi

Wszystkie logi są dostępne w katalogu `/var/log/app/` wewnątrz kontenera oraz w `logs/` na hoście.

*   **Logi Systemowe i Usług:**
    *   `php_errors.log`: Błędy PHP.
    *   `nginx-access.log`: Logi dostępowe Nginx.
    *   `nginx-error.log`: Błędy Nginx.
    *   `supervisord.log`: Główny log demona supervisor.
    *   `php-fpm.log`: Logi procesu PHP-FPM.
    *   `cron.log`: Logi demona cron.
    *   `xdebug.log`: Logi debugowania Xdebug (jeśli włączone).
*   **Logi Zadań Cron:**
    *   `cron-jobs.log`: Standardowe wyjście wykonanych zadań cron.
    *   `custom-cron.log`: Przykładowy plik na niestandardowe logi z zadań PHP.

### Personalizacja

#### Dodawanie Rozszerzeń PHP
Dodaj w pliku `docker/Dockerfile`:
```dockerfile
RUN docker-php-ext-install nazwa_rozszerzenia
```

#### Nowe Zadania Cron
1.  Dodaj wpis w pliku `docker/cron/crontab`.
2.  Utwórz odpowiedni skrypt w `app/cron/`.
3.  Przebuduj kontener: `docker-compose up --build -d`.

#### Zmiany w Konfiguracji PHP
Edytuj pliki `docker/php/php.ini` lub `docker/php/xdebug.ini` i przebuduj kontener.

### Porty i Dostęp
*   **HTTP:** `localhost:8080`
*   **Xdebug:** `localhost:9003`
*   **Logi w przeglądarce:** `http://localhost:8080/logs/`

### Wymagania Systemowe
*   Docker Engine 20.10+
*   Docker Compose 2.0+
*   Minimum 2GB RAM (zalecane 4GB)
*   Kompatybilny z Mac (M1/M2/M3), Linux, Windows (z WSL2)

### Rozwiązywanie Problemów

#### Xdebug nie działa
*   Sprawdź, czy port `9003` jest wolny na maszynie hosta.
*   Zweryfikuj konfigurację serwera i debuggera w IDE.
*   Sprawdź logi Xdebug: `docker-compose exec web tail -f /var/log/app/xdebug.log`.

#### Zadania Cron nie wykonują się
*   Sprawdź składnię crontaba: `docker-compose exec web crontab -l`.
*   Sprawdź logi zadań: `docker-compose exec web tail -f /var/log/app/cron-jobs.log`.
*   Upewnij się, że skrypty PHP mają uprawnienia do wykonania.

#### Problemy z Uprawnieniami do Plików
Jeśli aplikacja nie może zapisywać plików (np. logów, cache), napraw uprawnienia wewnątrz kontenera:
```bash
docker-compose exec web chown -R www-data:www-data /var/www/html
```

---

**Author:** Eva AI Perplexity and Mariusz
