# Panduan Menjalankan PSU Career (Lokal)

Langkah demi langkah dari nol sampai bisa login sebagai admin, mahasiswa, dan perusahaan.

## 0. Prasyarat

- PHP 8.3+, Composer, Node.js (npm), MySQL 8 — semua sudah tersedia lewat Laragon.
- MySQL harus menyala: buka Laragon → **Start All** (atau minimal start MySQL).
- Kalau PHP default sistem kamu bukan 8.3 (mis. Laragon menyimpan beberapa versi PHP), tambahkan dulu versi 8.3 ke PATH sebelum menjalankan perintah `php`/`composer` di bawah:
  ```bash
  export PATH="/d/laragon/bin/php/php-8.3.26-Win32-vs16-x64:$PATH"
  ```

## 1. Install dependency

```bash
composer install
npm install
```

## 2. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka `.env`, pastikan bagian database sesuai (default sudah pas untuk Laragon):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kampus
DB_USERNAME=root
DB_PASSWORD=
```

Database `kampus` tidak perlu dibuat manual — `migrate` di langkah berikut akan membuatnya kalau belum ada (asal user `root` punya hak buat database; kalau tidak, buat manual dulu lewat HeidiSQL/phpMyAdmin bawaan Laragon).

## 3. Migrasi & seed database

```bash
php artisan migrate:fresh --seed
```

Ini membuat semua tabel lalu mengisi:

- 1 akun admin (`admin@psu.ac.id` / `password`)
- Program studi, skill, industri, 4 template CV contoh
- FAQ awal (mahasiswa & perusahaan)
- `landing_settings` dengan nilai placeholder `[ISI ...]` — wajib diganti admin lewat menu **Landing Page** sebelum dipakai serius

**Opsional** — kalau mau database terisi data dummy (50 mahasiswa + 5 perusahaan, status campuran) buat demo/testing manual:

```bash
php artisan db:seed --class=DemoSeeder
```

## 4. Build asset frontend

```bash
npm run build
```

Untuk development dengan hot-reload, pakai `npm run dev` di terminal terpisah (biarkan tetap jalan).

## 5. Jalankan server

```bash
php artisan serve --port=8123
```

Buka `http://127.0.0.1:8123` di browser.

## 6. (Opsional) Proses antrian notifikasi

Email (undangan akun, notifikasi verifikasi, dll) masuk ke antrian (`QUEUE_CONNECTION=database`) dan baru "terkirim" (muncul di `storage/logs/laravel.log`, karena `MAIL_MAILER=log`) setelah worker memprosesnya:

```bash
php artisan queue:work --stop-when-empty
```

Jalankan ini setiap kali habis melakukan aksi yang mengirim email (approve kemitraan, approve verifikasi, dll) kalau mau melihat isi emailnya.

## 7. Login

Buka `http://127.0.0.1:8123/login`.

| Role       | Cara dapat akun                                                                 |
|------------|----------------------------------------------------------------------------------|
| Admin      | `admin@psu.ac.id` / `password` (dari seeder)                                    |
| Mahasiswa  | Daftar sendiri di `/register`, **atau** admin daftarkan lewat menu **Mahasiswa** |
| Perusahaan | Ajukan kemitraan di `/perusahaan`, lalu admin setujui di **Pengajuan Kemitraan**, **atau** admin buat manual lewat menu **Perusahaan** |

Untuk akun yang dibuatkan admin (mahasiswa atau perusahaan) maupun lewat persetujuan kemitraan: user menerima email berisi tautan "Atur Password" (cek `storage/logs/laravel.log` setelah `queue:work` di atas), baru bisa login setelah set password lewat tautan itu.

### Login sampel (hasil `DemoSeeder`)

Semua akun dari `DemoSeeder` pakai password sama: **`password`**. Email-nya acak (Faker) tiap kali seeder dijalankan ulang — cek tabel `users` lewat phpMyAdmin/Tinker kalau mau pasangan email yang aktual saat ini. Contoh dari data yang sedang berjalan (production, per 2026-11-10):

| Role       | Email                              | Password   | Keterangan                          |
|------------|-------------------------------------|------------|--------------------------------------|
| Mahasiswa  | `cahyanto.prasetyo@example.net`     | `password` | Terverifikasi, bersedia dihubungi    |
| Mahasiswa  | `yani70@example.net`                | `password` | Terverifikasi, bersedia dihubungi    |
| Perusahaan | `dirja35@example.org`               | `password` | Status aktif (PD Rajasa Zulaika)     |
| Perusahaan | `yahya.palastri@example.org`        | `password` | Status aktif (UD Andriani Mulyani)   |

Cara cari sendiri kalau seeder dijalankan ulang:
```sql
SELECT u.name, u.email FROM users u
JOIN students s ON s.user_id = u.id
WHERE s.verification_status = 'verified' AND s.open_to_contact = 1 LIMIT 3;

SELECT u.name, u.email FROM users u
JOIN companies c ON c.user_id = u.id
WHERE c.status = 'active' LIMIT 3;
```

## 8. Jalankan test

```bash
php artisan test
```

Semua test memakai database SQLite in-memory terpisah (lewat `RefreshDatabase`), jadi tidak menyentuh database MySQL `kampus` yang dipakai untuk development.
