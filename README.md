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