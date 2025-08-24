# Arsitektur Sistem RAVAZKA

## 🏗️ Overview Arsitektur

Sistem RAVAZKA adalah aplikasi web e-commerce untuk toko seragam sekolah yang dibangun menggunakan arsitektur **MVC (Model-View-Controller)** dengan Laravel 11.

## 📊 Diagram Arsitektur

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   PRESENTATION  │    │    BUSINESS     │    │      DATA       │
│     LAYER       │    │     LAYER       │    │     LAYER       │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • Blade Views   │◄──►│ • Controllers   │◄──►│ • Models        │
│ • Components    │    │ • Middleware    │    │ • Database      │
│ • Layouts       │    │ • Services      │    │ • Migrations    │
│ • Assets        │    │ • Validation    │    │ • Seeders       │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         ▲                       ▲                       ▲
         │                       │                       │
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     ROUTING     │    │   AUTHENTICATION│    │    STORAGE      │
│                 │    │   & SECURITY    │    │                 │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ • Web Routes    │    │ • Auth System   │    │ • File Storage  │
│ • Route Groups  │    │ • CSRF Token    │    │ • Session Store │
│ • Middleware    │    │ • Role-based    │    │ • Cache         │
│ • Parameters    │    │   Access        │    │ • Logs          │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 🎯 Pola Arsitektur

### 1. **MVC Pattern**
- **Model**: Mengelola data dan business logic
- **View**: Menampilkan interface kepada user
- **Controller**: Menghubungkan Model dan View

### 2. **Repository Pattern**
- Abstraksi akses data melalui Eloquent ORM
- Memisahkan logic bisnis dari akses database

### 3. **Service Layer Pattern**
- Encapsulation logic bisnis kompleks
- Reusable business operations

### 4. **Middleware Pattern**
- Request filtering dan validation
- Authentication dan authorization

## 🔧 Komponen Utama

### **Frontend Layer**
```
resources/views/
├── layouts/           # Template dasar
│   └── app.blade.php  # Main layout dengan navbar & footer
├── components/        # Reusable components
│   ├── navbar.blade.php
│   └── footer.blade.php
├── auth/             # Authentication views
│   ├── login.blade.php
│   └── register.blade.php
├── admin/            # Admin interface
│   ├── dashboard.blade.php
│   ├── inventory/    # Inventory management
│   ├── orders/       # Order management
│   └── sales/        # Sales reports
├── customer/         # Customer interface
│   └── orders/       # Customer order views
├── cart/             # Shopping cart
│   ├── index.blade.php
│   └── checkout.blade.php
├── public/           # Public pages
│   ├── welcome.blade.php
│   ├── about.blade.php
│   └── products.blade.php
└── partials/         # Partial views
```

### **Backend Layer**
```
app/
├── Http/
│   ├── Controllers/   # Request handlers
│   │   ├── Admin/     # Admin controllers
│   │   │   ├── OrderController.php
│   │   │   └── SalesReportController.php
│   │   ├── Customer/  # Customer controllers
│   │   │   ├── OrderController.php
│   │   │   └── ProductController.php
│   │   ├── AuthController.php
│   │   ├── CartController.php
│   │   ├── InventoryController.php
│   │   ├── ProductController.php
│   │   ├── TestimonialController.php
│   │   └── WelcomeController.php
│   └── Middleware/    # Request filters
├── Models/           # Data entities
│   ├── User.php      # User model dengan roles
│   ├── Product.php   # Product model
│   ├── Inventory.php # Inventory model
│   ├── Cart.php      # Shopping cart model
│   ├── Order.php     # Order model
│   ├── OrderItem.php # Order items model
│   └── Testimonial.php # Testimonial model
├── Services/         # Business logic
│   └── GoogleMapsService.php
├── Helpers/          # Helper classes
│   ├── BreadcrumbHelper.php
│   └── ImageHelper.php
├── Exports/          # Excel exports
│   └── InventoryExport.php
└── Providers/        # Service providers
    └── AppServiceProvider.php
```

### **Data Layer**
```
database/
├── migrations/       # Database schema
├── seeders/         # Initial data
└── factories/       # Test data generators
```

## 🔐 Security Architecture

### **Authentication Flow**
```
User Request → Middleware → Controller → Model → Database
     ↓              ↓           ↓         ↓        ↓
  CSRF Check → Auth Check → Validation → Query → Response
```

### **Authorization Levels**
1. **Guest**: Akses terbatas (view products, register)
2. **User**: Customer features (orders, cart, profile)
3. **Admin**: Full system access (inventory, reports, orders)

### **Security Features**
- ✅ CSRF Protection
- ✅ Password Hashing (bcrypt)
- ✅ Input Validation
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection (Blade templating)
- ✅ Role-based Access Control

## 📊 Data Flow Architecture

### **Request Lifecycle**
```
1. HTTP Request
   ↓
2. Route Resolution
   ↓
3. Middleware Stack
   ↓
4. Controller Action
   ↓
5. Model Interaction
   ↓
6. Database Query
   ↓
7. View Rendering
   ↓
8. HTTP Response
```

### **Database Relations**
```
Users ──┐
        ├── Orders ── OrderItems ── Products ── Inventories
        ├── Carts ─────────────────┘              │
        └── Testimonials                          │
                                                  │
Categories (implicit) ────────────────────────────┘

# Detailed Relations:
# Users (1:N) → Orders
# Users (1:N) → Carts (persistent cart)
# Users (1:N) → Testimonials
# Orders (1:N) → OrderItems
# Products (1:1) → Inventories
# OrderItems (N:1) → Products
# Carts (N:1) → Products
```

## 🚀 Fitur Sistem Terkini

### **E-Commerce Features**
- ✅ **Product Catalog**: Multi-category dengan filtering
- ✅ **Shopping Cart**: Session & user-based persistence
- ✅ **Checkout Process**: Multi-step dengan validasi
- ✅ **Order Management**: Status tracking & updates
- ✅ **Payment Integration**: Bank Transfer & E-Wallet
- ✅ **WhatsApp Integration**: Auto order confirmation
- ✅ **Testimonial System**: Customer feedback

### **Admin Dashboard**
- ✅ **Analytics Dashboard**: Real-time statistics
- ✅ **Inventory Management**: CRUD dengan stock monitoring
- ✅ **Order Processing**: Status updates & payment proof
- ✅ **Sales Reports**: PDF/Excel export dengan charts
- ✅ **Product Management**: Multi-size & category support
- ✅ **User Management**: Role-based access control

### **Security & Performance**
- ✅ **Authentication**: Laravel Sanctum dengan roles
- ✅ **Authorization**: Middleware-based permissions
- ✅ **Data Validation**: Form requests & rules
- ✅ **CSRF Protection**: Built-in Laravel security
- ✅ **Session Management**: Database-driven sessions
- ✅ **Caching Strategy**: File-based caching

## 🚀 Deployment Architecture

### **Development Environment**
- **Web Server**: Laravel Development Server (php artisan serve)
- **Database**: MySQL (via Laragon/XAMPP)
- **Cache**: File-based caching
- **Session**: Database sessions
- **Assets**: Vite development server

### **Production Ready Features**
- ✅ Environment Configuration (.env)
- ✅ Database Migrations & Seeders
- ✅ Asset Compilation (Vite)
- ✅ Error Handling & Logging
- ✅ Performance Optimization
- ✅ Security Best Practices
- ✅ Backup & Recovery Strategy

## 📈 Scalability Considerations

### **Current Architecture Supports**
- Horizontal scaling (multiple app instances)
- Database optimization (indexes, relations)
- Caching strategies (Redis ready)
- CDN integration (asset optimization)

### **Performance Optimizations**
- ✅ Eloquent Eager Loading
- ✅ Database Indexing
- ✅ Asset Minification
- ✅ Image Optimization
- 🚧 Query Caching
- 🚧 Redis Integration

## 🔄 Integration Points

### **External Services**
- **Google Maps API**: Location services
- **Email Service**: Notifications (configurable)
- **Payment Gateway**: Ready for integration
- **File Storage**: Local/Cloud ready

### **API Readiness**
- RESTful architecture foundation
- JSON response capability
- API middleware support
- Rate limiting ready

## 📝 Design Patterns Used

1. **Singleton**: Service providers, configurations
2. **Factory**: Model factories, database seeders
3. **Observer**: Model events (future implementation)
4. **Strategy**: Payment methods, shipping options
5. **Facade**: Laravel service access
6. **Dependency Injection**: Controller dependencies

---

## 🎯 Key Architectural Decisions

### **Why Laravel 11?**
- Mature ecosystem
- Built-in security features
- Excellent ORM (Eloquent)
- Rich templating (Blade)
- Strong community support

### **Why Bootstrap 5?**
- Responsive design
- Component-based UI
- Consistent styling
- Mobile-first approach

### **Why MySQL?**
- ACID compliance
- Excellent Laravel integration
- Proven scalability
- Rich ecosystem

Arsitektur ini dirancang untuk mendukung pertumbuhan bisnis dengan maintainability yang tinggi dan performa yang optimal.