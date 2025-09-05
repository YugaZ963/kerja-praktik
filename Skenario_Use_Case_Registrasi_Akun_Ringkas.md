# Skenario Use Case Registrasi Akun - Ringkas

**Nomor:** REQ-APITS-F-06  
**Nama:** Registrasi Akun  
**Tujuan:** Customer dapat mendaftar akun baru dengan role user  
**Deskripsi:** Customer dapat mendaftar akun baru dengan role user untuk mengakses fitur lengkap RAVAZKA  
**Aktor:** Customer  

## Skenario Utama

**Kondisi Awal:** Customer berada di halaman registrasi aplikasi RAVAZKA

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Customer membuka halaman registrasi dan mengisi form dengan nama lengkap, email, dan password (minimal 6 karakter) | Sistem menampilkan form registrasi dengan field nama, email, password, konfirmasi password, dan role "User/Pelanggan" sebagai default |
| 2 | Customer mengkonfirmasi password dan memastikan data yang diisi sudah benar | Sistem memvalidasi data secara real-time (email unik, password minimal 6 karakter, konfirmasi password cocok) |
| 3 | Customer menekan tombol "Daftar" untuk menyelesaikan proses registrasi | Sistem membuat akun baru dengan role "user", melakukan auto-login, dan menggabungkan cart session guest ke user cart |
| 4 | Customer berhasil masuk ke aplikasi dan dapat mengakses fitur user seperti checkout dan riwayat pesanan | Sistem mengarahkan ke halaman beranda dengan pesan sukses registrasi dan menampilkan navbar dengan menu user yang sudah login |

**Kondisi Akhir:** Customer berhasil terdaftar dan masuk ke dalam aplikasi RAVAZKA dengan role user, dapat mengakses semua fitur pelanggan

---

**Catatan Tambahan:**
- Sistem otomatis mengatur role sebagai "user" untuk registrasi customer
- Auto-login setelah registrasi berhasil untuk meningkatkan user experience
- Integrasi cart session memastikan produk yang sudah dipilih sebelum registrasi tidak hilang
- Validasi email unik mencegah duplikasi akun