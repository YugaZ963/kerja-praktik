# Use Case Diagram - Sistem Manajemen Inventaris Seragam Sekolah (RAVAZKA)

## Actors (Aktor)

### 1. **Admin**
- Pengelola sistem dengan akses penuh
- Memiliki role 'admin' dalam sistem
- Dapat mengakses semua fitur administratif

### 2. **Customer/User**
- Pelanggan yang ingin membeli seragam sekolah
- Memiliki role 'user' dalam sistem
- Dapat melakukan pembelian dan tracking pesanan

### 3. **Guest User**
- Pengunjung yang belum login
- Dapat melihat produk dan menambahkan ke cart
- Perlu login/register untuk checkout

---

## Use Cases

### **Authentication & Authorization**

#### UC-001: Login
- **Actor**: Admin, Customer
- **Description**: Masuk ke sistem menggunakan email dan password
- **Precondition**: User memiliki akun yang terdaftar
- **Flow**: Input credentials → Validasi → Redirect ke dashboard sesuai role

#### UC-002: Register
- **Actor**: Guest User
- **Description**: Mendaftar akun baru dengan memilih role (admin/user)
- **Precondition**: Belum memiliki akun
- **Flow**: Input data → Validasi → Buat akun → Auto login

#### UC-003: Logout
- **Actor**: Admin, Customer
- **Description**: Keluar dari sistem
- **Flow**: Klik logout → Hapus session → Redirect ke halaman utama

---

### **Admin Features**

#### UC-004: Manage Inventory
- **Actor**: Admin
- **Description**: Mengelola data inventaris seragam (CRUD)
- **Includes**: Add Inventory, Edit Inventory, Delete Inventory, View Inventory
- **Flow**: Akses menu inventaris → Pilih aksi → Input/Edit data → Simpan

#### UC-005: Manage Products
- **Actor**: Admin
- **Description**: Mengelola data produk seragam
- **Includes**: Add Product, Edit Product, Delete Product, View Product Details
- **Flow**: Akses menu produk → Pilih aksi → Input/Edit data → Simpan

#### UC-006: Manage Orders
- **Actor**: Admin
- **Description**: Mengelola pesanan pelanggan
- **Includes**: View Orders, Update Order Status, Process Orders
- **Flow**: Akses menu pesanan → Pilih pesanan → Update status → Simpan

#### UC-007: Generate Sales Report
- **Actor**: Admin
- **Description**: Membuat laporan penjualan dengan filter tanggal
- **Includes**: View Sales Summary, Export to PDF, Export to Excel
- **Flow**: Akses menu laporan → Set filter → Generate report → Export (optional)

#### UC-008: Monitor Stock
- **Actor**: Admin
- **Description**: Memantau stok produk real-time
- **Flow**: Akses dashboard → View stock status → Take action if needed

---

### **Customer Features**

#### UC-009: Browse Products
- **Actor**: Customer, Guest User
- **Description**: Melihat katalog produk seragam yang tersedia
- **Flow**: Akses halaman produk → Browse kategori → View product details

#### UC-010: View Product Details
- **Actor**: Customer, Guest User
- **Description**: Melihat detail produk termasuk harga, ukuran, stok
- **Flow**: Klik produk → View detail page → Check specifications

#### UC-011: Add to Cart
- **Actor**: Customer, Guest User
- **Description**: Menambahkan produk ke keranjang belanja
- **Precondition**: Produk tersedia dan stok mencukupi
- **Flow**: Pilih produk → Set quantity → Add to cart → Update cart count

#### UC-012: Manage Cart
- **Actor**: Customer, Guest User
- **Description**: Mengelola isi keranjang belanja
- **Includes**: View Cart, Update Quantity, Remove Item, Clear Cart
- **Flow**: Akses cart → Pilih aksi → Update → Refresh total

#### UC-013: Checkout Process
- **Actor**: Customer
- **Description**: Proses pembelian dengan pengisian data dan pemilihan pembayaran
- **Precondition**: Cart tidak kosong, user sudah login
- **Includes**: Fill Customer Data, Select Payment Method, Confirm Order
- **Flow**: Akses checkout → Input data → Pilih pembayaran → Submit order

#### UC-014: WhatsApp Integration
- **Actor**: Customer
- **Description**: Mengirim detail pesanan ke WhatsApp untuk konfirmasi
- **Precondition**: Checkout berhasil
- **Flow**: Submit order → Generate WhatsApp message → Redirect to WhatsApp

#### UC-015: Track Order
- **Actor**: Customer
- **Description**: Melacak status pesanan yang telah dibuat
- **Flow**: Login → Akses menu pesanan → View order status → Track progress

#### UC-016: Mark Order as Completed
- **Actor**: Customer
- **Description**: Menandai pesanan sebagai selesai setelah diterima
- **Precondition**: Order status = 'delivered'
- **Flow**: View order detail → Click complete → Confirm completion

#### UC-017: Submit Testimonial
- **Actor**: Customer
- **Description**: Memberikan testimoni setelah pesanan selesai
- **Precondition**: Order status = 'completed'
- **Flow**: View completed order → Fill testimonial form → Submit

---

### **System Features**

#### UC-018: Persistent Cart
- **Actor**: System
- **Description**: Menyimpan cart data untuk user yang login
- **Flow**: User login → Merge session cart → Persist to database

#### UC-019: Stock Management
- **Actor**: System
- **Description**: Otomatis mengurangi stok saat order dibuat
- **Flow**: Order created → Check stock → Reduce stock → Update inventory

#### UC-020: Session Management
- **Actor**: System
- **Description**: Mengelola session user dan cart untuk guest
- **Flow**: User visit → Create session → Store cart data → Manage expiry

---

## Use Case Relationships

### **Include Relationships**
- UC-013 (Checkout) **includes** UC-014 (WhatsApp Integration)
- UC-007 (Sales Report) **includes** Export to PDF, Export to Excel
- UC-004 (Manage Inventory) **includes** CRUD operations
- UC-012 (Manage Cart) **includes** View, Update, Remove, Clear

### **Extend Relationships**
- UC-017 (Submit Testimonial) **extends** UC-016 (Mark Order Completed)
- UC-018 (Persistent Cart) **extends** UC-011 (Add to Cart)
- UC-019 (Stock Management) **extends** UC-013 (Checkout Process)

### **Generalization**
- Guest User **generalizes to** Customer (after registration/login)
- Basic User **generalizes to** Admin (with elevated privileges)

---

## System Boundaries

### **Web Application Boundary**
- Authentication System
- Product Management
- Cart & Order Management
- Reporting System
- User Interface (Bootstrap-based)

### **External Systems**
- WhatsApp API (for order confirmation)
- Payment Systems (BRI Bank, DANA E-Wallet)
- Email System (for notifications)

---

## Business Rules

1. **Authentication**: Users must login to complete checkout
2. **Authorization**: Only admins can access inventory and sales reports
3. **Stock Control**: Orders cannot exceed available stock
4. **Order Status**: Only delivered orders can be marked as completed
5. **Testimonials**: Only completed orders can receive testimonials
6. **Cart Persistence**: Logged-in users' carts persist across sessions
7. **WhatsApp Integration**: All orders must be confirmed via WhatsApp
8. **Payment Methods**: Support for Bank Transfer (BRI) and E-Wallet (DANA)

---

## Technical Implementation Notes

- **Framework**: Laravel 11 with MVC architecture
- **Authentication**: Laravel's built-in auth with role-based access control
- **Database**: Support for MySQL, PostgreSQL, SQLite
- **Frontend**: Bootstrap 5 for responsive design
- **Session Management**: Database-driven sessions with 120-minute lifetime
- **Security**: CSRF protection, input validation, password hashing
- **Performance**: Redis caching, optimized database queries

---

*Diagram ini menggambarkan semua use case dalam Sistem Manajemen Inventaris Seragam Seragam Sekolah RAVAZKA yang dibangun dengan Laravel 11.*