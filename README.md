# 🏫 Aplikasi Raport ASTS - MTsN 3 Purworejo

Aplikasi Manajemen & Cetak Raport Asesmen Sumatif Tengah Semester (ASTS) berbasis web yang dirancang khusus untuk memenuhi kebutuhan administrasi nilai, presensi, dan cetak raport fisik presisi skala 1 lembar A4 di MTsN 3 Purworejo.

![Laravel Version](https://img.shields.io/badge/Laravel-v13.x-red.svg)
![PHP Version](https://img.shields.io/badge/PHP-v8.4-blue.svg)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v4.0-06B6D4.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

---

## ✨ Fitur Utama

- **🔒 Kontrol Akses Berbasis Peran (Multi-Role):**
  - **Administrator:** Akses penuh ke seluruh data, pengaturan modul, dan manajemen pengguna.
  - **Kepala Madrasah:** Memantau seluruh nilai, presensi, dan cetak raport secara nasional.
  - **Wali Kelas:** Mengelola data siswa kelasnya, mengisi presensi/catatan wali kelas, mengunci/membuka nilai kelas, serta melakukan cetak raport satuan maupun massal.
  - **Guru Mata Pelajaran:** Menginput nilai ASTS secara manual atau mengunggah melalui file templat Excel.
  - **Guru BK:** Menginput ketidakhadiran (Sakit, Izin, Alfa) dan catatan perkembangan siswa.

- **📊 Manajemen Nilai & Presensi Dual-Mode:**
  - **Input Manual:** Entri nilai dan kehadiran langsung melalui tabel web interaktif dengan fitur penanda fokus (*highlight*).
  - **Import & Export Excel:** Unduh templat Excel khusus per kelas/mapel dan unggah nilai secara massal.

- **🔐 Proteksi & Penguncian Nilai:**
  - Fitur **Kunci/Buka Kunci Nilai Kelas** oleh Wali Kelas atau Admin untuk mencegah pengubahan nilai secara tak sengaja oleh Guru Mapel setelah tenggat waktu.

- **🖨️ Cetak Raport Presisi 1 Lembar A4:**
  - Layout laporan cetak telah disesuaikan agar pas 1 lembar A4 (*no page overflow*).
  - Dilengkapi Kop Surat Kemenag & Logo Madrasah simetris secara lokal.
  - Fitur **Cetak Massal (Bulk Print)** untuk mencetak seluruh raport siswa dalam 1 kelas sekaligus hanya dengan 1 kali klik.
  - Tanda tangan dan tanggal cetak yang dikelola secara dinamis lewat menu Pengaturan.

---

## 🛠️ Teknologi yang Digunakan

- **Framework Backend:** [Laravel 13](https://laravel.com) (PHP 8.4)
- **Frontend & Styling:** [Tailwind CSS v4](https://tailwindcss.com), Alpine.js, Laravel Breeze
- **Excel Processor:** [Maatwebsite/Laravel-Excel](https://laravel-excel.com)
- **Web Server Target:** Nginx / Apache di Ubuntu Server (aaPanel Compatible)

---

## 🚀 Panduan Instalasi Lokal / Server

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal maupun server produksi:

### 1. Prasyarat Sistem
- PHP `>= 8.2` (Direkomendasikan PHP 8.4)
- Composer `>= 2.x`
- Node.js `>= 20.x` & NPM
- MySQL / MariaDB

### 2. Kloning Repositori
```bash
git clone [https://github.com/USERNAME/raport-mts.git](https://github.com/USERNAME/raport-mts.git)
cd raport-mts
```

# Instalasi Dependensi
## Install paket PHP
```
composer install
```

## Install paket JavaScript / CSS
```
npm install
```

# Konfigurasi Environment (.env)
## Salin file .env.example menjadi .env:
cp .env.example .env

## Buka file .env dan atur koneksi database kamu:
```
DB_CONNECTION=mysql
DB_HOST=127.0.01
DB_PORT=3306
DB_DATABASE=db_raport_mts
DB_USERNAME=root
DB_PASSWORD=password_kamu
```

# Generate Key & Migrasi Database
```
php artisan key:generate
php artisan migrate --seed
```

# Kompilasi Aset Frontend
```
npm run build
```

# Jalankan Server Lokal
```
php artisan serve
```
Aplikasi dapat diakses di browser melalui URL: http://localhost:8000

# Struktur Folder Utama
raport-mts/
├── app/
│   ├── Exports/            # Class Ekspor Templat Excel
│   ├── Imports/            # Class Impor Data Excel
│   ├── Http/Controllers/   # Controller Utama (Grades, Classes, Students, Reports, dll)
│   └── Models/             # Model Eloquent Database
├── public/
│   └── images/             # File Logo Kemenag & Madrasah (logo-kemenag.png, logo-mts.png)
├── resources/
│   ├── css/                # Kustomisasi Styling Tailwind CSS
│   └── views/              # Template Blade Layouts & Modul Tampilan
└── routes/
    └── web.php             # Routing Aplikasi
    
# Lisensi
Aplikasi ini dikembangkan untuk kepentingan internal MTsN 3 Purworejo dan dilisensikan di bawah lisensi MIT License.