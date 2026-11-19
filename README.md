# PSU Career

Portal karier Universitas Panca Sakti Bekasi — menghubungkan mahasiswa, Career Center (admin), dan perusahaan mitra. Kampus selalu menjadi perantara kontak antara mahasiswa dan perusahaan; perusahaan tidak pernah melihat email/no HP mahasiswa kecuali request kontaknya sudah disetujui admin.

## Stack

Laravel 13 (PHP 8.3+), MySQL 8, Blade + Tailwind CSS v4 (Vite) + Alpine.js, Pest, barryvdh/laravel-dompdf, intervention/image v4.

## Instalasi

Lihat **[PANDUAN.md](PANDUAN.md)** untuk langkah lengkap dari install sampai login sebagai admin/mahasiswa/perusahaan. Ringkas:

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve --port=8123
```

Seeder (`DatabaseSeeder`) mengisi: 1 akun admin, program studi contoh, skill umum, industri, 4 template CV, FAQ awal, dan `landing_settings` dengan nilai placeholder `[ISI ...]` yang perlu diganti admin lewat menu **Landing Page** sebelum rilis produksi. Data dummy (50 mahasiswa + 5 perusahaan) ada di seeder terpisah: `php artisan db:seed --class=DemoSeeder`.

Jalankan test:

```bash
php artisan test
```

## Akun Default

| Role  | Email             | Password   |
|-------|-------------------|------------|
| Admin | admin@psu.ac.id   | `password` |

Tidak ada akun mahasiswa/perusahaan contoh secara default:

- **Mahasiswa**: daftar sendiri lewat `/register`, atau admin daftarkan langsung lewat menu **Mahasiswa**.
- **Perusahaan**: ajukan kemitraan lewat `/perusahaan` lalu admin setujui di **Pengajuan Kemitraan**, atau admin buat manual lewat menu **Perusahaan**.

Akun yang dibuat admin (mahasiswa maupun perusahaan) menerima email berisi tautan untuk mengatur password sendiri (`MAIL_MAILER=log` secara default, cek `storage/logs/laravel.log`).

## Menambah Template CV Baru

1. Buat Blade view baru di `resources/views/cv/templates/`, misalnya `resources/views/cv/templates/minimalis.blade.php`.
2. View menerima array data berikut (lihat `app/Services/CvDataBuilder.php`):
   `name`, `photoDataUri`, `summary`, `studyProgram`, `entryYear`, `semester`, `gpa`, `city`, `accentColor`, `skills[]`, `experiences[]`, `educations[]`, `portfolios[]`, dan — hanya saat mahasiswa melihat/mengunduh CV miliknya sendiri — `email`, `phone`, `linkedinUrl`, `portfolioUrl`. **Jangan render field kontak itu di template manapun yang bisa diakses perusahaan.**
3. Tambahkan satu baris di `CvTemplate` (lewat Tinker atau seeder):
   ```php
   CvTemplate::create([
       'name' => 'Minimalis',
       'slug' => 'minimalis',
       'blade_view' => 'cv.templates.minimalis',
       'is_active' => true,
       'sort_order' => 5,
   ]);
   ```
4. Template otomatis muncul di pilihan mahasiswa (`/student/cv`) dan dipakai DomPDF untuk unduhan PDF — tidak perlu kode tambahan.

## Catatan

- Perubahan semester/IPK/prodi/angkatan pada mahasiswa yang sudah terverifikasi selalu lewat alur persetujuan `student_update_requests`, tidak pernah langsung mengubah data.
- Log aktivitas penting (login, verifikasi, approval) tercatat di `activity_logs`, bisa difilter lewat menu **Log Aktivitas** admin.
- Penghapusan akun mahasiswa melakukan soft delete + anonimisasi kontak (telepon, LinkedIn, portofolio, nama, email), bukan hard delete.
