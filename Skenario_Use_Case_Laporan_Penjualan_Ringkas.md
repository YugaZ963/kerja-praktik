# Skenario Use Case Laporan Penjualan (Ringkas)

## Informasi Use Case
- **Nomor**: REQ-APITS-F-05
- **Nama**: Laporan Penjualan
- **Tujuan**: Admin dapat melihat laporan penjualan dengan analisis revenue dan grafik
- **Deskripsi**: Admin dapat mengakses dashboard laporan penjualan yang menampilkan analisis revenue, grafik tren penjualan, dan statistik penjualan
- **Aktor**: Admin

## Skenario Utama

### Kondisi Awal
Admin sudah login dan berada di dashboard admin

### Aksi Aktor dan Tanggapan Sistem

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Admin mengklik menu "Laporan Penjualan" dari dashboard | Sistem menampilkan halaman laporan dengan 4 kartu ringkasan (Total Revenue, Total Pesanan, Rata-rata Order, Produk Terjual) |
| 2 | Admin menganalisis grafik tren penjualan dan kategori | Sistem menampilkan grafik tren penjualan harian dan pie chart penjualan per kategori |
| 3 | Admin melihat tabel produk terlaris dan pesanan terbaru | Sistem menampilkan tabel produk terlaris dengan ranking dan tabel pesanan selesai terbaru dengan detail customer |
| 4 | Admin menggunakan filter tanggal dan export PDF | Sistem memfilter data berdasarkan rentang tanggal dan menghasilkan file PDF laporan untuk diunduh |

### Kondisi Akhir
Admin berhasil melihat dan menganalisis laporan penjualan dengan berbagai visualisasi data dan dapat mengexport laporan dalam format PDF

### Catatan Tambahan
- Dashboard menampilkan analisis revenue dengan grafik interaktif
- Filter tanggal tersedia dengan quick select (hari ini, 7 hari, bulan ini, tahun ini)
- Tabel penjualan per kategori dilengkapi dengan persentase dan progress bar
- Export PDF mencakup semua data dan visualisasi yang ditampilkan