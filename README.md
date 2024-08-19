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

## How to Start

To get started with the framework, follow these steps:

1. Set Up Environment Variables:
* Copy the example environment file to create your own `.env` file:
```
cp .env.example .env
```
* Review the `.env` file and update the settings as needed. Ensure the environment variables align with the 
configuration in your `docker-compose.yaml` file. You can adjust the values in either file to suit your setup.

2. Build and Start the Docker Environment:
* Use Docker Compose to build and start the development environment, which includes an Apache web server and 
a MySQL database:
```
docker-compose up --build
```

3. Run Migrations:
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

4. Access the Application:
* Once the containers are up and running, open your web browser and navigate to:
```
http://localhost:8080/gator/public/<route_name>
```
Replace <route_name> with the specific route you want to access.