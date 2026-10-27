# Shop Management System

A web-based shop management system built with Laravel and PHP to manage customers, products, and orders.

## Overview

This project is a portfolio application developed to practice and demonstrate Laravel development skills, including MVC architecture, database relationships, form validation, and order management.

## Features

- Customer management and customer selection when creating orders
- Product and category management
- Order creation and order item management
- Product search by name or code
- Adding products to orders with specified quantities
- Automatic calculation of order item subtotals and total order amount
- Database transactions for order operations

## Tech Stack

- PHP
- Laravel
- MySQL / MariaDB
- Blade Templates
- HTML, CSS, and JavaScript
- Git and GitHub

## Project Structure

The application follows Laravel's MVC architecture:

- `app/Models`: Eloquent models and database relationships
- `app/Http/Controllers`: Application controllers and business logic
- `database/migrations`: Database schema definitions
- `database/seeders`: Database seeders
- `resources/views`: Blade templates and user interface
- `routes/web.php`: Web routes

## Installation

### Requirements

- PHP
- Composer
- MySQL or MariaDB

### Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/zakeihsoleimani/shopmanagement.git
   ```

2. Navigate to the project directory:

   ```bash
   cd shopmanagement
   ```

3. Install PHP dependencies:

   ```bash
   composer install
   ```

4. Create your environment file:

   ```bash
   cp .env.example .env
   ```

   On Windows, you can copy `.env.example` and rename the copy to `.env`.

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Create a database and configure the database connection in `.env`.

7. Run database migrations:

   ```bash
   php artisan migrate
   ```

8. Start the development server:

   ```bash
   php artisan serve
   ```

9. Open the local URL displayed in your terminal.

## Database

The application uses a relational database to store customers, products, categories, orders, and order items.

## Future Improvements

- Automated feature and unit tests
- Improved authorization and role-based access control
- Refactoring order business logic into dedicated service classes
- API endpoints for order and product management
- Automated order processing with queues

## Developer

**Zakeih Soleimani**

GitHub: [@zakeihsoleimani](https://github.com/zakeihsoleimani)

## License

This project is intended for learning and portfolio purposes.
