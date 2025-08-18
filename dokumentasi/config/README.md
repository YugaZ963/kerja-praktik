# Dokumentasi File Konfigurasi

## 📋 Overview
Folder `config/` berisi semua file konfigurasi untuk aplikasi RAVAZKA. Setiap file mengatur aspek tertentu dari aplikasi dan dapat dikustomisasi melalui environment variables.

## 📁 Struktur File Konfigurasi

```
config/
├── app.php          # Konfigurasi aplikasi utama
├── auth.php         # Sistem autentikasi
├── cache.php        # Sistem caching
├── database.php     # Koneksi database
├── excel.php        # Export Excel (Laravel Excel)
├── filesystems.php  # Storage dan file system
├── googlemaps.php   # Google Maps API
├── logging.php      # Sistem logging
├── mail.php         # Email configuration
├── queue.php        # Queue system
├── services.php     # Third-party services
└── session.php      # Session management
```

## 🔧 File Konfigurasi Detail

### 1. **app.php** - Konfigurasi Aplikasi Utama
**Fungsi**: Mengatur pengaturan dasar aplikasi

**Konfigurasi Utama**:
- `name`: Nama aplikasi (default: 'Laravel')
- `env`: Environment aplikasi (local/production)
- `debug`: Mode debug untuk development
- `url`: Base URL aplikasi
- `timezone`: Timezone aplikasi (UTC)
- `locale`: Bahasa default aplikasi
- `providers`: Service providers yang diload
- `aliases`: Class aliases untuk kemudahan akses

**Environment Variables**:
```env
APP_NAME=RAVAZKA
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
APP_TIMEZONE=Asia/Jakarta
```

**Teknologi**: Laravel Service Container, Service Providers

---

### 2. **database.php** - Konfigurasi Database
**Fungsi**: Mengatur koneksi database dan pengaturan terkait

**Konfigurasi Utama**:
- `default`: Koneksi database default ('mysql')
- `connections`: Definisi berbagai koneksi database
- `migrations`: Tabel untuk tracking migrasi
- `redis`: Konfigurasi Redis (jika digunakan)

**Koneksi MySQL**:
```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'laravel'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]
```

**Environment Variables**:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ravazka_db
DB_USERNAME=root
DB_PASSWORD=
```

**Teknologi**: Eloquent ORM, PDO, MySQL

---

### 3. **auth.php** - Sistem Autentikasi
**Fungsi**: Mengatur sistem login, register, dan authorization

**Konfigurasi Utama**:
- `defaults`: Guard dan password broker default
- `guards`: Definisi authentication guards
- `providers`: User providers untuk autentikasi
- `passwords`: Konfigurasi password reset

**Guard Configuration**:
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
```

**User Provider**:
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

**Teknologi**: Laravel Authentication, Session-based Auth, Eloquent

---

### 4. **session.php** - Manajemen Sesi
**Fungsi**: Mengatur penyimpanan dan pengelolaan session user

**Konfigurasi Utama**:
- `driver`: Driver session ('database')
- `lifetime`: Durasi session (120 menit)
- `expire_on_close`: Session expire saat browser ditutup
- `encrypt`: Enkripsi data session
- `files`: Path penyimpanan file session
- `connection`: Koneksi database untuk session
- `table`: Tabel database untuk session
- `store`: Redis store (jika menggunakan Redis)

**Database Session**:
```php
'driver' => env('SESSION_DRIVER', 'database'),
'table' => env('SESSION_TABLE', 'sessions'),
'connection' => env('SESSION_CONNECTION'),
```

**Environment Variables**:
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
```

**Teknologi**: Database Session Storage, Encryption

---

### 5. **cache.php** - Sistem Caching
**Fungsi**: Mengatur penyimpanan cache untuk performa aplikasi

**Konfigurasi Utama**:
- `default`: Driver cache default ('file')
- `stores`: Definisi berbagai cache stores
- `prefix`: Prefix untuk cache keys

**File Cache**:
```php
'file' => [
    'driver' => 'file',
    'path' => storage_path('framework/cache/data'),
    'lock_path' => storage_path('framework/cache/data'),
],
```

**Teknologi**: File-based Caching, Redis (ready)

---

### 6. **mail.php** - Konfigurasi Email
**Fungsi**: Mengatur pengiriman email dan notifikasi

**Konfigurasi Utama**:
- `default`: Mailer default ('log')
- `mailers`: Definisi berbagai mail drivers
- `from`: Default sender information

**SMTP Configuration**:
```php
'smtp' => [
    'transport' => 'smtp',
    'host' => env('MAIL_HOST', '127.0.0.1'),
    'port' => env('MAIL_PORT', 2525),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
],
```

**Environment Variables**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

**Teknologi**: SMTP, Laravel Mail, Mailable Classes

---

### 7. **filesystems.php** - File Storage
**Fungsi**: Mengatur penyimpanan file dan asset

**Konfigurasi Utama**:
- `default`: Disk default ('local')
- `disks`: Definisi berbagai storage disks
- `links`: Symbolic links untuk public storage

**Local Storage**:
```php
'local' => [
    'driver' => 'local',
    'root' => storage_path('app'),
    'throw' => false,
],

'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
    'throw' => false,
],
```

**Teknologi**: Local File Storage, Cloud Storage (ready)

---

### 8. **googlemaps.php** - Google Maps API
**Fungsi**: Konfigurasi untuk integrasi Google Maps

**Konfigurasi**:
```php
return [
    'api_key' => env('GOOGLE_MAPS_API_KEY'),
    'region' => 'ID',
    'language' => 'id',
];
```

**Environment Variables**:
```env
GOOGLE_MAPS_API_KEY=your-google-maps-api-key
```

**Teknologi**: Google Maps JavaScript API, Geocoding API

---

### 9. **excel.php** - Laravel Excel
**Fungsi**: Konfigurasi untuk export/import Excel

**Fitur**:
- Export data ke Excel/CSV
- Import data dari Excel/CSV
- Batch processing
- Memory optimization

**Teknologi**: PhpSpreadsheet, Laravel Excel Package

---

### 10. **logging.php** - Sistem Logging
**Fungsi**: Mengatur pencatatan log aplikasi

**Konfigurasi Utama**:
- `default`: Channel log default ('stack')
- `deprecations`: Log untuk deprecated features
- `channels`: Definisi berbagai log channels

**Stack Channel**:
```php
'stack' => [
    'driver' => 'stack',
    'channels' => ['single'],
    'ignore_exceptions' => false,
],
```

**Teknologi**: Monolog, File Logging, Syslog

---

### 11. **queue.php** - Queue System
**Fungsi**: Mengatur sistem antrian untuk background jobs

**Konfigurasi Utama**:
- `default`: Connection default ('database')
- `connections`: Definisi queue connections
- `batching`: Konfigurasi job batching
- `failed`: Konfigurasi failed jobs

**Database Queue**:
```php
'database' => [
    'driver' => 'database',
    'connection' => env('DB_QUEUE_CONNECTION'),
    'table' => env('DB_QUEUE_TABLE', 'jobs'),
    'queue' => env('DB_QUEUE', 'default'),
    'retry_after' => (int) env('DB_QUEUE_RETRY_AFTER', 90),
    'after_commit' => false,
],
```

**Teknologi**: Database Queue, Redis Queue (ready), Job Processing

---

### 12. **services.php** - Third-party Services
**Fungsi**: Konfigurasi untuk layanan eksternal

**Konfigurasi**:
```php
return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    
    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
];
```

**Teknologi**: AWS SES, Postmark, Third-party API Integration

---

## 🔐 Security Considerations

### **Environment Variables**
- Semua konfigurasi sensitif menggunakan `.env` file
- `.env` file tidak di-commit ke repository
- Production menggunakan environment variables server

### **Encryption**
- Session data dapat dienkripsi
- Database credentials disimpan aman
- API keys tidak hardcoded

### **Best Practices**
- Gunakan environment-specific configurations
- Validasi konfigurasi pada startup
- Monitor perubahan konfigurasi
- Backup konfigurasi production

---

## 🚀 Deployment Notes

### **Development**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database
php artisan migrate
```

### **Production**
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure proper database credentials
- Set up proper mail configuration
- Configure cache and session drivers

### **Performance Optimization**
```bash
# Cache configurations
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache
```

File konfigurasi ini membentuk fondasi yang solid untuk aplikasi RAVAZKA dengan fleksibilitas tinggi dan keamanan yang baik.