# Skenario Use Case Keranjang Belanja - Ringkas

**Nomor:** REQ-APITS-F-08  
**Nama:** Keranjang Belanja  
**Tujuan:** Customer dapat menambah, mengupdate, dan menghapus item di keranjang  
**Deskripsi:** Customer dapat mengelola produk seragam sekolah dalam keranjang belanja RAVAZKA sebelum melakukan checkout  
**Aktor:** Customer  

## Skenario Utama

**Kondisi Awal:** Customer berada di halaman produk atau halaman keranjang RAVAZKA

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Customer menambahkan produk ke keranjang dari halaman detail produk dengan memilih ukuran dan quantity | Sistem menampilkan form "Tambah ke Keranjang" di halaman detail produk dan memvalidasi stok produk sebelum menambahkan ke keranjang |
| 2 | Customer mengakses halaman keranjang belanja untuk melihat daftar produk yang sudah dipilih | Sistem menampilkan halaman keranjang dengan daftar produk lengkap (gambar, nama, kategori, ukuran, harga, quantity) dan menyediakan kontrol untuk mengubah quantity |
| 3 | Customer mengubah jumlah quantity produk atau menghapus produk tertentu dari keranjang | Sistem memvalidasi stok maksimum saat mengubah quantity, menghitung subtotal per item dan total keseluruhan, serta menyediakan tombol hapus untuk setiap item |
| 4 | Customer melanjutkan ke checkout atau mengosongkan seluruh keranjang jika diperlukan | Sistem mendukung persistent cart untuk user login dan session cart untuk guest, menampilkan ringkasan pesanan dengan total dan tombol checkout/kosongkan keranjang |

**Kondisi Akhir:** Customer berhasil mengelola keranjang belanja RAVAZKA dan dapat melanjutkan ke proses checkout dengan produk seragam sekolah yang dipilih

---

**Catatan Tambahan:**
- Sistem mendukung persistent cart untuk user yang sudah login dan session cart untuk guest
- Validasi stok real-time untuk mencegah overselling
- Auto-calculate subtotal dan total keseluruhan saat ada perubahan quantity
- Integrasi dengan sistem inventory untuk update stok secara otomatis