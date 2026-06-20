# laundwiki - Sistem Informasi Manajemen Laundry

Project ini dibuat untuk memenuhi tugas **Ujian Akhir Semester (UAS) Genap TA. 2025/2026** pada mata kuliah **Pemrograman Web**. Aplikasi ini menerapkan konsep pemrograman berbasis objek (OOP) menggunakan PHP dan MySQL sesuai dengan standar kompetensi yang ditentukan.

---

## 📄 Analisis Kebutuhan Sistem

### 1. Deskripsi Singkat Sistem
**laundwiki** adalah sebuah sistem informasi berbasis web yang dirancang untuk mengelola operasional bisnis laundry secara digital dan terstruktur. Aplikasi ini membantu pemilik laundry dalam mendata pelanggan, mengelola paket layanan yang tersedia, serta mencatat transaksi order laundry secara *real-time*. Dengan adanya sistem ini, pencatatan manual yang rentan kesalahan dapat dimasifkan ke dalam sistem digital yang aman.

### 2. Masalah yang Ingin Diselesaikan
* **Pencatatan Manual yang Tidak Efisien:** Proses pembukuan transaksi menggunakan kertas rawan robek, rusak, atau terselip.
* **Kesulitan Pelacakan Status Transaksi:** Pemilik atau kasir sering kali kesulitan mengetahui status pakaian pelanggan (apakah sedang dicuci, selesai, atau sudah diambil) tanpa mengecek fisik pakaian satu per satu.
* **Ketidakakuratan Perhitungan:** Risiko salah hitung total biaya transaksi akibat human error saat kalkulasi manual.

### 3. Pengguna Sistem (User)
* **Administrator / Kasir:** Pengguna utama yang memiliki hak akses penuh untuk mengelola data master (pelanggan, layanan, paket), menginput transaksi baru, mengubah status order, dan melihat ringkasan performa laundry.

### 4. Kebutuhan Fungsional Sistem
Sistem menyediakan fitur-fitur utama berikut untuk mendukung operasional:
* **Autentikasi & Dashboard:** Halaman utama yang menampilkan ringkasan data performa laundry.
* **Manajemen Data Master (CRUD Lengkap):**
  * Mengelola data **Pelanggan** (Tambah, Lihat, Ubah, Hapus).
  * Mengelola data **Layanan** (Tambah, Lihat, Ubah, Hapus).
  * Mengelola data **Paket** (Tambah, Lihat, Ubah, Hapus).
* **Manajemen Data Transaksi:**
  * Mencatat **Order Laundry** baru yang berelasi langsung dengan data master.
  * Menampilkan riwayat transaksi secara keseluruhan.
* **Fitur Pendukung:**
  * Pencarian data pelanggan dan transaksi.
  * Validasi form input untuk mencegah kesalahan data kosong.
  * Mengubah status transaksi secara dinamis.
  * Perhitungan total harga otomatis berdasarkan berat/satuan dan paket yang dipilih.

### 5. Kebutuhan Non-Fungsional Sistem
* **Keamanan:** Validasi input sisi server untuk mencegah celah keamanan dasar.
* **Arsitektur Kode:** Menggunakan pemrograman berbasis objek (OOP) dengan pemisahan struktur kelas yang jelas (Class Database, Class Pelanggan, Class Paket, Class Transaksi).
* **Antarmuka (UI):** Desain yang responsif, bersih, dan mudah dipahami oleh kasir/admin.

---

## 🛠️ Struktur Database & OOP

### Tabel Database
Aplikasi ini menggunakan **4 tabel database** yang saling berelasi:
1. `pelanggan` (Data Master)
2. `layanan` (Data Master)
3. `paket` (Data Master)
4. `order_laundry` (Data Transaksi)

### Struktur Class PHP (OOP)
Implementasi Object-Oriented Programming menggunakan class-class berikut:
* `Database` - Menangani koneksi ke database MySQL.
* `Pelanggan` - Mengatur logika CRUD data pelanggan.
* `Paket` - Mengatur logika CRUD data paket layanan.
* `Transaksi` - Menangani pemrosesan order dan relasi data.

---

## 🚀 Cara Menjalankan Aplikasi

### Persiapan Awal: Import Database
Sebelum menjalankan aplikasi dengan metode di bawah, Anda perlu mengimpor file database terlebih dahulu:
1. Buka browser dan masuk ke **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Buat database baru dengan nama **`db_laundwiki`**.
3. Pilih database tersebut, lalu masuk ke tab **Import**.
4. Pilih file **`uas_laundry.sql`** dari folder project Anda, lalu klik **Go** / **Import**.

---

### Cara 1: Menggunakan PHP Built-in Server (`php -S`)
1. Pastikan servis **MySQL** pada XAMPP Control Panel sudah aktif.
2. Buka **Terminal** atau **Command Prompt (CMD)**.
3. Masuk ke direktori folder project **laundwiki** Anda:
```bash
  cd /jalur/ke/folder/laundwiki
```
4. Jalankan perintah `php -S`
```bash
  # 8000 adalah port
  php -S localhost:8000  
```
5. Buka web browser lalu pergi ke `http://localhost:8000`