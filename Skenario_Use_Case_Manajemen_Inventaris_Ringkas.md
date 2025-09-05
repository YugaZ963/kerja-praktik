# Skenario Use Case Manajemen Inventaris (Ringkas)

## Informasi Use Case
| Field | Detail |
|-------|--------|
| **Nomor** | REQ-APITS-F-03 |
| **Nama** | Manajemen Inventaris |
| **Tujuan** | Admin dapat mengelola data inventaris seragam sekolah dengan operasi CRUD lengkap |
| **Deskripsi** | Admin dapat melakukan Create, Read, Update, Delete data inventaris dengan fitur pencarian dan filtering |
| **Aktor** | Admin |

## Skenario Utama

### Kondisi Awal
Admin telah login dan memiliki akses ke sistem inventaris

### Alur Interaksi

| No | Aksi Aktor | Tanggapan Sistem |
|----|------------|------------------|
| 1 | Admin mengakses halaman inventaris dari dashboard | Sistem menampilkan halaman inventaris dengan tabel data dan fitur pencarian/filter |
| 2 | Admin melakukan pencarian dengan kata kunci tertentu | Sistem memproses pencarian dengan query LIKE pada nama, kategori, dan kode inventaris |
| 3 | Admin memfilter data berdasarkan kategori atau status stok | Sistem menerapkan filter dan menampilkan data sesuai kriteria yang dipilih |
| 4 | Admin menambah item baru dengan mengisi form lengkap | Sistem memvalidasi input form dan menyimpan data baru ke database dengan feedback sukses |
| 5 | Admin mengedit item existing dengan mengubah data | Sistem memperbarui data dengan validasi unique code dan memberikan konfirmasi perubahan |
| 6 | Admin menghapus item inventaris yang tidak diperlukan | Sistem menghapus item dan cascade delete produk terkait dengan konfirmasi penghapusan |

### Kondisi Akhir
Data inventaris berhasil dikelola sesuai operasi yang dipilih admin dengan feedback yang sesuai

---

**Catatan:** Skenario ini telah diringkas menjadi 6 aksi aktor dengan 6 tanggapan sistem yang seimbang, mencakup operasi CRUD utama dan fitur pencarian/filtering.