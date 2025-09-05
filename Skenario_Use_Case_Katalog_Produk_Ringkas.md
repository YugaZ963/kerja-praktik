# Skenario Use Case Katalog Produk - Ringkas

**Nomor:** REQ-APITS-F-07  
**Nama:** Katalog Produk  
**Tujuan:** Customer dapat melihat katalog produk seragam sekolah  
**Deskripsi:** Customer dapat menjelajahi dan melihat daftar produk seragam sekolah RAVAZKA yang tersedia dengan fitur pencarian dan filter  
**Aktor:** Customer  

## Skenario Utama

**Kondisi Awal:** Customer berada di halaman utama atau mengakses aplikasi RAVAZKA

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Customer membuka halaman utama aplikasi dan mengklik "Lihat Koleksi" atau menu "Produk" | Sistem menampilkan halaman utama dengan kategori populer (SD, SMP, SMA, Pramuka) dan mengarahkan ke halaman katalog produk dengan grid layout |
| 2 | Customer menggunakan fitur filter berdasarkan kategori (SD, SMP, SMA, Pramuka) atau mencari produk menggunakan search bar | Sistem menyediakan fitur pencarian dan filter berdasarkan kategori, ukuran, dan stok, serta menampilkan hasil yang sesuai dengan kriteria |
| 3 | Customer melihat daftar produk dan dapat mengurutkan berdasarkan harga atau nama produk | Sistem menampilkan informasi produk lengkap (nama, kategori, harga, ukuran tersedia, gambar, stok) dengan pagination untuk navigasi halaman |
| 4 | Customer mengklik "Lihat Detail" pada produk yang diminati untuk melihat informasi lengkap | Sistem mengarahkan ke halaman detail produk dengan informasi lengkap, atau menampilkan pesan "Produk Tidak Ditemukan" jika tidak ada hasil pencarian |

**Kondisi Akhir:** Customer berhasil melihat katalog produk RAVAZKA dan dapat memilih produk seragam sekolah yang diinginkan untuk dilihat detailnya

---

**Catatan Tambahan:**
- Sistem mendukung kategori utama: SD, SMP, SMA, dan Pramuka
- Filter dan pencarian bekerja secara real-time untuk pengalaman user yang optimal
- Grid layout responsif untuk tampilan yang baik di berbagai perangkat
- Pagination membantu navigasi pada katalog produk yang banyak