# Skenario Use Case Manajemen Pesanan (Ringkas)

## Informasi Use Case
- **Nomor**: REQ-APITS-F-04
- **Nama**: Manajemen Pesanan
- **Tujuan**: Admin dapat melihat, memfilter, dan mengupdate status pesanan pelanggan
- **Deskripsi**: Admin dapat mengelola semua pesanan pelanggan dengan fitur pencarian, filtering berdasarkan status, dan update status pesanan
- **Aktor**: Admin

## Skenario Utama

### Kondisi Awal
Admin telah login dan berada di halaman dashboard admin

### Aksi Aktor dan Tanggapan Sistem

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Admin mengakses halaman Manajemen Pesanan | Sistem menampilkan halaman dengan daftar pesanan dan counter status |
| 2 | Admin mencari pesanan berdasarkan nomor/nama/telepon | Sistem memproses pencarian dan menampilkan hasil yang sesuai |
| 3 | Admin memfilter pesanan berdasarkan status tertentu | Sistem memfilter dan menampilkan pesanan sesuai status yang dipilih |
| 4 | Admin mengupdate status pesanan dan menambahkan catatan | Sistem menyimpan perubahan, mengirim notifikasi ke pelanggan, dan mencatat riwayat dengan timestamp |

### Kondisi Akhir
Admin berhasil mengelola pesanan pelanggan dan status pesanan terupdate sesuai dengan alur bisnis

### Catatan Tambahan
- Sistem otomatis mengurangi stok saat status berubah ke 'delivered'
- Setiap perubahan status dicatat dalam riwayat dengan timestamp
- Notifikasi otomatis dikirim ke pelanggan saat status berubah
- Admin dapat melihat detail lengkap pesanan termasuk informasi pelanggan dan item pesanan