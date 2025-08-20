# PHP+Nginx+Xdebug+Cron Docker Stack
A complete development stack with the latest PHP, Nginx, Xdebug, Supervisor, Cron, and comprehensive logging. Clean directory structure - `docker/` for configuration, `app/` for application code.
## Directory Structure
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
## Features
→ PHP 8.3-FPM with extensions and Xdebug (debugging, profiling)    
→ Nginx with full PHP support as a reverse proxy    
→ Supervisor manages all services (php-fpm, nginx, cron)    
→ Cron with a custom crontab, automatic logging, and a PHP test job    
→ Comprehensive logging for PHP, Nginx, Cron, Supervisor, and custom application logs    
→ IDE-friendly structure (PhpStorm, VSCode)
## Quick Start
1.  **Project Setup**
    ```bash
    git clone <repo-url> your-project-name
    cd your-project-name
    ```
2.  **Run the Stack**
    ```bash
    docker-compose up --build
    ```
    The application will be available at: `http://localhost:8080`
3.  **Xdebug Configuration in PhpStorm**
    *   Set debugger port: `9003`
    *   Add server: `localhost`, port `8080`
    *   IDE key: `PHPSTORM`
4.  **Accessing Logs**
    *   On the host machine: `logs/` directory
    *   Inside the container: `/var/log/app/` directory
    *   Via web browser: `http://localhost:8080/logs/`
    *   PHP log viewer: `app/logs.php`
5.  **Managing Cron Jobs**
    *   Edit the file: `docker/cron/crontab`
    *   Example job: `app/cron/example-cron-job.php`
## 🔧 Environment Features
### Development Mode (`start-dev.sh`)
- ✅ **Xdebug enabled** (port 9003)
- ✅ **All logging levels** (DEBUG, INFO, WARNING, ERROR)
- ✅ **Display errors ON**
- ✅ **Live code reloading** via volumes
- ✅ **Development PHP settings** (higher memory, timeouts)
### Production Mode (`start-prod.sh`)
- ✅ **No Xdebug** (faster performance)
- ✅ **WARNING+ logging only** (reduced noise)
- ✅ **Display errors OFF** (security)
- ✅ **Optimized PHP settings** (opcache, security)
- ✅ **Runs in background** (`-d` flag)
## 🌐 Access Points
### Development Environment
- **Web Application:** `http://localhost:8080`
- **Xdebug:** `localhost:9003`
- **Logs Viewer:** `http://localhost:8080/logs/`
### Production Environment
- **Web Application:** `http://localhost:80`
- **Xdebug:** Not available
- **Logs Viewer:** `http://localhost:80/logs/`
## 📊 Monitoring & Logs
### Log Files (available in `logs/` directory)
- `php_errors.log` - PHP errors and warnings
- `nginx-access.log` - HTTP access logs
- `nginx-error.log` - Nginx errors
- `cron-jobs.log` - Cron job outputs
- `application.log` - Custom application logs
### View Logs
#### Log Monitoring
```bash
# PHP logs
docker-compose exec web tail -f /var/log/app/php_errors.log
# Cron logs
docker-compose exec web tail -f /var/log/app/cron-jobs.log
# All supervisor logs (services' stdout/stderr)
docker-compose logs -f web
```
#### Cron Management
```bash
# Check crontab list
docker-compose exec web crontab -l
# Manually run a job
docker-compose exec web php /var/www/html/cron/example-cron-job.php
```
#### Debugging and Container Management
```bash
# Enter the container's shell
docker-compose exec web bash
# Check the status of processes managed by supervisor
docker-compose exec web supervisorctl status
```
### Available Logs
All logs are available in the `/var/log/app/` directory inside the container and in `logs/` on the host.
*   **System and Service Logs:**
    *   `php_errors.log`: PHP errors.
    *   `nginx-access.log`: Nginx access logs.
    *   `nginx-error.log`: Nginx errors.
    *   `supervisord.log`: Supervisor daemon main log.
    *   `php-fpm.log`: PHP-FPM process logs.
    *   `cron.log`: Cron daemon logs.
    *   `xdebug.log`: Xdebug debugging logs (if enabled).
*   **Cron Job Logs:**
    *   `cron-jobs.log`: Standard output of executed cron jobs.
    *   `custom-cron.log`: Example file for custom logs from PHP jobs.
### Customization
#### Adding PHP Extensions
Add the following to the `docker/Dockerfile`:
```dockerfile
RUN docker-php-ext-install extension_name
```
#### New Cron Jobs
1.  Add an entry to the `docker/cron/crontab` file.
2.  Create the corresponding script in `app/cron/`.
3.  Rebuild the container: `docker-compose up --build -d`.
#### PHP Configuration Changes
Edit the `docker/php/php.ini` or `docker/php/xdebug.ini` files and rebuild the container.
### Ports & Access
*   **HTTP:** `localhost:8080`
*   **Xdebug:** `localhost:9003`
*   **Logs in browser:** `http://localhost:8080/logs/`
### System Requirements
*   Docker Engine 20.10+
*   Docker Compose 2.0+
*   Minimum 2GB RAM (4GB recommended)
*   Compatible with Mac (M1/M2/M3), Linux, Windows (with WSL2)
### Troubleshooting
#### Xdebug Not Working
*   Check if port `9003` is available on the host machine.
*   Verify the server and debugger configuration in your IDE.
*   Check the Xdebug logs: `docker-compose exec web tail -f /var/log/app/xdebug.log`.
#### Cron Jobs Not Executing
*   Check the crontab syntax: `docker-compose exec web crontab -l`.
*   Check the job logs: `docker-compose exec web tail -f /var/log/app/cron-jobs.log`.
*   Ensure the PHP scripts have execute permissions.
#### File Permission Issues
If the application cannot write files (e.g., logs, cache), fix the permissions inside the container:
```bash
docker-compose exec web chown -R www-data:www-data /var/www/html
```
---
**Author:** Mariusz
*Created with Eva (Perplexity AI)*