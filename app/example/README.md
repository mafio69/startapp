# Example Scripts and Configurations

This directory contains a collection of standalone examples and helper scripts to demonstrate various features of the Docker environment. These are separate from the more complex application pattern found in the `mvc-structure/` directory.

## Standalone Examples

These files can be placed in the `app/public/` directory to be accessed directly via a web browser.

*   `api-example.php`
    A simple script demonstrating how to create a basic JSON API endpoint. It shows how to set the correct `Content-Type` header and return data in JSON format.

*   `slim-demo.php`
    A basic example of an application using the [Slim Framework](https://www.slimframework.com/). To run this example, you first need to add Slim as a dependency using Composer:
    ```bash
    docker-compose exec web composer require slim/slim:"4.*" slim/psr7
    ```
    After installation, you can place this file in `app/public/` to see it in action.

*   `logs.php`
    A simple log viewer script that displays the contents of the `application.log` file. This provides a quick way to check application-specific logs from the browser.

*   `example-cron-job.php`
    This script is an example of a task designed to be run automatically by a cron job. You can see how it's configured by looking at `docker/cron/crontab`. It demonstrates how to write scripts for background processing.

## PhpStorm Xdebug Configuration Scripts

The files starting with `phpstorm_` are helper scripts provided by JetBrains to make configuring and validating Xdebug with the PhpStorm IDE much easier and faster.

*   `phpstorm_debug.php`
*   `phpstorm_index.php`
*   `phpstorm_debug_validator.phar`

### How to Use

To use these scripts, **you must place them in the `app/public/` directory**. The Nginx server serves files from this directory, making them accessible from your browser.

1.  **Copy the files:**
    ```bash
    cp app/example/phpstorm* app/public/
    ```

2.  **Validate your configuration:**
    Open your browser and navigate to `http://localhost:8080/phpstorm_debug.php` (or the validator script).

The script will analyze the connection from the Docker container to your IDE and provide instant feedback on your Xdebug configuration, helping you troubleshoot any issues.
