# QuickBite Backend API

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)

**A robust, scalable RESTful API backend for an e-commerce food delivery platform built with Laravel 12**

[Features](#-features) • [Installation](#-installation) • [API Documentation](#-api-documentation) • [Configuration](#-configuration) • [Testing](#-testing)

</div>

---

## 📋 Table of Contents

-   [Overview](#-overview)
-   [Features](#-features)
-   [Tech Stack](#-tech-stack)
-   [Prerequisites](#-prerequisites)
-   [Installation](#-installation)
-   [Configuration](#-configuration)
-   [API Documentation](#-api-documentation)
-   [Database Schema](#-database-schema)
-   [Authentication](#-authentication)
-   [Testing](#-testing)
-   [Development](#-development)
-   [Deployment](#-deployment)
-   [Security](#-security)
-   [Contributing](#-contributing)
-   [License](#-license)

---

## 🎯 Overview

QuickBite is a comprehensive e-commerce backend API designed for food delivery and restaurant management. It provides a complete solution for managing products, orders, users, payments, and customer interactions through a well-structured RESTful API.

### Key Highlights

-   🔐 **Secure Authentication** - Laravel Sanctum token-based authentication
-   🛒 **Shopping Cart System** - Full cart management with persistent storage
-   💳 **Payment Integration** - Stripe payment gateway integration
-   📧 **Email Services** - Brevo API integration for transactional emails
-   🖼️ **Image Management** - Cloudinary integration for product images
-   🛡️ **Authorization** - Role-based access control with Laravel Policies
-   📝 **Reviews & Comments** - Product review and rating system
-   🧪 **Testing** - Comprehensive test suite with Pest PHP

---

## ✨ Features

### Core Functionality

-   **User Management**

    -   User registration and authentication
    -   Role-based access control
    -   Profile management
    -   Secure password hashing

-   **Product Management**

    -   CRUD operations for products
    -   Category-based organization
    -   Product attributes (brand, type, size, color)
    -   Stock management
    -   Image upload and management via Cloudinary

-   **Shopping Experience**

    -   Shopping cart management
    -   Add/remove/update cart items
    -   Persistent cart storage

-   **Order Management**

    -   Order creation and tracking
    -   Order status management
    -   Order history
    -   Order items tracking

-   **Payment Processing**

    -   Stripe payment integration
    -   Secure payment processing
    -   Transaction management

-   **Customer Engagement**

    -   Product reviews and comments
    -   Contact form submission
    -   Newsletter subscription
    -   Email notifications via Brevo

-   **API Features**
    -   RESTful API design
    -   JSON responses
    -   Request validation
    -   Error handling
    -   API resource transformations

---

## 🛠️ Tech Stack

### Backend Framework

-   **Laravel 12.x** - Modern PHP framework
-   **PHP 8.2+** - Latest PHP features

### Authentication & Security

-   **Laravel Sanctum** - API token authentication
-   **Laravel Breeze** - Authentication scaffolding
-   **Laravel Policies** - Authorization system

### Third-Party Integrations

-   **Stripe** - Payment processing
-   **Brevo (formerly Sendinblue)** - Email service API
-   **Cloudinary** - Image storage and management

### Development Tools

-   **Pest PHP** - Testing framework
-   **Laravel Pint** - Code style fixer
-   **Laravel Pail** - Log viewer
-   **Laravel Sail** - Docker development environment

### Database

-   Supports MySQL, PostgreSQL, SQLite, and SQL Server

---

## 📦 Prerequisites

Before you begin, ensure you have the following installed:

-   **PHP** >= 8.2
-   **Composer** >= 2.0
-   **Database** (MySQL/PostgreSQL/SQLite)

### Optional

-   **Docker** and **Docker Compose** (for Laravel Sail)
-   **Stripe Account** (for payment processing)
-   **Brevo Account** (for email services)
-   **Cloudinary Account** (for image storage)

---

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/QuickBite-BackEnd.git
cd QuickBite-BackEnd
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment Variables

Edit the `.env` file with your configuration (see [Configuration](#-configuration) section).

### 5. Database Setup

```bash
php artisan migrate
php artisan db:seed  # Optional: Seed sample data
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Start Development Server

```bash
# Using Laravel's built-in server
php artisan serve

# Or using the dev script (includes queue, logs, and vite)
composer run dev
```

The API will be available at `http://localhost:8000`

---

## ⚙️ Configuration

### Environment Variables

Create a `.env` file in the root directory and configure the following:

#### Application

```env
APP_NAME="QuickBite"
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000
```

#### Database

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quickbite
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

#### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_FROM_ADDRESS=noreply@quickbite.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### Brevo API (Email Service)

```env
BREVO_API_KEY=your_brevo_api_key_here
ADMIN_EMAIL=admin@quickbite.com
```

#### Stripe (Payment Processing)

```env
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
```

#### Cloudinary (Image Storage)

```env
CLOUDINARY_URL=cloudinary://api_key:api_secret@cloud_name
```

#### Session & Cache

```env
SESSION_DRIVER=database
CACHE_DRIVER=file
QUEUE_CONNECTION=database
```

### Getting API Keys

#### Brevo API Key

1. Sign up at [Brevo](https://www.brevo.com/)
2. Navigate to **SMTP & API** section
3. Generate a new API key (v3)
4. Copy the key to `BREVO_API_KEY` in `.env`

#### Stripe Keys

1. Sign up at [Stripe](https://stripe.com/)
2. Navigate to **Developers** > **API keys**
3. Copy your **Publishable key** and **Secret key**
4. Add them to `STRIPE_KEY` and `STRIPE_SECRET` in `.env`

#### Cloudinary

1. Sign up at [Cloudinary](https://cloudinary.com/)
2. Copy your **Cloudinary URL** from the dashboard
3. Add it to `CLOUDINARY_URL` in `.env`

---

## 📚 API Documentation

### Base URL

```
http://localhost:8000/api
```

### Authentication

Most endpoints require authentication using Bearer tokens. Include the token in the Authorization header:

```
Authorization: Bearer {your_token_here}
```

### Endpoints Overview

#### 🔓 Public Endpoints

##### Authentication

-   `POST /api/register` - Register a new user
-   `POST /api/login` - Login and get access token

##### Products

-   `GET /api/products` - List all products
-   `GET /api/products/{id}` - Get product details

##### Categories

-   `GET /api/categories` - List all categories
-   `GET /api/categories/{id}` - Get category details

##### Comments

-   `GET /api/commentaires` - List all comments
-   `GET /api/commentaires/{id}` - Get comment details

##### Contact & Newsletter

-   `POST /api/contact/send` - Send contact form message
-   `POST /api/contact/subscribe` - Subscribe to newsletter

#### 🔒 Authenticated Endpoints

##### User Management

-   `GET /api/user` - Get current authenticated user
-   `POST /api/logout` - Logout current user
-   `GET /api/users` - List all users (Admin)
-   `POST /api/users` - Create user (Admin)
-   `GET /api/users/{id}` - Get user details
-   `PUT /api/users/{id}` - Update user
-   `DELETE /api/users/{id}` - Delete user

##### Products (Admin)

-   `POST /api/products` - Create product
-   `PUT /api/products/{id}` - Update product
-   `DELETE /api/products/{id}` - Delete product

##### Categories (Admin)

-   `POST /api/categories` - Create category
-   `PUT /api/categories/{id}` - Update category
-   `DELETE /api/categories/{id}` - Delete category

##### Shopping Cart

-   `GET /api/panier` - Get user's cart
-   `POST /api/panier` - Add item to cart
-   `PUT /api/panier/{id}` - Update cart item
-   `DELETE /api/panier/{id}` - Remove cart item

##### Orders

-   `GET /api/commandes` - Get user's orders
-   `POST /api/commandes` - Create new order
-   `GET /api/commandes/{id}` - Get order details

##### Comments

-   `POST /api/commentaires` - Create comment
-   `PUT /api/commentaires/{id}` - Update comment
-   `DELETE /api/commentaires/{id}` - Delete comment

##### Payments

-   `POST /api/stripe/pay` - Process Stripe payment

### Request/Response Examples

#### Register User

```http
POST /api/register
Content-Type: application/json

{
  "username": "johndoe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "adresse": "123 Main St"
}
```

**Response:**

```json
{
    "message": "User created.",
    "user": {
        "userId": 1,
        "username": "johndoe",
        "email": "john@example.com",
        "adresse": "123 Main St",
        "role": "user"
    },
    "token": "1|randomtokenstring..."
}
```

#### Login

```http
POST /api/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**

```json
{
    "message": "Logged In Successfuly",
    "user": {
        "userId": 1,
        "username": "johndoe",
        "email": "john@example.com"
    },
    "token": "2|randomtokenstring..."
}
```

#### Create Product (Authenticated)

```http
POST /api/products
Authorization: Bearer {token}
Content-Type: application/json

{
  "nom": "Delicious Burger",
  "description": "A mouth-watering burger",
  "prix": 12.99,
  "stock": 50,
  "categoryId": 1,
  "brand": "QuickBite",
  "type": "Burger",
  "size": "Large",
  "color": "Brown"
}
```

#### Add to Cart

```http
POST /api/panier
Authorization: Bearer {token}
Content-Type: application/json

{
  "produitId": 1,
  "quantite": 2
}
```

#### Process Payment

```http
POST /api/stripe/pay
Authorization: Bearer {token}
Content-Type: application/json

{
  "amount": 25.98,
  "currency": "usd",
  "payment_method_id": "pm_card_visa"
}
```

---

## 🗄️ Database Schema

### Core Tables

-   **utilisateur** - User accounts and authentication
-   **products** - Product catalog
-   **categories** - Product categories
-   **panier** - Shopping cart items
-   **commandes** - Orders
-   **order_items** - Order line items
-   **commentaires** - Product reviews/comments

### Relationships

```
Utilisateur (User)
├── hasMany Panier (Cart)
├── hasMany Commande (Order)
└── hasMany Commentaire (Comment)

Product
├── belongsTo Category
├── hasMany Panier
├── hasMany OrderItem
└── hasMany Commentaire

Commande (Order)
├── belongsTo Utilisateur
└── hasMany OrderItem

Category
└── hasMany Product
```

---

## 🔐 Authentication

The API uses **Laravel Sanctum** for token-based authentication.

### How It Works

1. **Registration/Login**: User receives an access token
2. **Authenticated Requests**: Include token in `Authorization` header
3. **Token Management**: Tokens are stored in `personal_access_tokens` table

### Token Usage

```javascript
// Example with fetch
fetch("http://localhost:8000/api/user", {
    headers: {
        Authorization: "Bearer " + token,
        Accept: "application/json",
        "Content-Type": "application/json",
    },
});
```

### Token Expiration

Tokens don't expire by default. To implement expiration, configure Sanctum in `config/sanctum.php`.

---

## 🧪 Testing

The project uses **Pest PHP** for testing.

### Run Tests

```bash
# Run all tests
php artisan test

# Or using composer
composer test

# Run specific test file
php artisan test tests/Feature/Auth/LoginTest.php

# Run with coverage
php artisan test --coverage
```

### Test Structure

```
tests/
├── Feature/
│   └── Auth/
│       ├── LoginTest.php
│       ├── RegisterTest.php
│       └── ...
└── Unit/
    └── ExampleTest.php
```

### Writing Tests

```php
test('user can register', function () {
    $response = $this->postJson('/api/register', [
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['user', 'token']);
});
```

---

## 💻 Development

### Code Style

The project uses **Laravel Pint** for code formatting:

```bash
# Format code
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Development Scripts

```bash
# Full development environment (server, queue, logs, vite)
composer run dev

# Setup project (install, migrate, seed)
composer run setup

# Run tests
composer test
```

### Database Migrations

```bash
# Create migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Reset database
php artisan migrate:fresh --seed
```

### Seeding Data

```bash
# Run seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=ProductSeeder
```

### Queue Processing

```bash
# Process queue jobs
php artisan queue:work

# Or listen for jobs
php artisan queue:listen
```

---

## 🚢 Deployment

### Production Checklist

-   [ ] Set `APP_ENV=production`
-   [ ] Set `APP_DEBUG=false`
-   [ ] Generate application key: `php artisan key:generate`
-   [ ] Run migrations: `php artisan migrate --force`
-   [ ] Optimize autoloader: `composer install --optimize-autoloader --no-dev`
-   [ ] Cache configuration: `php artisan config:cache`
-   [ ] Cache routes: `php artisan route:cache`
-   [ ] Cache views: `php artisan view:cache`
-   [ ] Set up queue worker (Supervisor/PM2)
-   [ ] Configure web server (Nginx/Apache)
-   [ ] Set up SSL certificate
-   [ ] Configure environment variables
-   [ ] Set up log rotation
-   [ ] Configure backup strategy

### Server Requirements

-   PHP >= 8.2
-   Composer
-   Database (MySQL/PostgreSQL)
-   Web server (Nginx/Apache)
-   SSL certificate (recommended)

### Environment Variables for Production

Ensure all production environment variables are set:

-   Database credentials
-   API keys (Stripe, Brevo, Cloudinary)
-   Mail configuration
-   Cache driver

---

## 🔒 Security

### Security Features

-   **Password Hashing**: Bcrypt password hashing
-   **CSRF Protection**: Built-in CSRF protection
-   **SQL Injection Prevention**: Eloquent ORM with parameter binding
-   **Token Authentication**: Secure token-based auth
-   **Authorization**: Policy-based access control

### Reporting Security Issues

If you discover a security vulnerability, please email abddo845@gmail.com instead of using the issue tracker.

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Make your changes**
4. **Write/update tests** for your changes
5. **Ensure tests pass** (`composer test`)
6. **Format code** (`./vendor/bin/pint`)
7. **Commit your changes** (`git commit -m 'Add amazing feature'`)
8. **Push to the branch** (`git push origin feature/amazing-feature`)
9. **Open a Pull Request**

## 📝 License

This project is open-sourced software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

## 👥 Authors

-   **Abdelilah** - [YourGitHub](https://github.com/Abddo17)

---

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com/) - The PHP Framework
-   [Stripe](https://stripe.com/) - Payment processing
-   [Brevo](https://www.brevo.com/) - Email service
-   [Cloudinary](https://cloudinary.com/) - Image management
-   All contributors and the open-source community

---

## 📞 Support

For support, email abddo845@gmail.com or open an issue in the repository.

---

## 🔗 Useful Links

-   [Laravel Documentation](https://laravel.com/docs)
-   [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
-   [Stripe API Documentation](https://stripe.com/docs/api)
-   [Brevo API Documentation](https://developers.brevo.com/)
-   [Cloudinary Documentation](https://cloudinary.com/documentation)

---

<div align="center">

**Made with ❤️ using Laravel**

⭐ Star this repo if you find it helpful!

</div>
