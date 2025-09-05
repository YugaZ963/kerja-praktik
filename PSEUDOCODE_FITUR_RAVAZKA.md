# PSEUDOCODE FITUR SISTEM RAVAZKA
## Sistem E-Commerce Seragam Sekolah

---

## 1. REGISTRASI & LOGIN PENGGUNA

### Registrasi Pengguna
```pseudocode
BEGIN RegisterUser
    INPUT: name, email, password, password_confirmation
    
    // Validasi input
    IF email already exists THEN
        RETURN error "Email sudah terdaftar"
    END IF
    
    IF password != password_confirmation THEN
        RETURN error "Password tidak cocok"
    END IF
    
    // Hash password dan simpan user
    hashed_password = Hash(password)
    user = CREATE User {
        name: name,
        email: email,
        password: hashed_password,
        role: 'customer'
    }
    
    // Auto login setelah registrasi
    Login(user)
    
    RETURN redirect to dashboard
END RegisterUser
```

### Login Pengguna
```pseudocode
BEGIN LoginUser
    INPUT: email, password
    
    // Cari user berdasarkan email
    user = FIND User WHERE email = email
    
    IF user NOT EXISTS THEN
        RETURN error "Email tidak ditemukan"
    END IF
    
    // Verifikasi password
    IF NOT VerifyPassword(password, user.password) THEN
        RETURN error "Password salah"
    END IF
    
    // Set session dan merge cart jika ada
    SetUserSession(user)
    MergeGuestCartToUser(user.id)
    
    IF user.role = 'admin' THEN
        RETURN redirect to admin dashboard
    ELSE
        RETURN redirect to customer dashboard
    END IF
END LoginUser
```

---

## 2. BROWSE & SEARCH PRODUK

### Tampilkan Katalog Produk
```pseudocode
BEGIN BrowseProducts
    INPUT: search_query, category_filter, size_filter, sort_by, page
    
    // Build query dengan filter
    query = SELECT * FROM products WHERE active = true
    
    IF search_query NOT EMPTY THEN
        query = query AND (name LIKE '%search_query%' OR description LIKE '%search_query%')
    END IF
    
    IF category_filter NOT EMPTY THEN
        query = query AND category = category_filter
    END IF
    
    IF size_filter NOT EMPTY THEN
        query = query AND size = size_filter
    END IF
    
    // Apply sorting
    SWITCH sort_by
        CASE 'price_asc': ORDER BY price ASC
        CASE 'price_desc': ORDER BY price DESC
        CASE 'name_asc': ORDER BY name ASC
        CASE 'name_desc': ORDER BY name DESC
        DEFAULT: ORDER BY created_at DESC
    END SWITCH
    
    // Pagination
    products = PAGINATE query BY 12 items per page
    
    RETURN products with pagination info
END BrowseProducts
```

### Detail Produk
```pseudocode
BEGIN ShowProductDetail
    INPUT: product_id
    
    product = FIND Product WHERE id = product_id AND active = true
    
    IF product NOT EXISTS THEN
        RETURN error "Produk tidak ditemukan"
    END IF
    
    // Ambil stok dari inventory
    inventory = FIND Inventory WHERE product_id = product_id
    product.stock = inventory.stock
    
    // Ambil produk terkait (same category)
    related_products = SELECT * FROM products 
                      WHERE category = product.category 
                      AND id != product_id 
                      LIMIT 4
    
    RETURN product, related_products
END ShowProductDetail
```

---

## 3. KERANJANG BELANJA

### Tambah ke Keranjang
```pseudocode
BEGIN AddToCart
    INPUT: product_id, quantity
    
    product = FIND Product WHERE id = product_id
    
    IF product NOT EXISTS THEN
        RETURN error "Produk tidak ditemukan"
    END IF
    
    // Cek stok
    inventory = FIND Inventory WHERE product_id = product_id
    IF inventory.stock < quantity THEN
        RETURN error "Stok tidak mencukupi"
    END IF
    
    user_id = GetCurrentUserId()
    session_id = GetSessionId()
    
    // Cari cart item yang sudah ada
    IF user_id NOT NULL THEN
        cart_item = FIND Cart WHERE user_id = user_id AND product_id = product_id
    ELSE
        cart_item = FIND Cart WHERE session_id = session_id AND product_id = product_id
    END IF
    
    IF cart_item EXISTS THEN
        // Update quantity
        new_quantity = cart_item.quantity + quantity
        IF inventory.stock < new_quantity THEN
            RETURN error "Total quantity melebihi stok"
        END IF
        cart_item.quantity = new_quantity
        cart_item.total = new_quantity * product.price
        UPDATE cart_item
    ELSE
        // Buat cart item baru
        CREATE Cart {
            user_id: user_id,
            session_id: session_id,
            product_id: product_id,
            quantity: quantity,
            price: product.price,
            total: quantity * product.price
        }
    END IF
    
    RETURN success "Produk ditambahkan ke keranjang"
END AddToCart
```

### Lihat Keranjang
```pseudocode
BEGIN ViewCart
    user_id = GetCurrentUserId()
    session_id = GetSessionId()
    
    IF user_id NOT NULL THEN
        cart_items = SELECT * FROM carts 
                    JOIN products ON carts.product_id = products.id 
                    WHERE carts.user_id = user_id
    ELSE
        cart_items = SELECT * FROM carts 
                    JOIN products ON carts.product_id = products.id 
                    WHERE carts.session_id = session_id AND carts.user_id IS NULL
    END IF
    
    total_amount = SUM(cart_items.total)
    total_items = SUM(cart_items.quantity)
    
    RETURN cart_items, total_amount, total_items
END ViewCart
```

---

## 4. CHECKOUT & PEMBAYARAN

### Proses Checkout
```pseudocode
BEGIN ProcessCheckout
    INPUT: customer_data, payment_method, shipping_option
    
    user_id = GetCurrentUserId()
    IF user_id IS NULL THEN
        RETURN error "Harus login untuk checkout"
    END IF
    
    // Ambil cart items
    cart_items = GetCartItems(user_id)
    IF cart_items IS EMPTY THEN
        RETURN error "Keranjang kosong"
    END IF
    
    BEGIN TRANSACTION
    
    // Validasi stok semua produk
    FOR EACH item IN cart_items DO
        inventory = FIND Inventory WHERE product_id = item.product_id
        IF inventory.stock < item.quantity THEN
            ROLLBACK TRANSACTION
            RETURN error "Stok produk " + item.product.name + " tidak mencukupi"
        END IF
    END FOR
    
    // Hitung total
    subtotal = SUM(cart_items.total)
    shipping_cost = CalculateShippingCost(shipping_option)
    total_amount = subtotal + shipping_cost
    
    // Generate order number
    order_number = "ORD" + CurrentDate("Ymd") + RandomNumber(4)
    
    // Buat order
    order = CREATE Order {
        order_number: order_number,
        user_id: user_id,
        customer_name: customer_data.name,
        customer_phone: customer_data.phone,
        customer_address: customer_data.address,
        payment_method: payment_method,
        shipping_option: shipping_option,
        subtotal: subtotal,
        shipping_cost: shipping_cost,
        total_amount: total_amount,
        status: 'pending'
    }
    
    // Buat order items
    FOR EACH item IN cart_items DO
        CREATE OrderItem {
            order_id: order.id,
            product_id: item.product_id,
            product_name: item.product.name,
            product_size: item.product.size,
            quantity: item.quantity,
            price: item.price,
            total: item.total
        }
    END FOR
    
    COMMIT TRANSACTION
    
    // Kosongkan keranjang
    DELETE FROM carts WHERE user_id = user_id
    
    // Generate WhatsApp message
    whatsapp_message = GenerateWhatsAppMessage(order, cart_items)
    whatsapp_url = "https://wa.me/6289677754918?text=" + URLEncode(whatsapp_message)
    
    RETURN redirect to whatsapp_url
END ProcessCheckout
```

---

## 5. MANAJEMEN PESANAN (CUSTOMER)

### Lihat Daftar Pesanan
```pseudocode
BEGIN ViewCustomerOrders
    user_id = GetCurrentUserId()
    
    orders = SELECT * FROM orders 
            WHERE user_id = user_id 
            ORDER BY created_at DESC
    
    FOR EACH order IN orders DO
        order.items = SELECT * FROM order_items WHERE order_id = order.id
        order.status_label = GetStatusLabel(order.status)
        order.can_upload_payment = (order.status = 'pending')
        order.can_complete = (order.status = 'delivered')
    END FOR
    
    RETURN orders
END ViewCustomerOrders
```

### Detail Pesanan
```pseudocode
BEGIN ViewOrderDetail
    INPUT: order_id
    
    user_id = GetCurrentUserId()
    order = FIND Order WHERE id = order_id AND user_id = user_id
    
    IF order NOT EXISTS THEN
        RETURN error "Pesanan tidak ditemukan"
    END IF
    
    order.items = SELECT * FROM order_items WHERE order_id = order_id
    order.timeline = GenerateOrderTimeline(order)
    
    RETURN order
END ViewOrderDetail
```

---

## 6. UPLOAD BUKTI PEMBAYARAN

### Upload Bukti Pembayaran
```pseudocode
BEGIN UploadPaymentProof
    INPUT: order_id, payment_proof_file
    
    user_id = GetCurrentUserId()
    order = FIND Order WHERE id = order_id AND user_id = user_id
    
    IF order NOT EXISTS THEN
        RETURN error "Pesanan tidak ditemukan"
    END IF
    
    IF order.status != 'pending' THEN
        RETURN error "Pesanan tidak dalam status pending"
    END IF
    
    // Validasi file
    allowed_types = ['jpg', 'jpeg', 'png', 'pdf']
    file_extension = GetFileExtension(payment_proof_file)
    
    IF file_extension NOT IN allowed_types THEN
        RETURN error "Format file tidak didukung"
    END IF
    
    IF GetFileSize(payment_proof_file) > 5MB THEN
        RETURN error "Ukuran file terlalu besar"
    END IF
    
    // Upload file
    filename = order.order_number + "_" + CurrentTimestamp() + "." + file_extension
    file_path = UploadFile(payment_proof_file, "payment_proofs/", filename)
    
    // Update order
    UPDATE Order SET 
        payment_proof = file_path,
        status = 'payment_uploaded',
        payment_uploaded_at = CurrentTimestamp()
    WHERE id = order_id
    
    RETURN success "Bukti pembayaran berhasil diupload"
END UploadPaymentProof
```

---

## 7. DASHBOARD ADMIN

### Dashboard Analytics
```pseudocode
BEGIN AdminDashboard
    // Statistik dasar
    total_orders = COUNT(*) FROM orders
    total_revenue = SUM(total_amount) FROM orders WHERE status IN ('completed', 'delivered')
    pending_orders = COUNT(*) FROM orders WHERE status = 'pending'
    low_stock_products = COUNT(*) FROM inventories WHERE stock <= 10
    
    // Data untuk grafik penjualan (30 hari terakhir)
    sales_data = []
    FOR i = 29 DOWN TO 0 DO
        date = CurrentDate() - i days
        daily_sales = SUM(total_amount) FROM orders 
                     WHERE DATE(created_at) = date 
                     AND status IN ('completed', 'delivered')
        sales_data.ADD({date: date, amount: daily_sales})
    END FOR
    
    // Top selling products
    top_products = SELECT p.name, SUM(oi.quantity) as total_sold
                  FROM products p
                  JOIN order_items oi ON p.id = oi.product_id
                  JOIN orders o ON oi.order_id = o.id
                  WHERE o.status IN ('completed', 'delivered')
                  GROUP BY p.id
                  ORDER BY total_sold DESC
                  LIMIT 5
    
    // Recent orders
    recent_orders = SELECT * FROM orders 
                   ORDER BY created_at DESC 
                   LIMIT 10
    
    RETURN {
        total_orders: total_orders,
        total_revenue: total_revenue,
        pending_orders: pending_orders,
        low_stock_products: low_stock_products,
        sales_data: sales_data,
        top_products: top_products,
        recent_orders: recent_orders
    }
END AdminDashboard
```

---

## 8. MANAJEMEN INVENTARIS

### Lihat Inventaris
```pseudocode
BEGIN ViewInventory
    INPUT: search, category_filter, stock_status, sort_by
    
    query = SELECT i.*, p.name, p.category, p.size, p.price
           FROM inventories i
           JOIN products p ON i.product_id = p.id
    
    IF search NOT EMPTY THEN
        query = query WHERE (p.name LIKE '%search%' OR p.category LIKE '%search%')
    END IF
    
    IF category_filter NOT EMPTY THEN
        query = query AND p.category = category_filter
    END IF
    
    IF stock_status = 'low' THEN
        query = query AND i.stock <= 10
    ELSE IF stock_status = 'out' THEN
        query = query AND i.stock = 0
    ELSE IF stock_status = 'available' THEN
        query = query AND i.stock > 10
    END IF
    
    // Apply sorting
    SWITCH sort_by
        CASE 'stock_asc': ORDER BY i.stock ASC
        CASE 'stock_desc': ORDER BY i.stock DESC
        CASE 'name_asc': ORDER BY p.name ASC
        DEFAULT: ORDER BY p.name ASC
    END SWITCH
    
    inventories = PAGINATE query BY 15 items per page
    
    RETURN inventories
END ViewInventory
```

### Update Stok
```pseudocode
BEGIN UpdateStock
    INPUT: inventory_id, new_stock, adjustment_type, notes
    
    inventory = FIND Inventory WHERE id = inventory_id
    
    IF inventory NOT EXISTS THEN
        RETURN error "Inventaris tidak ditemukan"
    END IF
    
    IF new_stock < 0 THEN
        RETURN error "Stok tidak boleh negatif"
    END IF
    
    old_stock = inventory.stock
    
    BEGIN TRANSACTION
    
    // Update inventory
    UPDATE Inventory SET 
        stock = new_stock,
        last_updated = CurrentTimestamp()
    WHERE id = inventory_id
    
    // Log stock movement
    CREATE StockMovement {
        inventory_id: inventory_id,
        type: adjustment_type,
        old_stock: old_stock,
        new_stock: new_stock,
        difference: new_stock - old_stock,
        notes: notes,
        created_by: GetCurrentUserId()
    }
    
    COMMIT TRANSACTION
    
    RETURN success "Stok berhasil diperbarui"
END UpdateStock
```

---

## 9. MANAJEMEN PESANAN ADMIN

### Lihat Semua Pesanan
```pseudocode
BEGIN ViewAllOrders
    INPUT: status_filter, date_from, date_to, search
    
    query = SELECT * FROM orders
    
    IF status_filter NOT EMPTY THEN
        query = query WHERE status = status_filter
    END IF
    
    IF date_from NOT EMPTY THEN
        query = query AND DATE(created_at) >= date_from
    END IF
    
    IF date_to NOT EMPTY THEN
        query = query AND DATE(created_at) <= date_to
    END IF
    
    IF search NOT EMPTY THEN
        query = query AND (order_number LIKE '%search%' OR customer_name LIKE '%search%')
    END IF
    
    orders = PAGINATE query ORDER BY created_at DESC BY 20 items per page
    
    FOR EACH order IN orders DO
        order.items_count = COUNT(*) FROM order_items WHERE order_id = order.id
    END FOR
    
    RETURN orders
END ViewAllOrders
```

### Update Status Pesanan
```pseudocode
BEGIN UpdateOrderStatus
    INPUT: order_id, new_status, notes
    
    order = FIND Order WHERE id = order_id
    
    IF order NOT EXISTS THEN
        RETURN error "Pesanan tidak ditemukan"
    END IF
    
    // Validasi status flow
    valid_transitions = {
        'pending': ['payment_uploaded', 'cancelled'],
        'payment_uploaded': ['confirmed', 'cancelled'],
        'confirmed': ['processing', 'cancelled'],
        'processing': ['shipped'],
        'shipped': ['delivered'],
        'delivered': ['completed'],
        'cancelled': [],
        'completed': []
    }
    
    IF new_status NOT IN valid_transitions[order.status] THEN
        RETURN error "Transisi status tidak valid"
    END IF
    
    BEGIN TRANSACTION
    
    // Update order status
    UPDATE Order SET 
        status = new_status,
        updated_at = CurrentTimestamp()
    WHERE id = order_id
    
    // Jika status delivered, kurangi stok
    IF new_status = 'delivered' THEN
        order_items = SELECT * FROM order_items WHERE order_id = order_id
        FOR EACH item IN order_items DO
            UPDATE Inventory SET stock = stock - item.quantity
            WHERE product_id = item.product_id
        END FOR
    END IF
    
    // Log status change
    CREATE OrderStatusLog {
        order_id: order_id,
        old_status: order.status,
        new_status: new_status,
        notes: notes,
        changed_by: GetCurrentUserId()
    }
    
    COMMIT TRANSACTION
    
    RETURN success "Status pesanan berhasil diperbarui"
END UpdateOrderStatus
```

---

## 10. LAPORAN PENJUALAN

### Generate Laporan Penjualan
```pseudocode
BEGIN GenerateSalesReport
    INPUT: date_from, date_to, category_filter, export_format
    
    // Default date range jika tidak diisi
    IF date_from IS EMPTY THEN
        date_from = FirstDayOfCurrentMonth()
    END IF
    
    IF date_to IS EMPTY THEN
        date_to = CurrentDate()
    END IF
    
    // Query data penjualan
    query = SELECT o.*, oi.product_name, oi.quantity, oi.total, p.category
           FROM orders o
           JOIN order_items oi ON o.id = oi.order_id
           JOIN products p ON oi.product_id = p.id
           WHERE o.status IN ('completed', 'delivered')
           AND DATE(o.created_at) BETWEEN date_from AND date_to
    
    IF category_filter NOT EMPTY THEN
        query = query AND p.category = category_filter
    END IF
    
    sales_data = EXECUTE query
    
    // Hitung statistik
    total_orders = COUNT(DISTINCT o.id) FROM sales_data
    total_revenue = SUM(o.total_amount) FROM sales_data
    total_items_sold = SUM(oi.quantity) FROM sales_data
    
    // Group by product untuk top selling
    top_products = GROUP sales_data BY product_name
                  ORDER BY SUM(quantity) DESC
                  LIMIT 10
    
    // Group by date untuk trend
    daily_sales = GROUP sales_data BY DATE(created_at)
                 SELECT date, SUM(total_amount) as revenue, COUNT(DISTINCT order_id) as orders
    
    report_data = {
        period: {from: date_from, to: date_to},
        summary: {
            total_orders: total_orders,
            total_revenue: total_revenue,
            total_items_sold: total_items_sold,
            average_order_value: total_revenue / total_orders
        },
        top_products: top_products,
        daily_sales: daily_sales,
        detailed_sales: sales_data
    }
    
    IF export_format = 'excel' THEN
        RETURN ExportToExcel(report_data)
    ELSE IF export_format = 'pdf' THEN
        RETURN ExportToPDF(report_data)
    ELSE
        RETURN report_data
    END IF
END GenerateSalesReport
```

---

## 11. LAPORAN INVENTARIS

### Generate Laporan Inventaris
```pseudocode
BEGIN GenerateInventoryReport
    INPUT: export_format, include_zero_stock
    
    query = SELECT i.*, p.name, p.category, p.size, p.price,
                  (i.stock * p.price) as stock_value
           FROM inventories i
           JOIN products p ON i.product_id = p.id
    
    IF NOT include_zero_stock THEN
        query = query WHERE i.stock > 0
    END IF
    
    inventory_data = EXECUTE query ORDER BY p.category, p.name
    
    // Hitung statistik
    total_products = COUNT(*) FROM inventory_data
    total_stock_value = SUM(stock_value) FROM inventory_data
    low_stock_count = COUNT(*) FROM inventory_data WHERE stock <= 10
    out_of_stock_count = COUNT(*) FROM inventory_data WHERE stock = 0
    
    // Group by category
    category_summary = GROUP inventory_data BY category
                      SELECT category, 
                             COUNT(*) as product_count,
                             SUM(stock) as total_stock,
                             SUM(stock_value) as total_value
    
    report_data = {
        generated_at: CurrentTimestamp(),
        summary: {
            total_products: total_products,
            total_stock_value: total_stock_value,
            low_stock_count: low_stock_count,
            out_of_stock_count: out_of_stock_count
        },
        category_summary: category_summary,
        detailed_inventory: inventory_data
    }
    
    IF export_format = 'excel' THEN
        RETURN ExportInventoryToExcel(report_data)
    ELSE IF export_format = 'pdf' THEN
        RETURN ExportInventoryToPDF(report_data)
    ELSE
        RETURN report_data
    END IF
END GenerateInventoryReport
```

---

## 12. INTEGRASI WHATSAPP

### Generate WhatsApp Message
```pseudocode
BEGIN GenerateWhatsAppMessage
    INPUT: order, order_items, customer_data
    
    message = "🛍️ *PESANAN BARU - RAVAZKA*\n\n"
    message += "📋 *Detail Pesanan:*\n"
    message += "• No. Pesanan: " + order.order_number + "\n"
    message += "• Tanggal: " + FormatDate(order.created_at) + "\n\n"
    
    message += "👤 *Data Customer:*\n"
    message += "• Nama: " + customer_data.name + "\n"
    message += "• No. HP: " + customer_data.phone + "\n"
    message += "• Alamat: " + customer_data.address + "\n\n"
    
    message += "🛒 *Produk yang Dipesan:*\n"
    FOR EACH item IN order_items DO
        message += "• " + item.product_name + " (" + item.product_size + ")\n"
        message += "  Qty: " + item.quantity + " x " + FormatCurrency(item.price)
        message += " = " + FormatCurrency(item.total) + "\n"
    END FOR
    
    message += "\n💰 *Ringkasan Pembayaran:*\n"
    message += "• Subtotal: " + FormatCurrency(order.subtotal) + "\n"
    message += "• Ongkir (" + order.shipping_option + "): " + FormatCurrency(order.shipping_cost) + "\n"
    message += "• *Total: " + FormatCurrency(order.total_amount) + "*\n\n"
    
    message += "💳 *Metode Pembayaran:*\n"
    IF order.payment_method = 'bank_transfer' THEN
        message += "Bank Transfer - BRI\n"
        message += "No. Rek: 1234-5678-9012-3456\n"
        message += "A.n: RAVAZKA STORE\n\n"
    ELSE IF order.payment_method = 'ewallet' THEN
        message += "E-Wallet - DANA\n"
        message += "No. HP: 089677754918\n\n"
    END IF
    
    message += "📝 *Instruksi:*\n"
    message += "1. Silakan lakukan pembayaran sesuai total di atas\n"
    message += "2. Upload bukti pembayaran di website\n"
    message += "3. Pesanan akan diproses setelah pembayaran dikonfirmasi\n\n"
    
    message += "Terima kasih telah berbelanja di RAVAZKA! 🙏"
    
    RETURN message
END GenerateWhatsAppMessage
```

### Redirect ke WhatsApp
```pseudocode
BEGIN RedirectToWhatsApp
    INPUT: message
    
    whatsapp_number = "6289677754918"
    encoded_message = URLEncode(message)
    whatsapp_url = "https://wa.me/" + whatsapp_number + "?text=" + encoded_message
    
    RETURN redirect to whatsapp_url
END RedirectToWhatsApp
```

---

## 13. HALAMAN KONTAK & ABOUT

### Halaman About
```pseudocode
BEGIN ShowAboutPage
    about_content = {
        company_name: "RAVAZKA",
        description: "Toko seragam sekolah terpercaya dengan kualitas terbaik",
        established_year: "2020",
        vision: "Menjadi penyedia seragam sekolah terdepan di Indonesia",
        mission: [
            "Menyediakan seragam berkualitas tinggi",
            "Memberikan pelayanan terbaik kepada pelanggan",
            "Mendukung pendidikan dengan produk berkualitas"
        ],
        values: [
            "Kualitas",
            "Kepercayaan",
            "Pelayanan Prima"
        ]
    }
    
    RETURN RenderView("about", about_content)
END ShowAboutPage
```

### Halaman Kontak
```pseudocode
BEGIN ShowContactPage
    contact_info = {
        address: "Jl. Pendidikan No. 123, Jakarta Selatan",
        phone: "(021) 1234-5678",
        whatsapp: "089677754918",
        email: "info@ravazka.com",
        business_hours: {
            weekdays: "08:00 - 17:00 WIB",
            saturday: "08:00 - 15:00 WIB",
            sunday: "Tutup"
        },
        maps_coordinates: {
            latitude: -6.2088,
            longitude: 106.8456
        }
    }
    
    RETURN RenderView("contact", contact_info)
END ShowContactPage
```

### Submit Form Kontak
```pseudocode
BEGIN SubmitContactForm
    INPUT: name, email, subject, message
    
    // Validasi input
    IF name IS EMPTY THEN
        RETURN error "Nama harus diisi"
    END IF
    
    IF NOT IsValidEmail(email) THEN
        RETURN error "Email tidak valid"
    END IF
    
    IF message IS EMPTY THEN
        RETURN error "Pesan harus diisi"
    END IF
    
    // Simpan ke database
    CREATE ContactMessage {
        name: name,
        email: email,
        subject: subject,
        message: message,
        status: 'unread',
        created_at: CurrentTimestamp()
    }
    
    // Kirim email notifikasi ke admin (optional)
    SendEmailNotification({
        to: "admin@ravazka.com",
        subject: "Pesan Kontak Baru: " + subject,
        body: "Dari: " + name + " (" + email + ")\n\n" + message
    })
    
    RETURN success "Pesan Anda telah terkirim. Terima kasih!"
END SubmitContactForm
```

---

## HELPER FUNCTIONS

### Utility Functions
```pseudocode
FUNCTION CalculateShippingCost(shipping_option)
    IF shipping_option = 'express' THEN
        RETURN 15000
    ELSE
        RETURN 0
    END IF
END FUNCTION

FUNCTION FormatCurrency(amount)
    RETURN "Rp " + NumberFormat(amount, 0, ".", ".")
END FUNCTION

FUNCTION GenerateOrderTimeline(order)
    timeline = []
    
    timeline.ADD({status: "pending", label: "Pesanan Dibuat", date: order.created_at})
    
    IF order.payment_uploaded_at NOT NULL THEN
        timeline.ADD({status: "payment_uploaded", label: "Bukti Pembayaran Diupload", date: order.payment_uploaded_at})
    END IF
    
    IF order.confirmed_at NOT NULL THEN
        timeline.ADD({status: "confirmed", label: "Pembayaran Dikonfirmasi", date: order.confirmed_at})
    END IF
    
    IF order.shipped_at NOT NULL THEN
        timeline.ADD({status: "shipped", label: "Pesanan Dikirim", date: order.shipped_at})
    END IF
    
    IF order.delivered_at NOT NULL THEN
        timeline.ADD({status: "delivered", label: "Pesanan Diterima", date: order.delivered_at})
    END IF
    
    RETURN timeline
END FUNCTION

FUNCTION MergeGuestCartToUser(user_id)
    session_id = GetSessionId()
    
    // Ambil cart guest
    guest_cart = SELECT * FROM carts WHERE session_id = session_id AND user_id IS NULL
    
    FOR EACH item IN guest_cart DO
        // Cek apakah user sudah punya produk yang sama
        existing_cart = FIND Cart WHERE user_id = user_id AND product_id = item.product_id
        
        IF existing_cart EXISTS THEN
            // Gabungkan quantity
            existing_cart.quantity += item.quantity
            existing_cart.total = existing_cart.quantity * existing_cart.price
            UPDATE existing_cart
        ELSE
            // Pindahkan ke user
            item.user_id = user_id
            item.session_id = NULL
            UPDATE item
        END IF
    END FOR
    
    // Hapus sisa cart guest
    DELETE FROM carts WHERE session_id = session_id AND user_id IS NULL
END FUNCTION
```

---

**Catatan:**
- Semua pseudocode di atas mengikuti struktur dan logika bisnis sistem RAVAZKA
- Implementasi menggunakan Laravel 11 dengan Eloquent ORM
- Database transactions digunakan untuk operasi critical
- Validasi input dan error handling diterapkan di setiap fungsi
- WhatsApp integration menggunakan URL scheme wa.me
- File upload menggunakan Laravel Storage dengan validasi keamanan