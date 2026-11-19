# Cara Build & Upload ke Server

Hosting ini **tidak ada SSH/Terminal**, jadi semua upload dilakukan manual lewat cPanel File Manager. Struktur di server beda dari lokal:

```
/home/wopecnbh/
├── laravel_core/      ← kode aplikasi (app, routes, resources, vendor, dll) — TIDAK bisa diakses browser
└── public_html/        ← webroot asli, isinya cuma:
    ├── index.php        (bootstrap ke ../laravel_core/)
    ├── .htaccess
    ├── favicon.ico, robots.txt
    ├── images/branding/ (logo.png, hero-students.jpg)
    └── build/           (hasil `npm run build` — assets/*.css, assets/*.js, manifest.json)
```

Login cPanel: `http://nilou.kawaiihost.net:2083/` → File Manager.

## 1. Kalau cuma ubah PHP/Blade (tidak ubah class Tailwind baru)

Edit file di `D:\project\kampus`, test di lokal (`php artisan serve`), lalu copy isi file itu ke lokasi yang sama di server (`laravel_core/...`) lewat File Manager → klik file → **Edit**.

Tidak perlu `npm run build`.

## 2. Kalau ubah CSS/Tailwind (termasuk pakai class Tailwind baru di Blade)

```bash
export PATH="/d/laragon/bin/php/php-8.3.26-Win32-vs16-x64:$PATH"
cd /d/project/kampus
npm run build
```

Ini menghasilkan file BARU dengan nama hash berubah, contoh:
```
public/build/assets/app-C5MK3VCJ.css   ← nama hash selalu beda tiap build
public/build/manifest.json             ← otomatis update, isinya nama file hash di atas
```

**Upload 2 hal ini ke server:**
1. File CSS baru → upload ke `public_html/build/assets/` (lewat tombol **Upload** di File Manager — file binary/besar harus upload manual, tidak bisa saya tempel lewat editor teks)
2. `public_html/build/manifest.json` → **harus** diedit supaya nama file-nya sama dengan yang baru diupload (tinggal copy isi dari `public/build/manifest.json` lokal)

> Catatan: dulu sempat ada 2 lokasi manifest.json yang beda (`laravel_core/public/build/` vs `public_html/build/`) dan bikin bingung karena Laravel bacanya dari lokasi yang salah. Sudah diperbaiki di `bootstrap/app.php` — sekarang Laravel selalu baca dari `public_html/build/manifest.json`. Jadi **cukup edit yang di `public_html/build/` saja**.

## 3. Kalau file gambar baru (logo, foto, dll)

Upload manual ke `public_html/images/...` lewat File Manager (sama kayak CSS, harus upload manual karena binary).

## 4. Kalau sudah upload tapi perubahan tidak muncul

Kemungkinan Laravel masih pakai cache lama. Hapus semua isi folder ini (aman, otomatis dibuat ulang):
```
laravel_core/storage/framework/views/
```

Kalau masih PHP versi lama / error aneh setelah ganti kode, coba refresh PHP worker: cPanel → **MultiPHP Manager** → centang domain → pilih versi lain → Apply → pilih PHP 8.3 lagi → Apply.

## 5. Checklist tiap kali ada perubahan

- [ ] Test lokal dulu (`php artisan serve`, buka `http://127.0.0.1:8123`)
- [ ] Kalau ubah CSS: `npm run build`
- [ ] Jalankan test: `php artisan test` (harus 119 passed)
- [ ] Upload file yang berubah ke `laravel_core/...` (PHP/Blade) dan/atau `public_html/build/` (CSS/JS)
- [ ] Kalau perlu, bersihkan `laravel_core/storage/framework/views/`
- [ ] Cek live site, pastikan tidak ada error
