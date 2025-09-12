# School Uniform Inventory Management System

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel" alt="Laravel 11">
<img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP 8.2+">
<img src="https://img.shields.io/badge/Bootstrap-5-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap 5">
</p>

## About The Project

The School Uniform Inventory Management System is a web application built with Laravel 11 to manage school uniform inventory. This application features role-based access control, distinguishing between Administrators and regular Users.

## Key Features

### 🔐 **Authentication & Authorization**
- **User Registration & Login**: A secure user registration and login system.
- **Role-Based Access Control**: Differentiated access based on user roles (Admin/User).
- **Dashboard**: A dedicated dashboard for each role.
- **Session Management**: Secure session handling.

### 👨‍💼 **Admin Features**
- **Inventory Management**: Manage uniform inventory data.
- **Product Management**: Manage uniform product data.
- **Reports**: Generate stock and inventory reports.
- **Export to Excel/PDF**: Export reports to Excel and PDF formats.
- **Stock Monitoring**: Real-time stock monitoring.

### 👤 **User Features**
- **Product Catalog**: View the uniform product catalog.
- **Product Details**: View detailed product information.
- **Shopping Cart**: Add products to a shopping cart.
- **Checkout**: A seamless checkout process.
- **Order History**: View personal order history.

### 🛡️ **Security Features**
- **AdminMiddleware**: A dedicated middleware to protect admin routes.
- **Password Hashing**: Secure password encryption.
- **CSRF Protection**: Protection against CSRF attacks.
- **Input Validation**: Strict input validation.

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM (for asset compilation)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/YugaZ963/kerja-praktik.git
   cd kerja-praktik
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   - Create a MySQL database.
   - Update the database configuration in the `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_username
   DB_PASSWORD=your_database_password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate --seed
   ```
   This will run all migrations and seed the database with initial data, including an admin user.

6. **Compile Assets**
   ```bash
   npm run build
   ```

7. **Start the Development Server**
   ```bash
   php artisan serve
   ```
   The application will be available at `http://127.0.0.1:8000`.

## Default Login Credentials

### Administrator
- **Email:** `admin@ravazka.com`
- **Password:** `admin123`

### Regular User
- **Email:** `user@ravazka.com`
- **Password:** `user123`

## Project Structure

```
├── app/
│   ├── Console/Commands/       # Artisan commands
│   ├── Exports/                # Excel exports
│   ├── Helpers/                # Helper classes
│   ├── Http/
│   │   ├── Controllers/        # Application controllers
│   │   └── Middleware/         # Application middleware
│   ├── Models/                 # Eloquent models
│   ├── Observers/              # Model observers
│   ├── Providers/              # Service providers
│   └── Services/               # Service classes
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/                     # Publicly accessible files
├── resources/
│   ├── css/
│   ├── js/
│   └── views/                  # Blade templates
├── routes/                     # Route definitions
└── tests/                      # Application tests
```

## Contributing

1. Fork this repository.
2. Create a feature branch (`git checkout -b feature/AmazingFeature`).
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4. Push to the branch (`git push origin feature/AmazingFeature`).
5. Open a Pull Request.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
