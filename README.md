# Sistem Manajemen Inventaris Seragam Sekolah

<p align="center">
<img src="https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel" alt="Laravel 11">
<img src="https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php" alt="PHP 8.2+">
<img src="https://img.shields.io/badge/Bootstrap-5-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap 5">
</p>

## Tentang Project

Sistem Manajemen Inventaris Seragam Sekolah adalah aplikasi web yang dibangun dengan Laravel 11 untuk mengelola inventaris seragam sekolah. Aplikasi ini memiliki fitur authentication dengan role-based access control yang membedakan akses antara Administrator dan User biasa.

## Fitur Utama

### 🔐 **Authentication & Authorization**
- **User Registration & Login** - Sistem pendaftaran dan login pengguna
- **Role-based Access Control** - Pembedaan akses berdasarkan role (Admin/User)
- **Dashboard** - Dashboard khusus untuk setiap role
- **Session Management** - Pengelolaan sesi yang aman

### 👨‍💼 **Admin Features**
- **Inventory Management** - Kelola data inventaris seragam
- **Product Management** - Kelola data produk seragam
- **Reports** - Laporan stok dan inventaris
- **Export to Excel/PDF** - Export laporan ke format Excel dan PDF
- **Stock Monitoring** - Monitoring stok real-time

### 👤 **User Features**
- **Product Catalog** - Melihat katalog produk seragam
- **Product Details** - Detail informasi produk
- **Dashboard** - Dashboard personal user

### 🛡️ **Security Features**
- **AdminMiddleware** - Middleware khusus untuk proteksi route admin
- **Password Hashing** - Enkripsi password yang aman
- **CSRF Protection** - Perlindungan dari serangan CSRF
- **Input Validation** - Validasi input yang ketat

## Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Node.js & NPM (untuk asset compilation)

### Langkah Instalasi

1. **Clone Repository**
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
   - Buat database MySQL
   - Update konfigurasi database di file `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database
   DB_USERNAME=username
   DB_PASSWORD=password
   ```

5. **Database Migration & Seeding**
   ```bash
   php artisan migrate
   php artisan db:seed --class=AdminUserSeeder
   ```

6. **Compile Assets**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Default Login Credentials

### Administrator
- **Email:** `admin@ravazka.com`
- **Password:** `admin123`

### Regular User
- **Email:** `user@ravazka.com`
- **Password:** `user123`

## Struktur Project

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── InventoryController.php
│   │   │   └── ProductController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Inventory.php
│       └── Product.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── auth/
│       ├── inventory/
│       └── layouts/
└── routes/
    └── web.php
```

## Contributing

1. Fork repository ini
2. Buat branch feature (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development/)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
