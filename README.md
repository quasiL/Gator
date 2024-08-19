# Gator PHP MVC Framework

Gator is a PHP MVC (Model-View-Controller) framework designed primarily for educational purposes. 
It offers a structured and organized approach to web development in PHP, making it a valuable tool for 
learning the fundamentals of web application architecture.

## Features Implemented

- [x] **MVC Core**: Developed the foundational components of the Model-View-Controller architecture.
- [x] **Routing System**: Routing System: A modern routing system that leverages PHP 8's attribute annotations 
for mapping URLs to controller actions. This approach eliminates the need for external route registration files, 
allowing you to define routes directly within your controller methods. For example:
```
#[Route('/about', 'GET')]
public function getAbout(HttpRequest $request, HttpResponse $response)
{
    $this->render('about', []);
}
```
- [x] **Query Builder**: Introducing *Burt*, a convenient and intuitive query builder for streamlined database 
interactions. With *Burt*, you can easily construct complex queries in a clean and readable manner. For example:
```
$users = Burt::table('users')
    ->select()
    ->where('status', '=', 1)
    ->getAll();
```
- [x] **Database Migrations**: Effortlessly manage your database schema changes with a built-in migrations system. 
Use simple commands to apply or rollback migrations:
```
php migrations.php migrate
php migrations.php rollback
```

## Upcoming Features

- [ ] **Dependency Injection**
- [ ] **Caching**
- [ ] **Validation**
- [ ] **Middleware Support**
- [ ] **Documentation**

## Prerequisites

Before you start using the framework, ensure you have the following prerequisites in place:

1. #### PHP Version 8:
* The framework requires PHP version 8. If you're not using Docker, make sure PHP is installed 
on your system.

2. #### MySQL Database:
*  MySQL database is needed for data storage. Ensure you have MySQL installed if you're not using Docker. 
The framework is configured to work with MySQL 8.0.

3. #### Apache Web Server:
* Apache web server is needed for the development environment. Ensure you have Apache installed if you're 
not using Docker.

4. #### Composer:
* Composer is a dependency manager for PHP, required to install and manage the framework's libraries. 
Install Composer globally on your system or use it within Docker if you're not managing dependencies manually.

5. #### Docker and Docker Compose (Optional but Recommended):
* If you prefer using Docker to manage your environment, ensure you have Docker and Docker Compose installed 
on your system. Docker will handle PHP, MySQL, and other dependencies within containers, providing 
a consistent development environment.

## How to Start

To get started with the framework, follow these steps:

1. Set Up Environment Variables:
* Copy the example environment file to create your own `.env` file:
```
cp .env.example .env
```
* Review the `.env` file and update the settings as needed. Ensure the environment variables align with the 
configuration in your `docker-compose.yaml` file. You can adjust the values in either file to suit your setup.

2. Install Dependencies:
* The framework requires several PHP libraries to function correctly. Install these dependencies using Composer:
```
composer install
```

3. Build and Start the Docker Environment:
* Use Docker Compose to build and start the development environment, which includes an Apache web server and 
a MySQL database:
```
docker-compose up --build
```

4. Run Migrations:
* To apply database migrations, you need to execute the migration commands inside the web server container:
```
docker-compose exec webserver bash
```
* Once inside the container, navigate to the gator directory:
```
cd gator
```
* Run the migrations to set up your database schema:
```
php migrations.php migrate
```

5. Access the Application:
* Once the containers are up and running, open your web browser and navigate to:
```
http://localhost:8080/gator/public/<route_name>
```
Replace <route_name> with the specific route you want to access.

## Required Libraries

* `vlucas/phpdotenv`:
This library is used to load environment variables from your .env file into the application. 
It helps manage configuration settings in a consistent way across different environments.

* `ext-pdo`:
PDO (PHP Data Objects) is a database access layer providing a uniform method of access to multiple databases. 
It is required for interacting with your database in a secure and consistent manner.

* `guzzlehttp/psr7`:
  Guzzle's PSR-7 implementation is used for creating and working with HTTP requests and responses according to 
PSR-7 (HTTP message interfaces) and PSR-12 (HTTP middleware specification). This ensures compatibility with other 
libraries and frameworks that adhere to these standards.