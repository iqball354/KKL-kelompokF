# KKL Kelompok F

## Informasi Proyek

- Nama proyek: KKL Kelompok F
- Deskripsi singkat: Aplikasi web berbasis Laravel untuk pengelolaan data keahlian dosen, kurikulum, dan konsentrasi jurusan dengan alur akses berbasis peran.
- Fitur-fitur:
	- Login dan logout pengguna.
	- Dashboard berbeda untuk tiap role.
	- Manajemen keahlian dosen.
	- Manajemen kurikulum.
	- Manajemen konsentrasi jurusan.
	- Validasi dan verifikasi data oleh role tertentu.
- Teknologi yang digunakan:
	- PHP 7.3 / 8.0
	- Laravel 8
	- MySQL / database relasional
	- Laravel Sanctum
	- Laravel Mix
	- Bootstrap / CSS / JavaScript

## Struktur Folder

- `app/`: logika utama aplikasi, model, controller, middleware, dan provider.
- `bootstrap/`: file bootstrap aplikasi Laravel.
- `config/`: konfigurasi aplikasi.
- `database/`: migrasi, seeder, dan factory.
- `public/`: file publik dan entry point aplikasi.
- `resources/`: view Blade, CSS, dan JavaScript frontend.
- `routes/`: definisi route web, API, dan console.
- `storage/`: file cache, log, dan upload.
- `tests/`: pengujian aplikasi.

## Cara Meng-clone Repository

```bash
git clone <url-repository>
cd KKL-kelompokF
```

## Cara Menginstal Dependency

Install dependency backend:

```bash
composer install
```

## Cara Mengatur Environment

1. Salin file `.env.example` menjadi `.env`.
2. Atur konfigurasi database di file `.env`, terutama `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.
3. Generate application key:

```bash
php artisan key:generate
```

4. Jalankan migrasi dan seeder jika dibutuhkan:

```bash
php artisan migrate --seed
```

## Cara Menjalankan Backend dan Frontend

Jalankan backend Laravel:

```bash
php artisan serve
```

## Catatan

- Setelah backend dan frontend berjalan, buka aplikasi melalui alamat yang ditampilkan oleh `php artisan serve`.
- Jika menggunakan Laragon, pastikan database dan web server sudah aktif sebelum menjalankan migrasi.
