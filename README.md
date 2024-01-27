# Laravel Project

This is a Laravel project which demonstrates a robust web application using Laravel's features.

## Prerequisites

- PHP >= 8.0
- Composer
- MySQL 

## Installation Guide

1. **Clone the repository:**
git clone https://github.com/earqq/courses

2. **Navigate to the project directory:**
cd your-project-name

3. **Install Composer dependencies:**
composer install

4. **Set up your environment file:**
cp .env.example .env

5. **Then update .env with your database information.**
6. **Generate your application key:**
php artisan key:generate
7. **Run database migrations:**
php artisan migrate
8. **Run database seeders:**
php artisan db:seed

9. **Serve the application:**
php artisan serve

10. **You can now access the server at http://localhost:8000.**

## Usage

After installation, you are ready to use the application. run the test, and there is a route to add achivements to the user and validate the function of the events and listeners

- http://localhost:8000/add-achievements

**License**
This project is open-sourced software licensed under the MIT license.
