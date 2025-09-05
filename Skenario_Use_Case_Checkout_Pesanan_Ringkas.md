# Skenario Use Case Checkout Pesanan - Ringkas

**Nomor:** REQ-APITS-F-09  
**Nama:** Checkout Pesanan  
**Tujuan:** Customer dapat menyelesaikan proses pembelian dengan mengisi data pelanggan dan memilih metode pembayaran  
**Deskripsi:** Customer dapat melakukan checkout pesanan seragam sekolah dari keranjang belanja RAVAZKA dengan konfirmasi via WhatsApp  
**Aktor:** Customer  

## Skenario Utama

**Kondisi Awal:** Customer sudah login dan memiliki item seragam sekolah di keranjang belanja RAVAZKA

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Customer mengakses halaman checkout dari keranjang | Sistem menampilkan halaman checkout dengan form data pelanggan dan ringkasan pesanan dengan total pembayaran yang harus dibayar |
| 2 | Customer mengisi data pelanggan (nama, nomor WhatsApp, alamat) dan memilih metode pembayaran (Bank BRI atau DANA) | Sistem memvalidasi input data pelanggan, metode pembayaran yang dipilih, dan menampilkan preview pesanan sebelum konfirmasi |
| 3 | Customer menambahkan catatan tambahan (opsional) dan menekan tombol "Kirim Pesanan via WhatsApp" | Sistem membuat order baru dengan status pending, membuat order items, memvalidasi stok produk, dan mengosongkan keranjang setelah order berhasil |
| 4 | Customer menunggu redirect ke WhatsApp untuk konfirmasi pesanan dengan tim RAVAZKA | Sistem generate pesan WhatsApp dengan detail pesanan lengkap (produk, total, data customer, metode pembayaran) dan redirect ke WhatsApp untuk konfirmasi |

**Kondisi Akhir:** Customer berhasil membuat pesanan seragam sekolah dan diarahkan ke WhatsApp untuk konfirmasi pembayaran dan pengiriman dengan tim RAVAZKA

---

**Catatan Tambahan:**
- Sistem mendukung metode pembayaran Bank BRI dan DANA
- Validasi stok real-time saat checkout untuk mencegah overselling
- Integrasi WhatsApp untuk konfirmasi pesanan dan komunikasi dengan customer
- Auto-clear keranjang setelah checkout berhasil untuk mencegah duplikasi order