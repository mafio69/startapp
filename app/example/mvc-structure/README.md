# Building an MVC Application with This Docker Environment

This guide explains how to use the provided Docker environment (PHP + Nginx) to build a simple web application based on the Model-View-Controller (MVC) architecture.

## Core MVC Concepts

The MVC architecture divides an application into three main components:

1.  **Model**: Represents the application's data and business logic. It's responsible for interacting with the database (reading, writing, updating). In our example, this is the `models/User.php` file.
2.  **View**: Responsible for presenting data to the user. This is the UI layer, usually in the form of HTML/PHP files. In our example, this is `views/home.php`.
3.  **Controller**: Acts as an intermediary between the Model and the View. It receives user requests, communicates with the model to retrieve or modify data, and finally passes that data to the appropriate view to be rendered. In our example, this is `controllers/HomeController.php`.

## How It Works in This Environment

The key element here is the **Front Controller Pattern**. Thanks to the Nginx server configuration in this project (`docker/nginx/default.conf`), all HTTP requests (that do not point to existing static files like `css` or `js`) are redirected to a single file: `app/public/index.php`.

This file (`index.php`) becomes the central entry point for the entire application. Its job is to:
1.  Analyze the request URL (e.g., `/users` or `/products/show/1`).
2.  Based on the URL, decide which **Controller** and which of its methods should be executed.
3.  Execute that method, which will process the request, use the **Model**, and finally load the appropriate **View**.

### Step 1: The Front Controller (`app/public/index.php`)

This is the heart of our application. We need to create a simple router that maps URLs to the corresponding controllers.

Example content for `app/public/index.php` that handles the `/` path:

```php
<?php

// Simple router
$requestUri = $_SERVER['REQUEST_URI'];

// Remove query string from URL
$requestPath = parse_url($requestUri, PHP_URL_PATH);

// Load controllers and models
require_once __DIR__ . '/../example/mvc-structure/controllers/HomeController.php';
require_once __DIR__ . '/../example/mvc-structure/models/User.php';

// Routing
switch ($requestPath) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    // You can add more routes here
    // case '/users':
    //     $controller = new UserController();
    //     $controller->list();
    //     break;
    default:
        http_response_code(404);
        echo "404 Not Found";
        break;
}
```

### Step 2: The Controller (`app/example/mvc-structure/controllers/HomeController.php`)

The `HomeController` is already set up. Its `index()` method creates an instance of the `User` model, gets data from it, and then loads the `home.php` view, passing the data to it.

```php
<?php

class HomeController {
    public function index() {
        // 1. Interact with the Model
        $user = new User("Guest");

        // 2. Pass data to the View
        // Define variables that will be accessible in the view
        $userName = $user->getName();
        $pageTitle = "Homepage";

        // 3. Load the view file
        // The view file will have access to the variables defined above
        require_once __DIR__ . '/../views/home.php';
    }
}
```

### Step 3: The Model (`app/example/mvc-structure/models/User.php`)

The `User` model is very simple. In a real application, it would connect to a database to fetch user data.

```php
<?php

class User {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}
```

### Step 4: The View (`app/example/mvc-structure/views/home.php`)

The `home.php` view is a simple PHP/HTML file that renders the data received from the controller.

```php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>
    <p>This is a simple MVC application running in a Docker environment.</p>
</body>
</html>
```

## How to Activate This MVC Structure

The web server's root directory is `app/public/`. To make this MVC example functional, you need to copy the directory structure (`controllers`, `models`, `views`) into the `app/` folder and create the main `index.php` file in `app/public/`.

### Step 1: Copy the MVC Directories

From your project's root directory (`/home/mariusz/projects/docker_start/`), run the following commands. This will copy the example structure into the main application directory.

```bash
# Copy the controllers
cp -r app/example/mvc-structure/controllers app/

# Copy the models
cp -r app/example/mvc-structure/models app/

# Copy the views
cp -r app/example/mvc-structure/views app/
```

### Step 2: Create the Front Controller

Now, you need to create the main entry point for the application. Create a file named `index.php` inside the `app/public/` directory.

The file `/home/mariusz/projects/docker_start/app/public/index.php` should have the following content. It sets up paths and routes the initial request to the `HomeController`.

```php
<?php

declare(strict_types=1);

// Define the application's root path, which is one level up from `public`
define('APP_PATH', __DIR__ . '/..');

// Load the required controller and model
require_once APP_PATH . '/controllers/HomeController.php';
require_once APP_PATH . '/models/User.php';

// Simple router based on the request URI
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route the request to the correct controller action
switch ($requestPath) {
    case '/':
        $controller = new HomeController();
        $controller->index();
        break;
    default:
        http_response_code(404);
        echo "<h1>404 Not Found</h1>";
        break;
}
```
*Note: The `HomeController` will correctly load the view because its path `require_once __DIR__ . '/../views/home.php'` will now resolve correctly from `app/controllers/` to `app/views/`.*

### Step 3: View in Browser

1.  If it's not already running, start the Docker environment: `docker-compose up -d --build`
2.  Open your web browser and navigate to `http://localhost:8080/`.

You should now see the welcome page, served through your new MVC structure.

## Next Steps

This example is very basic. To build a full-fledged application, you should:
*   **Add a database**: Extend the `docker-compose.yml` file with a database service (e.g., `mysql` or `postgres`) and connect to it in your models.
*   **Use Composer**: Utilize the `composer.json` file to manage dependencies. You can add a professional routing component (e.g., `nikic/fast-route`) or a template engine (e.g., `twig/twig`).
*   **Expand the structure**: Create more controllers, models, and views to handle the different features of your application.

This environment is an ideal starting point for any PHP project, and the MVC architecture will help you maintain clean and scalable code.