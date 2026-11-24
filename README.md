<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# 📦 Takar – Sistem Manajemen Inventory Sekala Cafe & Shisha

Project ini merupakan aplikasi berbasis **Laravel** untuk pengelolaan persediaan bahan baku di **Sekala Cafe & Shisha** (mulai dari data master supplier dan bahan baku hingga transaksi pemesanan dan pengelolaan stok)[cite: 1, 2].  
Dokumen ini berisi panduan lengkap untuk menjalankan project setelah melakukan *clone repository*.

---

## 🛠️ Persyaratan Sistem

Sebelum memulai, pastikan perangkat Anda sudah terinstall:
- **PHP** ≥ 8.2
- **Composer**
- **Node.js** ≥ 25.00 ≥ 
- **MySQL / MariaDB** (atau SQLite jika menggunakan konfigurasi default lokal)
- **Git**

---

## 🚀 Panduan Instalasi (Step by Step)

### 1. Clone Repository
Buka terminal dan jalankan perintah berikut:
```bash
git clone https://github.com/SI-PPL-2026/TUBES-Kelompok-C04.git
cd TUBES-Kelompok-C04

```

### #2️ Instalasi Dependency

Instal semua package yang dibutuhkan menggunakan Composer:

```bash
composer install
npm install

```

### #3️ Konfigurasi Environment

Salin file `.env.example` menjadi `.env`, lalu edit db_connection "sqlite" menjadi "mysql", dan db_database "laravel" menjadi nama database yg anda mau

```bash
cp .env.example .env

```
*Jangan lupa sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di dalam file `.env`. jika punya anda diubah dari versi standa nya*

### #4 Key Generate, Migrate, Seeding biar bisa dipakai

Jalankan perintah berikut untuk mengamankan aplikasi dan membuat struktur tabel:

```bash
php artisan key:generate
php artisan migrate
php artisan app:user:create

```
```bash
php artisan db:seed

```

Terakhir, tinggal jalankan website dengan :
Opsi A : Build aset sekali:
```bash
npm run build
php artisan serve

```
Opsi B : Mode Pengembang :
```bash
# Terminal 1
npm run dev
# Terminal 2
php artisan serve

```

---

## 📄 License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

```