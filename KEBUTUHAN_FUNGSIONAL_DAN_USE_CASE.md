# Kebutuhan Fungsional dan Use Case - Sistem E-Commerce Seragam Sekolah RAVAZKA (ULTRA SIMPLIFIED)

## 📋 Overview

Dokumen ini berisi **10 kebutuhan fungsional utama** untuk **Sistem E-Commerce Toko Seragam Sekolah RAVAZKA** yang dibangun menggunakan Laravel 11. Sistem ini menyediakan platform inti untuk penjualan seragam sekolah dengan fitur-fitur esensial yang fokus pada interaksi user yang minimal namun efektif.

---

## 🎯 KEBUTUHAN FUNGSIONAL UTAMA (10 Use Cases)

### 1. **UC-01: Autentikasi Pengguna**
- **Deskripsi**: Sistem login, registrasi, dan logout
- **Aktor**: Guest User → Customer/Admin
- **Fitur Utama**:
  - Registrasi pengguna baru
  - Login dengan email dan password
  - Logout dengan pembersihan session
  - Role-based access (Customer/Admin)
- **Implementasi**: `AuthController`

### 2. **UC-02: Beranda dan Katalog**
- **Deskripsi**: Halaman utama dengan katalog produk terintegrasi
- **Aktor**: Guest User, Customer, Admin
- **Fitur Utama**:
  - Tampilan produk unggulan di beranda
  - Grid katalog produk dengan pagination
  - Filter kategori dan ukuran
  - Search produk
  - Detail produk dalam modal/popup
- **Implementasi**: `WelcomeController`, `ProductController`

### 3. **UC-03: Keranjang dan Checkout**
- **Deskripsi**: Mengelola keranjang belanja dan proses pemesanan
- **Aktor**: Guest User, Customer
- **Fitur Utama**:
  - Tambah/ubah/hapus item keranjang
  - Session-based (guest) dan user-based (login)
  - Kalkulasi total otomatis
  - Form checkout terintegrasi
  - Integrasi WhatsApp untuk konfirmasi
- **Implementasi**: `CartController`

### 4. **UC-04: Manajemen Produk (Admin)**
- **Deskripsi**: Admin mengelola produk dan inventaris
- **Aktor**: Admin
- **Fitur Utama**:
  - CRUD produk dengan inventaris terintegrasi
  - Upload gambar produk
  - Manajemen stok real-time
  - Adjustment stok per ukuran
- **Implementasi**: `Admin\ProductController`, `InventoryController`

### 5. **UC-05: Manajemen Pesanan (Admin)**
- **Deskripsi**: Admin mengelola semua pesanan
- **Aktor**: Admin
- **Fitur Utama**:
  - Dashboard pesanan
  - Update status pesanan
  - Verifikasi pembayaran
  - Laporan penjualan sederhana
- **Implementasi**: `Admin\OrderController`

### 6. **UC-06: Tracking Pesanan (Customer)**
- **Deskripsi**: Customer melacak pesanan
- **Aktor**: Customer
- **Fitur Utama**:
  - Riwayat pesanan
  - Detail status pesanan
  - Upload bukti pembayaran
  - Konfirmasi penerimaan
- **Implementasi**: `Customer\OrderController`

### 7. **UC-07: Testimonial**
- **Deskripsi**: Customer memberikan testimonial
- **Aktor**: Customer
- **Fitur Utama**:
  - Form testimonial dengan rating
  - Input nama dan institusi
  - Approval system admin
- **Implementasi**: `TestimonialController`

### 8. **UC-08: Dashboard Admin**
- **Deskripsi**: Admin melihat dashboard dan laporan
- **Aktor**: Admin
- **Fitur Utama**:
  - Dashboard analytics sederhana
  - Total revenue dan pesanan
  - Grafik penjualan
  - Ringkasan inventaris
- **Implementasi**: `Admin\DashboardController`

### 9. **UC-09: Halaman Informasi**
- **Deskripsi**: Halaman statis informasi toko
- **Aktor**: Guest User, Customer, Admin
- **Fitur Utama**:
  - Halaman Tentang Kami
  - Halaman Kontak
  - Informasi lokasi toko
- **Implementasi**: `AboutController`, `ContactController`

### 10. **UC-10: SEO dan Optimasi**
- **Deskripsi**: Fitur SEO dan optimasi sistem
- **Aktor**: System (Auto-generated)
- **Fitur Utama**:
  - Auto-generate sitemap.xml
  - Meta tags dinamis
  - URL-friendly slugs
  - Robots.txt
- **Implementasi**: `SeoController`, Route middleware

---

## 📊 USE CASE DIAGRAM SIMPLIFIED

### **Aktor Sistem**

#### 1. **Guest User** 🔓
- Pengunjung yang belum login
- Dapat melihat produk dan menambahkan ke cart

#### 2. **Customer** 👤
- Pelanggan terdaftar
- Dapat melakukan pembelian dan tracking pesanan

#### 3. **Admin** 👨‍💼
- Pengelola sistem
- Akses penuh ke fitur administratif

### **13 Use Cases Utama**

1. **UC-01**: Login dan Registrasi
2. **UC-02**: Beranda (Welcome)
3. **UC-03**: Katalog Produk
4. **UC-04**: Detail Produk
5. **UC-05**: Keranjang Belanja
6. **UC-06**: Checkout Pesanan
7. **UC-07**: Kelola Produk (Admin)
8. **UC-08**: Kelola Inventaris (Admin)
9. **UC-09**: Kelola Pesanan (Admin)
10. **UC-10**: Tracking Pesanan (Customer)
11. **UC-11**: Testimonial
12. **UC-12**: Laporan Penjualan (Admin)
13. **UC-13**: Halaman Informasi

### **Relasi Use Case**

#### **Include Relationships**
- UC-06 (Checkout) **includes** WhatsApp Integration
- UC-05 (Keranjang) **includes** Stock Validation
- UC-08 (Kelola Inventaris) **includes** Stock Monitoring

#### **Extend Relationships**
- UC-11 (Testimonial) **extends** UC-10 (Tracking Pesanan)
- UC-10 (Upload Bukti) **extends** UC-10 (Tracking Pesanan)

#### **Generalization**
- Guest User → Customer (setelah login)
- Customer ← Admin (admin memiliki akses customer + admin)

---

## 🔧 IMPLEMENTASI TEKNIS

### **Arsitektur Sistem**
- **Framework**: Laravel 11
- **Pattern**: MVC (Model-View-Controller)
- **Database**: MySQL dengan Eloquent ORM
- **Frontend**: Blade Templates + Bootstrap 5

### **Key Models**
- `User`: Data pengguna dengan role
- `Product`: Data produk seragam
- `Inventory`: Data inventaris
- `Cart`: Keranjang belanja
- `Order`: Data pesanan
- `Testimonial`: Testimonial pelanggan

### **Key Controllers**
- `AuthController`: Authentication
- `ProductController`: Katalog produk
- `CartController`: Keranjang dan checkout
- `OrderController`: Manajemen pesanan
- `InventoryController`: Manajemen inventaris
- `SalesReportController`: Laporan penjualan

---

## 📈 BUSINESS RULES

1. **Stok Management**: Stok tidak boleh negatif, pengurangan otomatis saat delivered
2. **Order Processing**: Status pesanan mengikuti flow yang ditentukan
3. **Cart Management**: Session-based untuk guest, user-based untuk login
4. **Access Control**: Role-based access sesuai dengan aktor
5. **Testimonial**: Hanya customer dengan pesanan completed

---

## 🎯 KESIMPULAN

Sistem E-Commerce RAVAZKA (Ultra Simplified) fokus pada **10 fitur inti** yang esensial dengan interaksi user yang minimal:

✅ **Autentikasi Terintegrasi** (Login/Register/Logout)
✅ **Beranda dengan Katalog Terintegrasi**
✅ **Keranjang dan Checkout Terpadu**
✅ **Manajemen Produk dan Inventaris Terpadu (Admin)**
✅ **Manajemen Pesanan dengan Laporan (Admin)**
✅ **Tracking Pesanan (Customer)**
✅ **Sistem Testimonial**
✅ **Dashboard Admin Terintegrasi**
✅ **Halaman Informasi Statis**
✅ **SEO dan Optimasi Otomatis**

Sistem ini dirancang untuk memenuhi kebutuhan inti toko seragam sekolah dengan kompleksitas minimal dan fokus pada efisiensi interaksi user.

---

## 📋 RINGKASAN 10 USE CASE UTAMA

1. **UC-01**: Autentikasi Pengguna - Login, registrasi, logout terintegrasi
2. **UC-02**: Beranda dan Katalog - Halaman utama dengan katalog produk
3. **UC-03**: Keranjang dan Checkout - Keranjang belanja dengan checkout terpadu
4. **UC-04**: Manajemen Produk (Admin) - Kelola produk dan inventaris terpadu
5. **UC-05**: Manajemen Pesanan (Admin) - Kelola pesanan dengan laporan
6. **UC-06**: Tracking Pesanan (Customer) - Pelacakan pesanan pelanggan
7. **UC-07**: Testimoni - Sistem testimonial pelanggan
8. **UC-08**: Dashboard Admin - Dashboard dengan laporan terintegrasi
9. **UC-09**: Halaman Informasi - Halaman statis (Tentang, Kontak)
10. **UC-10**: SEO dan Optimasi - Fitur SEO otomatis

**Keunggulan Sistem Ultra Simplified:**
- Menggabungkan fitur terkait dalam satu use case
- Mengurangi kompleksitas navigasi user
- Fokus pada efisiensi dan kemudahan penggunaan
- Tetap mempertahankan semua fitur inti e-commerce

Sistem ini mengoptimalkan pengalaman user dengan meminimalkan jumlah interaksi yang diperlukan sambil tetap menyediakan semua fungsi esensial untuk toko seragam sekolah RAVAZKA.