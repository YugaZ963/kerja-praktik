# Setup RajaOngkir API untuk Ongkos Kirim

## Langkah-langkah Setup:

### 1. Daftar di RajaOngkir
- Kunjungi: https://rajaongkir.com/
- Daftar akun baru atau login jika sudah punya akun
- Pilih paket "Starter" (gratis) untuk testing

### 2. Dapatkan API Key
- Setelah login, masuk ke dashboard
- Klik menu "API Key" 
- Copy API Key yang diberikan

### 3. Konfigurasi di Laravel
- Buka file `.env`
- Ganti `your_rajaongkir_api_key_here` dengan API Key yang didapat
- Contoh: `RAJAONGKIR_API_KEY=abcd1234567890`

### 4. Konfigurasi Origin City
- Default origin city ID adalah 501 (Yogyakarta)
- Jika ingin mengubah, cari city ID di dokumentasi RajaOngkir
- Update `RAJAONGKIR_ORIGIN_CITY_ID` di file `.env`

### 5. Testing
- Pastikan server Laravel berjalan
- Buka halaman checkout
- Coba pilih provinsi dan kota tujuan
- Pilih kurir (JNE/JNT) untuk melihat ongkos kirim

## Fitur yang Tersedia:

### Kurir yang Didukung:
- **JNE** (Jalur Nugraha Ekakurir)
  - REG (Regular)
  - OKE (Ongkos Kirim Ekonomis)
  - YES (Yakin Esok Sampai)

- **JNT** (J&T Express)
  - REG (Regular)
  - EZ (Economy)

### Perhitungan Berat:
- Setiap produk memiliki berat default 500 gram
- Berat minimum pengiriman 1 kg
- Berat dihitung otomatis berdasarkan jumlah item di keranjang

### Informasi Ongkir:
- Biaya pengiriman real-time dari API RajaOngkir
- Estimasi waktu pengiriman
- Berbagai pilihan layanan per kurir

## Troubleshooting:

### Error "API Key tidak valid"
- Pastikan API Key sudah benar di file `.env`
- Restart server Laravel setelah mengubah `.env`

### Error "Kota tidak ditemukan"
- Pastikan koneksi internet stabil
- Cek apakah API RajaOngkir sedang maintenance

### Ongkir tidak muncul
- Cek log Laravel di `storage/logs/laravel.log`
- Pastikan origin city ID valid
- Cek apakah kurir melayani rute tersebut

## Catatan Penting:
- Paket Starter RajaOngkir memiliki limit 1000 request per bulan
- Untuk production, pertimbangkan upgrade ke paket berbayar
- Simpan API Key dengan aman, jangan commit ke repository