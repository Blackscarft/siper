# SIPER - Sistem Informasi Persediaan 📦

<div align="center">
  <img src="public/logo.svg" width="180" alt="SIPER Logo">
  <strong>Solusi manajemen stok barang dan gudang yang efisien, cepat, dan modern.</strong>

![Laravel](https://img.shields.io/badge/Laravel-11.37.0-FF2D20.svg?logo=laravel&logoColor=red)
![PHP](https://img.shields.io/badge/php-8.2-%23777BB4.svg?&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.3-7952B3?logo=bootstrap&logoColor=fff)
[![mazer-template](https://img.shields.io/badge/mazer%20template-435ebe?style=flat&logoColor=white)](https://zuramai.github.io/mazer/)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=fff)
![Composer](https://img.shields.io/badge/Composer-2.8.5-885630?logo=composer&logoColor=fff)

---
 </div>

## 🚀 Tentang SIPER
**SIPER** adalah platform manajemen inventaris yang dirancang untuk mempermudah pemantauan stok barang. Dibangun dengan **Laravel 11** dan desain UI dari **Mazer Admin Dashboard**, aplikasi ini menawarkan pengalaman pengelolaan gudang yang intuitif bagi admin maupun petugas lapangan.

### ✨ Fitur Utama
- 📊 **Dashboard Analitik**: Pantau total barang, kategori, dan transaksi secara visual.
- 📦 **Manajemen Inventaris**: Pengelolaan data barang (CRUD) yang terstruktur.
- 🔄 **Mutasi Barang**: Pencatatan otomatis barang masuk (Inbound) dan keluar (Outbound).
- ⚖️ **Stock Opname**: Fitur rekonsiliasi untuk menyesuaikan jumlah stok di sistem dengan stok fisik di gudang secara periodik guna meminimalisir selisih.
- 🔒 **Tutup Buku**: Penguncian data transaksi pada periode tertentu (bulanan/tahunan) untuk memastikan laporan tidak berubah dan saldo akhir menjadi saldo awal periode berikutnya.
- 👤 **Multi-role Access**: Keamanan akses berdasarkan peran pengguna (Admin & Manager).
- 📱 **Responsive Design**: Akses lancar melalui PC, Tablet, maupun Smartphone.

---

## ⚙️ Panduan Instalasi

Ikuti langkah-langkah berikut untuk memasang **SIPER** di lingkungan lokal Anda:

### 1. Persyaratan Sistem
Pastikan perangkat Anda sudah terpasang:
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL / MariaDB

### 2. Clone Repositori
```bash
git clone [https://github.com/username-anda/siper.git](https://github.com/username-anda/siper.git)
cd siper
```

### 3. Instalasi Dependensi
Instal library backend (PHP):
```bash
# Instal library Laravel
composer install
```
### 4. Konfigurasi Environment
Salin file konfigurasi ```.env.example``` menjadi ```.env``` dan generate unique key:
```bash
cp .env.example .env
php artisan key:generate
```

### 5. Pengaturan Database
Buat database baru di panel MySQL Anda (misal: ```db_siper```), lalu sesuaikan baris berikut di file ```.env```:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_siper
DB_USERNAME=root
DB_PASSWORD=
```
### 6. Migrasi dan Seeder
Jalankan migrasi untuk membuat tabel dan isi data awal **(akun admin dan manager)**:
```bash
php artisan migrate --seed
```
### 7. Jalankan Server
```bash
# Di terminal baru, jalankan server Laravel
php artisan serve
```

### 8. Akses & Login
Akses aplikasi melalui browser di: ```http://localhost:8000``` kemudian dapat login dengan akun berikut:
| Username | Password | Role |
| -------- | -------  | ---- |
| admin@tes.com  | adminrhs   | Admin |
| manager@tes.com  | adminrhs   | Manager  |

<br>

> Dibangun dengan ❤️ oleh <a href="https://www.instagram.com/agus_nugrh">Suga Ho.</a>