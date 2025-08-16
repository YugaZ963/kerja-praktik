# Use Case Diagram - RAVAZKA System

## 📋 Overview

Dokumen ini berisi Use Case Diagram untuk **Sistem Manajemen Inventaris Seragam Sekolah (RAVAZKA)** yang dibangun menggunakan Laravel 11. Diagram ini menggambarkan semua interaksi antara aktor (users) dengan sistem.

## 📁 Files

1. **`Use_Case_Diagram.md`** - Dokumentasi lengkap use cases dalam format Markdown
2. **`Use_Case_Diagram.puml`** - Diagram visual dalam format PlantUML
3. **`USE_CASE_README.md`** - File ini (panduan penggunaan)

## 👥 Actors (Aktor)

### 1. **Guest User** 🔓
- Pengunjung yang belum login
- Dapat melihat produk dan menambahkan ke cart
- Perlu registrasi/login untuk checkout

### 2. **Customer** 👤
- Pelanggan terdaftar dengan role 'user'
- Dapat melakukan pembelian dan tracking pesanan
- Memiliki akses ke fitur cart persistent

### 3. **Admin** 👨‍💼
- Pengelola sistem dengan role 'admin'
- Akses penuh ke semua fitur administratif
- Dapat mengelola inventaris, pesanan, dan laporan

### 4. **System** ⚙️
- Sistem otomatis yang menjalankan proses background
- Mengelola session, stock, dan cart persistence

## 🎯 Main Use Cases

### **Authentication & Authorization**
- **UC-001**: Login
- **UC-002**: Register
- **UC-003**: Logout

### **Product Management**
- **UC-009**: Browse Products
- **UC-010**: View Product Details
- **UC-004**: Manage Inventory (Admin only)
- **UC-005**: Manage Products (Admin only)

### **Shopping & Orders**
- **UC-011**: Add to Cart
- **UC-012**: Manage Cart
- **UC-013**: Checkout Process
- **UC-014**: WhatsApp Integration
- **UC-015**: Track Order
- **UC-016**: Mark Order as Completed
- **UC-017**: Submit Testimonial

### **Administrative**
- **UC-006**: Manage Orders (Admin only)
- **UC-007**: Generate Sales Report (Admin only)
- **UC-008**: Monitor Stock (Admin only)

### **System Features**
- **UC-018**: Persistent Cart
- **UC-019**: Stock Management
- **UC-020**: Session Management

## 🔗 Relationships

### **Include Relationships** (wajib terjadi)
- Checkout Process **includes** WhatsApp Integration
- Sales Report **includes** Export to PDF/Excel
- Add to Cart **includes** Persistent Cart

### **Extend Relationships** (opsional)
- Submit Testimonial **extends** Mark Order Completed
- Persistent Cart **extends** Add to Cart
- Stock Management **extends** Checkout Process

### **Generalization** (inheritance)
- Guest User → Customer (setelah register/login)
- Customer → Admin (dengan elevated privileges)

## 🛠️ How to View the Diagram

### Option 1: PlantUML Online
1. Buka [PlantUML Online Server](http://www.plantuml.com/plantuml/uml/)
2. Copy-paste isi file `Use_Case_Diagram.puml`
3. Klik "Submit" untuk generate diagram

### Option 2: VS Code Extension
1. Install extension "PlantUML" di VS Code
2. Buka file `Use_Case_Diagram.puml`
3. Tekan `Alt + D` untuk preview diagram

### Option 3: Local PlantUML
1. Download PlantUML JAR file
2. Install Java Runtime
3. Run: `java -jar plantuml.jar Use_Case_Diagram.puml`

## 📊 Business Rules

1. **Authentication Required**: User harus login untuk checkout
2. **Role-Based Access**: Admin memiliki akses ke semua fitur
3. **Stock Validation**: Pesanan tidak boleh melebihi stok tersedia
4. **Order Status Flow**: pending → processing → shipped → delivered → completed
5. **Testimonial Rules**: Hanya pesanan completed yang bisa diberi testimoni
6. **Cart Persistence**: Cart user login tersimpan di database
7. **WhatsApp Integration**: Semua pesanan dikonfirmasi via WhatsApp
8. **Payment Methods**: Mendukung Bank Transfer (BRI) dan E-Wallet (DANA)

## 🔧 Technical Implementation

### **Framework & Technology**
- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Bootstrap 5 + Blade Templates
- **Database**: MySQL/PostgreSQL/SQLite
- **Authentication**: Laravel Auth + Role-based middleware
- **Session**: Database-driven (120 min lifetime)
- **Caching**: Redis support

### **Key Components**
- **Controllers**: AuthController, InventoryController, CartController, OrderController
- **Models**: User, Product, Cart, Order, OrderItem, Testimonial
- **Middleware**: AdminMiddleware, RequireLoginMiddleware
- **Routes**: Role-protected routes with middleware

### **Security Features**
- CSRF Protection
- Input Validation
- Password Hashing (bcrypt)
- Role-based Access Control
- Session Security

## 📱 User Flows

### **Customer Purchase Flow**
1. Browse Products → View Details → Add to Cart
2. Manage Cart → Checkout → Fill Data → Select Payment
3. WhatsApp Confirmation → Track Order → Mark Completed → Submit Testimonial

### **Admin Management Flow**
1. Login → Dashboard → Manage Inventory/Products
2. Process Orders → Update Status → Generate Reports
3. Monitor Stock → Export Data

### **Guest to Customer Flow**
1. Browse as Guest → Add to Cart → Register/Login
2. Cart Auto-merge → Continue Checkout

## 🎨 Diagram Legend

- **Rectangle**: System boundary
- **Oval**: Use case
- **Stick Figure**: Actor
- **Solid Arrow**: Association
- **Dashed Arrow with <<include>>**: Include relationship
- **Dashed Arrow with <<extend>>**: Extend relationship
- **Solid Arrow with triangle**: Generalization

## 📞 Support

Untuk pertanyaan teknis atau klarifikasi tentang use case diagram ini, silakan hubungi tim development atau buat issue di repository project.

---

**Created for**: Sistem Manajemen Inventaris Seragam Sekolah (RAVAZKA)  
**Framework**: Laravel 11  
**Last Updated**: January 2025  
**Version**: 1.0