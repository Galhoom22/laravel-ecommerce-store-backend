# Laravel E-commerce Store (Backend)

A professional e-commerce backend built with Laravel 12, featuring clean architecture, SOLID principles, and comprehensive testing.

## 🎯 Features

-   User Authentication (Login & Register)
-   Role-based Authorization (Admin/Customer)
-   Product Management (CRUD with images)
-   Category Management (Hierarchical structure)
-   Shopping Cart (Guest + Authenticated users)
-   Order Management (Checkout + Order history)
-   Event-driven cart merge on login
-   Repository Pattern + Service Layer
-   Comprehensive test coverage (32 tests)

## 🛠️ Tech Stack

-   **Framework:** Laravel 12.x
-   **PHP:** 8.3+
-   **Database:** MySQL 8.0
-   **Authentication:** Custom Laravel Auth (manual implementation using controllers and Blade views)
-   **Authorization:** Spatie Laravel Permission
-   **Testing:** PHPUnit
-   **Frontend:** Blade + Bootstrap (Zay Shop template)

## 📦 Installation

### Prerequisites

-   PHP 8.3 or higher
-   Composer
-   MySQL 8.0 or higher
-   Node.js & NPM (for asset compilation)

### Setup Steps

1. **Clone the repository**

```bash
git clone https://github.com/Galhoom22/laravel-ecommerce-store-backend.git
cd laravel-ecommerce-store-backend
```

2. **Install dependencies**

```bash
composer install
npm install && npm run build
```

3. **Environment configuration**

```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
   Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_ecommerce
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations & seeders**

```bash
php artisan migrate --seed
```

6. **Start development server**

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 🔐 Default Credentials

### Admin Account

-   **Email:** admin@example.com
-   **Password:** password

### Customer Account

-   **Email:** customer@example.com
-   **Password:** password

## 🧪 Testing

Run all tests:

```bash
php artisan test
```

Run specific test suite:

```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

## 🏗️ Architecture

This project follows **Clean Architecture** principles:

```
Controller → Service → Repository → Model
```

### Design Patterns

-   **Repository Pattern:** Abstracts data access
-   **Service Layer:** Contains business logic
-   **Dependency Injection:** All dependencies via interfaces
-   **Event-Driven:** Cart merge on login event

### SOLID Principles

-   ✅ Single Responsibility Principle
-   ✅ Open/Closed Principle
-   ✅ Liskov Substitution Principle
-   ✅ Interface Segregation Principle
-   ✅ Dependency Inversion Principle

## 🔑 Key Features

### Shopping Cart

-   Guest cart (session-based)
-   User cart (database-backed)
-   Automatic merge on login
-   CRUD operations

### Order System

-   Checkout with shipping details
-   Order history
-   Order status tracking
-   Transaction safety with DB locks

### Authorization

-   Role-based access control
-   Admin can manage products/categories
-   Customers can only place orders

## 📘 API & Web Documentation

-   📬 **Postman Collection:** [Laravel-Ecommerce-Web-Routes.postman_collection.json](./docs/postman/Laravel-Ecommerce-Web-Routes.postman_collection.json)
-   🌐 Base URL: `{{base_url}}` → Default: `http://127.0.0.1:8000`
-   🧩 Import into Postman → Select Environment → **Laravel E-commerce Env**

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'feat: add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

## 👨‍💻 Author

Abdelrahman Galhoom - [GitHub Profile](https://github.com/Galhoom22)

## 🙏 Acknowledgments

-   Laravel Framework
-   Spatie Laravel Permission
-   Zay Shop Template by TemplateMo
