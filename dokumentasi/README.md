# Dokumentasi Project RAVAZKA

## Overview
Dokumentasi lengkap untuk **Sistem E-Commerce Toko Seragam Sekolah RAVAZKA** yang dibangun menggunakan Laravel 11. Sistem ini menyediakan platform lengkap untuk penjualan seragam sekolah dengan fitur manajemen inventaris, sistem pemesanan, integrasi WhatsApp, dan dashboard admin yang komprehensif.

## Struktur Dokumentasi

### 📁 config/
Dokumentasi file-file konfigurasi sistem
- Database configuration
- Authentication & session
- Application settings
- Third-party services

### 📁 app/
Dokumentasi kode aplikasi utama
- **Models/**: Entity dan relasi database
- **Controllers/**: Logic bisnis dan handling request
- **Middleware/**: Filter dan validasi request
- **Services/**: Service layer untuk logic kompleks

### 📁 database/
Dokumentasi struktur dan migrasi database
- **migrations/**: Skema database
- **seeders/**: Data awal sistem

### 📁 resources/
Dokumentasi tampilan dan asset
- **views/**: Template Blade
- **css/**: Styling aplikasi
- **js/**: JavaScript functionality

### 📁 routes/
Dokumentasi routing dan endpoint
- Web routes
- API endpoints
- Route middleware

### 📁 public/
Dokumentasi asset publik
- Images dan media
- Compiled assets
- Static files

## Fitur Utama

### 🛒 **E-Commerce Features**
- **Katalog Produk**: Browsing produk seragam dengan filter kategori dan ukuran
- **Keranjang Belanja**: Cart persistence untuk user login dan session-based untuk guest
- **Sistem Checkout**: Proses pemesanan dengan multiple payment methods
- **Order Tracking**: Pelacakan status pesanan real-time
- **WhatsApp Integration**: Konfirmasi pesanan otomatis via WhatsApp

### 👨‍💼 **Admin Management**
- **Dashboard Analytics**: Statistik penjualan dan inventory real-time
- **Inventory Management**: CRUD inventaris dengan stock monitoring
- **Order Management**: Kelola pesanan, update status, upload bukti pembayaran
- **Sales Reports**: Laporan penjualan dengan export PDF/Excel
- **Product Management**: Manajemen produk dengan multiple sizes dan categories

### 🔐 **Authentication & Security**
- **Role-based Access**: Admin dan Customer dengan permission berbeda
- **Secure Authentication**: Laravel built-in auth dengan CSRF protection
- **Session Management**: Database-driven sessions dengan 120 menit lifetime

### 💳 **Payment & Shipping**
- **Multiple Payment Methods**: Bank Transfer (BRI) dan E-Wallet (DANA)
- **Shipping Options**: Regular (3-5 hari) dan Express (1-2 hari)
- **Payment Proof Upload**: Upload bukti pembayaran untuk verifikasi

## Teknologi yang Digunakan

### Backend
- **Laravel 11**: PHP Framework dengan MVC architecture
- **MySQL**: Database dengan Eloquent ORM
- **Blade**: Template engine untuk views
- **Laravel Auth**: Built-in authentication system

### Frontend
- **Bootstrap 5**: Responsive CSS Framework
- **JavaScript/jQuery**: Client-side functionality
- **Bootstrap Icons**: Icon library
- **Chart.js**: Dashboard analytics charts

### Integrations
- **WhatsApp API**: Order confirmation integration
- **Google Maps**: Location services (optional)
- **Excel Export**: Laravel Excel untuk laporan

### Tools & Libraries
- **Composer**: PHP dependency manager
- **NPM**: Node package manager
- **Vite**: Modern build tool
- **PHPUnit**: Testing framework

## Quick Start Guide

### 📋 **Prerequisites**
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Web Server (Apache/Nginx)

### 🚀 **Installation**
```bash
# Clone repository
git clone [repository-url]
cd kerja-praktik

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ravazka_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations and seeders
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### 👤 **Default Accounts**
- **Admin**: admin@ravazka.com / password
- **Customer**: user@ravazka.com / password

## Cara Membaca Dokumentasi

1. **Mulai dari Overview**: Pahami arsitektur sistem secara keseluruhan
2. **Pelajari Models**: Memahami struktur data dan relasi
3. **Pahami Controllers**: Logic bisnis dan flow aplikasi
4. **Lihat Views**: Interface dan user experience
5. **Cek Routes**: Endpoint dan navigation
6. **Review Use Cases**: Understand business processes

## Konvensi Dokumentasi

- ✅ **Implemented**: Fitur sudah diimplementasi
- 🚧 **In Progress**: Sedang dalam pengembangan
- ❌ **Not Implemented**: Belum diimplementasi
- 📝 **Documentation**: Dokumentasi tersedia
- ⚠️ **Important**: Informasi penting

---

*Dokumentasi ini dibuat untuk membantu developer memahami struktur, alur, dan teknologi yang digunakan dalam project RAVAZKA.*