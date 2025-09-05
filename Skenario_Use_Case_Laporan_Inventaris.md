# Skenario Use Case - Laporan Inventaris

## Nomor
REQ-APITS-F-06

## Nama
Laporan Inventaris

## Tujuan
Admin dapat melihat laporan inventaris dengan analisis stok dan monitoring item

## Deskripsi
Admin dapat mengakses dashboard laporan inventaris yang menampilkan analisis stok, grafik monitoring inventaris, dan statistik item inventaris

## Aktor
Admin

## Skenario Utama

### Kondisi Awal
Admin sudah login dan berada di dashboard admin

### Aksi Aktor dan Tanggapan Sistem

| **Aksi Aktor** | **Tanggapan Sistem** |
|---|---|
| Admin mengklik menu "Laporan Inventaris" dari dashboard | Sistem menampilkan halaman laporan inventaris dengan 4 kartu ringkasan (Total Item, Stok Tersedia, Item Low Stock, Total Kategori) |
| Admin menganalisis grafik stok per kategori dan tren inventaris | Sistem menampilkan grafik bar chart stok per kategori dan line chart tren perubahan stok harian |
| Admin melihat tabel item low stock dan item terpopuler | Sistem menampilkan tabel item dengan stok di bawah minimum dengan alert dan tabel item dengan penjualan tertinggi |
| Admin menggunakan filter kategori dan export Excel | Sistem memfilter data berdasarkan kategori yang dipilih dan menghasilkan file Excel laporan inventaris untuk diunduh |

### Kondisi Akhir
Admin berhasil melihat dan menganalisis laporan inventaris dengan berbagai visualisasi data stok dan dapat mengexport laporan dalam format Excel

---

## Skenario Alternatif

### A1: Filter Berdasarkan Status Stok
**Kondisi**: Admin ingin melihat item berdasarkan status stok tertentu

| **Aksi Aktor** | **Tanggapan Sistem** |
|---|---|
| Admin memilih filter "Low Stock" atau "Out of Stock" | Sistem menampilkan hanya item yang sesuai dengan filter status stok |
| Admin melihat detail item yang memerlukan restock | Sistem menampilkan informasi supplier dan tanggal restock terakhir |

### A2: Export Laporan dengan Periode Tertentu
**Kondisi**: Admin ingin export laporan untuk periode waktu tertentu

| **Aksi Aktor** | **Tanggapan Sistem** |
|---|---|
| Admin mengklik "Filter Tanggal" dan memilih rentang waktu | Sistem menampilkan modal filter dengan input tanggal dan quick select (Hari ini, 7 hari, Bulan ini, Tahun ini) |
| Admin memilih periode dan klik "Export Excel" | Sistem menghasilkan file Excel dengan data inventaris sesuai periode yang dipilih |

---

## Aturan Bisnis

1. **Akses Terbatas**: Hanya admin yang dapat mengakses laporan inventaris
2. **Real-time Data**: Data inventaris harus selalu update sesuai dengan perubahan stok terkini
3. **Alert Low Stock**: Item dengan stok di bawah minimum harus ditampilkan dengan highlight merah
4. **Export Permission**: Admin dapat export laporan dalam format Excel (.xlsx)
5. **Filter Kategori**: Laporan dapat difilter berdasarkan kategori produk (Seragam SD, SMP, SMA, Pramuka)
6. **Historical Data**: Sistem menyimpan riwayat perubahan stok untuk analisis tren

---

## Komponen Dashboard Laporan Inventaris

### 1. Kartu Ringkasan
- **Total Item**: Jumlah total item dalam inventaris
- **Stok Tersedia**: Total unit stok yang tersedia
- **Item Low Stock**: Jumlah item dengan stok di bawah minimum
- **Total Kategori**: Jumlah kategori produk yang ada

### 2. Grafik Visualisasi
- **Bar Chart**: Distribusi stok per kategori produk
- **Line Chart**: Tren perubahan stok dalam 30 hari terakhir
- **Pie Chart**: Persentase stok per kategori

### 3. Tabel Data
- **Tabel Item Low Stock**: Daftar item yang perlu restock dengan alert
- **Tabel Item Terpopuler**: Item dengan penjualan tertinggi
- **Tabel Riwayat Restock**: History restock terakhir per item

### 4. Fitur Filter dan Export
- **Filter Kategori**: Dropdown untuk memilih kategori tertentu
- **Filter Status**: Low Stock, Normal Stock, Out of Stock
- **Filter Tanggal**: Rentang waktu untuk analisis
- **Export Excel**: Download laporan dalam format .xlsx

---

## Teknologi dan Implementasi

- **Backend**: Laravel 11 dengan Eloquent ORM
- **Frontend**: Bootstrap 5 + Chart.js untuk visualisasi
- **Export**: Laravel Excel (Maatwebsite/Excel)
- **Database**: MySQL dengan tabel inventories dan products
- **Real-time**: Ajax untuk update data tanpa refresh halaman