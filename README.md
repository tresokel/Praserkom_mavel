# Sistem Pemesanan Tiket Event & Konser

Aplikasi berbasis web untuk katalog event, pemesanan tiket oleh pelanggan, serta dashboard admin untuk pengelolaan master data event dan transaksi pesanan masuk. Proyek ini dibangun menggunakan framework **Laravel** dan **Tailwind CSS**.

---

## 🚀 Fitur Utama

### 1. Halaman Pelanggan (Customer)
* **Katalog Event**: Menampilkan daftar event lengkap dengan poster, judul, kategori, harga tiket, dan deskripsi.
* **Filter Kategori**: Menyaring event berdasarkan kategori (*Musik*, *Festival*, *Seminar*, atau *Semua Event*).
* **Pemesanan Tiket**: Pelanggan dapat memasukkan nama, email, serta memilih jumlah tiket yang ingin dipesan.
* **Modal Konfirmasi**: Rincian pemesanan dan total pembayaran ditampilkan sebelum pesanan dikirim.
* **Penyimpanan Pesanan Otomatis**: Data pesanan dan rincian item tiket otomatis tersimpan ke database secara aman (*Database Transaction*).

### 2. Panel Admin
* **Autentikasi Aman**: Login, register, dan logout menggunakan Laravel Breeze.
* **Dashboard Admin**:
  * Menampilkan seluruh daftar transaksi/pesanan masuk secara urut dari yang paling baru.
  * Informasi detail pesanan: nama pemesan, email, tiket yang dibeli beserta kuantitasnya, total bayar, tanggal, dan status.
  * **Ubah Status Pesanan**: Admin dapat langsung memperbarui status tiket (`Pending`, `Confirmed`, `Cancelled`).
* **Kelola Data Event (CRUD)**:
  * **Lihat Data**: Tabel daftar master data event dengan gambar dan paginasi.
  * **Tambah Event**: Form tambah event lengkap dengan upload file gambar/poster.
  * **Edit Event**: Memperbarui informasi atau mengganti poster event.
  * **Hapus Event**: Menghapus data event sekaligus menghapus file gambar poster dari storage.

---

## 🗄️ Struktur Database

Aplikasi ini menggunakan database MySQL dengan tabel utama sebagai berikut:

* **`users`**: Menyimpan data pengguna / admin untuk login.
* **`events`**: Menyimpan data event (judul, kategori `enum('Musik', 'Festival', 'Seminar')`, harga tiket, deskripsi, dan path gambar).
* **`orders`**: Menyimpan data transaksi pesanan pelanggan (nama, email, total harga, status `enum('pending', 'confirmed', 'cancelled')`).
* **`orders_details`**: Menyimpan rincian item tiket yang dipesan (relasi ke `order_id` dan `event_id`, jumlah tiket, dan subtotal harga).

---

## 🛠️ Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan komputer Anda telah terpasang:
* **PHP** >= 8.2 (disarankan PHP 8.2+)
* **Composer**
* **Node.js & NPM**
* **MySQL Database** (misal via XAMPP)

---

## 📦 Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di komputer lokal:

### 1. Masuk ke Direktori Proyek
Buka terminal / PowerShell dan arahkan ke folder proyek:
```bash
cd Praserkom_mavel
```

### 2. Pasang Dependensi PHP (Composer)
```bash
composer install
```

### 3. Pasang Dependensi Frontend (NPM)
```bash
npm install
npm run build
```
> *Catatan: Untuk proses pengembangan secara langsung dengan Hot Reload, Anda bisa menjalankan `npm run dev` di tab terminal terpisah.*

### 4. Konfigurasi File Environment (`.env`)
Salin file `.env.example` menjadi `.env` jika belum ada:
```bash
cp .env.example .env
```
Generate kunci aplikasi:
```bash
php artisan key:generate
```

Buka file `.env` dan pastikan konfigurasi database sudah sesuai:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_tiket_konser
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan database `db_tiket_konser` sudah dibuat di phpMyAdmin / MySQL)*

### 5. Jalankan Migrasi Database
Jalankan migrasi untuk membuat seluruh tabel yang dibutuhkan:
```bash
php artisan migrate
```

### 6. Buat Tautan Storage (Symlink)
Tautan ini diperlukan agar gambar poster yang diunggah dapat diakses dari browser:
```bash
php artisan storage:link
```

### 7. Jalankan Server Lokal
Jalankan server pengembangan Laravel:
```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser:
* **Halaman Pelanggan (Pemesanan Tiket)**: [http://127.0.0.1:8000/](http://127.0.0.1:8000/)
* **Login Admin**: [http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)
* **Dashboard Admin**: [http://127.0.0.1:8000/dashboard](http://127.0.0.1:8000/dashboard)
* **Kelola Event**: [http://127.0.0.1:8000/events](http://127.0.0.1:8000/events)

---

## 👥 Alur Penggunaan Aplikasi

1. **Membuat Akun Admin**:
   * Kunjungi halaman registrasi di `/register` untuk membuat akun admin pertama Anda.
   * Setelah register/login, Anda akan diarahkan ke halaman `/dashboard`.
2. **Mengelola Data Event**:
   * Klik menu **Kelola Event** di bagian navigasi atas.
   * Tambahkan event konser atau seminar baru melalui tombol **+ Tambah Event**.
3. **Pemesanan Tiket oleh Pelanggan**:
   * Buka halaman utama `/` (katalog event).
   * Masukkan nama & email, pilih jumlah tiket yang diinginkan, lalu klik **Pesan Tiket**.
4. **Melihat & Memproses Pesanan**:
   * Kembali ke `/dashboard` sebagai admin.
   * Pesanan yang baru dibuat akan langsung muncul di tabel pesanan masuk lengkap dengan nama pembeli, rincian tiket, dan total pembayaran.
   * Admin dapat mengubah status pesanan dari `Pending` menjadi `Confirmed` atau `Cancelled`.
