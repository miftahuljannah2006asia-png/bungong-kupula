# Bungong Kupula

Platform gastronomi dan budaya Kabupaten Pidie, Aceh. Dibangun dengan **Laravel 12**, **Tailwind CSS 4**, dan **Alpine.js**, terhubung ke **MySQL/MariaDB**.

Fitur: **area admin untuk menambah kuliner baru**, beranda, Pustaka Rasa (pencarian + filter + halaman resep), Peta Wilayah, Pasar Oleh-Oleh (keranjang + pemesanan), Hikayat, Kontak, resep tersimpan, sitemap.xml.

## Kebutuhan

- PHP 8.2+ (ekstensi `openssl`, `curl`, `mbstring`, `pdo_mysql` aktif)
- MySQL 5.7+ atau MariaDB 10.3+ (Laragon, XAMPP, atau dari hosting), dikelola lewat phpMyAdmin
- Composer 2
- Node.js 20+ dan npm

## Menjalankan di komputer (VS Code)

1. Buka phpMyAdmin (Laragon: `http://localhost/phpmyadmin`), klik **New**, beri nama `bungong_kupula`, collation **utf8mb4_unicode_ci**, lalu **Create**.
2. Jalankan:

```bash
composer install
cp .env.example .env     # lalu cek DB_USERNAME / DB_PASSWORD di .env
composer run setup       # membuat APP_KEY, tabel database (migrasi), npm install, build
composer run dev         # server Laravel + Vite (hot reload) di http://localhost:8000
```

Tanpa `composer run setup` (manual):

```bash
php artisan key:generate
php artisan migrate
npm install
npm run dev     # atau: npm run build
```

**Hosting tanpa akses terminal (cPanel)?** Tabel bisa dibuat lewat phpMyAdmin: pilih database Anda, tab **Import**, unggah `database/schema.sql`. File itu membuat tabel `orders`, `order_items`, `messages`, `recipes`, dan mencatat migrasinya, sehingga `php artisan migrate` di kemudian hari tidak bentrok.

> Jika memakai Laragon/XAMPP, pastikan `public/` menjadi document root, atau jalankan `php artisan serve`.

## Di mana mengedit apa

| Ingin mengubah | Edit file |
|---|---|
| Resep (cara mudah) | Admin: `/admin` → Kuliner |
| Resep bawaan (via kode) | `resources/content/recipes.php` |
| Tampilan admin | `resources/views/admin/*.blade.php` |
| Kawasan Pidie | `resources/content/districts.php` |
| Hikayat | `resources/content/stories.php` |
| Produk oleh-oleh | Admin: `/admin` → Produk (data contoh awal: `resources/content/products.php`) |
| Warna & font | `resources/css/app.css` (blok `@theme`) |
| Tampilan halaman | `resources/views/pages/*.blade.php` |
| Header / footer / keranjang | `resources/views/partials/*.blade.php` |
| Logika keranjang & filter | `resources/js/app.js` |
| Nomor WhatsApp & email | `.env` (`KUPULA_WHATSAPP`, `KUPULA_EMAIL`) |
| Foto | `public/images/` (nama file dipakai di `resources/content/*.php`) |

## Admin: menambah kuliner baru

Area admin ada di **`/admin`** (contoh: http://localhost:8000/admin).

**1. Buat akun admin** (sekali saja):

```bash
php artisan migrate          # membuat tabel recipes (jika belum)
php artisan admin:hash       # masukkan kata sandi, lalu salin 2 baris hasilnya ke .env
```

Hasilnya seperti ini (tanda kutip tunggal pada hash **harus dipertahankan**):

```
ADMIN_EMAIL=email-anda@contoh.com
ADMIN_PASSWORD_HASH='$2y$12$....'
```

Jika sebelumnya sudah menjalankan `php artisan config:cache`, jalankan `php artisan config:clear`.

**2. Masuk** di `/admin/login`, lalu buka menu **Kuliner**.

- **Tambah kuliner baru**: isi nama, kategori, asal wilayah, kisah, bahan (satu baris satu bahan), langkah (satu baris satu langkah), profil rasa, dan unggah foto. Setelah disimpan, kuliner langsung tampil di Pustaka Rasa, halaman detail, pencarian, dan sitemap.
- **Ubah / ganti foto**: tombol *Ubah* pada daftar.
- **Resep bawaan** (6 resep dari `resources/content/recipes.php`): klik **Impor resep bawaan** sekali agar bisa diubah lewat admin. Resep bawaan tidak dihapus permanen, hanya **disembunyikan**.
- Foto yang diunggah tersimpan di `public/uploads/recipes/` (tidak ikut Git; di server, folder ini harus bisa ditulis).

Keamanan: sesi login memakai cookie sesi Laravel, form dilindungi CSRF, percobaan login dibatasi 5x per menit, dan halaman admin ditandai `noindex`. Gunakan HTTPS di produksi.

## Database MySQL

Semua data disimpan di MySQL/MariaDB: `products` (katalog oleh-oleh), `orders` + `order_items` (pesanan), `messages` (pesan kontak), dan `recipes` (resep dari admin).

1. Buat database kosong (collation `utf8mb4_unicode_ci`) di phpMyAdmin.
2. Isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env`.
3. Jalankan `php artisan migrate`, lalu `php artisan db:seed` untuk mengisi 4 produk contoh. (Tanpa terminal: impor `database/schema.sql` di phpMyAdmin, lalu tambah produk lewat menu **Produk** di admin.)

Harga dan stok dikelola lewat **Admin → Produk**. Harga pesanan selalu dihitung ulang di server dari tabel `products`. Pesanan masuk bisa dilihat di tabel `orders` lewat phpMyAdmin, atau di ringkasan admin.

## Menayangkan situs online (GitHub → hosting → link untuk dibagikan)

> **GitHub hanya menyimpan kode, bukan menayangkan situs ini.** GitHub Pages hanya untuk situs statis, sedangkan ini aplikasi Laravel yang butuh PHP dan MySQL. Alurnya: kode disimpan di GitHub, lalu dijalankan di hosting PHP. Link yang dibagikan adalah alamat domain hosting Anda.

### Langkah 1 — Simpan kode di GitHub

1. Buat repository kosong di github.com (**New repository**, tanpa README/.gitignore).
2. Di folder proyek, jalankan:

   ```bash
   git init
   git add .
   git commit -m "Situs Bungong Kupula"
   git branch -M main
   git remote add origin https://github.com/USERNAME/bungong-kupula.git
   git push -u origin main
   ```

3. Cek di halaman repository: `.env` **tidak boleh** muncul (sudah ada di `.gitignore`; cek juga dengan `git status` sebelum push). Jika repository publik, pastikan tidak ada kata sandi atau kunci apa pun yang ter-commit.

### Langkah 2 — Siapkan hosting dan database

Pilih hosting PHP 8.2+ dengan MySQL dan phpMyAdmin (shared hosting/cPanel seperti Niagahoster, Hostinger, dan sejenisnya sudah cukup). Di cPanel:

1. **MySQL Databases** → buat database, buat user beserta kata sandi, lalu **Add User To Database** dengan *All Privileges*. Catat tiga nilainya: nama database, user, dan kata sandi (biasanya berawalan nama akun, misalnya `akun_kupula`).
2. **Select PHP Version** → pilih 8.2 atau lebih baru, dan pastikan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl`, `fileinfo` aktif.
3. **Domains** → ubah *document root* domain (atau subdomain) ke folder **`public`** milik proyek, misalnya `kupula/public`. Jika tidak bisa diubah, lihat catatan di bawah Jalur B.

### Langkah 3 — Pasang situs (pilih salah satu jalur)

Folder `vendor/` dan `public/build/` tidak ada di GitHub, jadi keduanya harus dibuat dulu, baik di server (Jalur A) maupun di komputer Anda (Jalur B).

#### Jalur A — Hosting punya Terminal/SSH (paling rapi, update tinggal `git pull`)

Cara ini cocok untuk VPS atau cPanel yang menyediakan **Terminal**. Jika Node.js tidak ada di server, lakukan langkah 4 di komputer sendiri lalu unggah folder `public/build/`.

```bash
git clone https://github.com/USERNAME/bungong-kupula.git kupula
cd kupula
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit `.env` di server (lihat **Isi .env produksi** di bawah), lalu:

```bash
php artisan migrate --force          # membuat tabel di MySQL
php artisan admin:hash               # buat kata sandi admin, salin hasilnya ke .env
php artisan db:seed --force          # isi produk contoh (opsional)
npm ci && npm run build              # hanya jika Node.js tersedia di server
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

#### Jalur B — Tanpa terminal (hanya File Manager/FTP + phpMyAdmin)

Semua persiapan dilakukan di komputer Anda, lalu hasilnya diunggah.

1. Di komputer: `composer install --no-dev --optimize-autoloader`, lalu `npm ci && npm run build`.
2. Buat nilai yang dibutuhkan, lalu simpan untuk langkah 4:
   - `php artisan key:generate --show` → ini nilai **APP_KEY**.
   - `php artisan admin:hash` → ini dua baris **ADMIN_EMAIL** dan **ADMIN_PASSWORD_HASH**.
3. Kompres seluruh folder proyek menjadi `.zip`, **tanpa** `node_modules/`, `.env`, dan `.git/`. Folder `vendor/` dan `public/build/` **harus ikut**.
4. Di cPanel **File Manager**, unggah zip itu ke folder di luar `public_html` (misalnya `/home/akun/kupula`), lalu **Extract**.
5. Buka phpMyAdmin → pilih database Anda → tab **Import** → unggah `database/schema.sql`. Tabel terbentuk, tanpa perlu terminal.
6. Buat file `.env` di folder `kupula` (salin dari `.env.example`) dan isi sesuai bagian **Isi .env produksi**.
7. Atur izin tulis (klik kanan → *Change Permissions* → `775`) untuk `storage/` (beserta isinya), `bootstrap/cache/`, `public/uploads/recipes/`, dan `public/uploads/products/`.

> **Document root tidak bisa diubah?** Salin *isi* folder `public/` ke `public_html/`, lalu di `public_html/index.php` ubah dua jalur `__DIR__.'/../...'` agar menunjuk ke folder proyek, misalnya `__DIR__.'/../kupula/vendor/autoload.php'` dan `__DIR__.'/../kupula/bootstrap/app.php'`. Pastikan folder proyek berada di luar `public_html` supaya `.env` tidak bisa diakses dari browser.

#### Isi .env produksi

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.id
APP_KEY=base64:...              # dari php artisan key:generate --show

DB_CONNECTION=mysql
DB_HOST=localhost               # atau 127.0.0.1, ikuti petunjuk hosting
DB_PORT=3306
DB_DATABASE=akun_kupula
DB_USERNAME=akun_kupula
DB_PASSWORD=kata-sandi-database

ADMIN_EMAIL=email-anda@contoh.com
ADMIN_PASSWORD_HASH='$2y$12$...'   # tanda kutip tunggal wajib dipertahankan

KUPULA_WHATSAPP=62812xxxxxxx
KUPULA_EMAIL=halo@domain-anda.id
```

### Langkah 4 — Aktifkan HTTPS dan uji

1. cPanel → **SSL/TLS Status** → jalankan **AutoSSL** agar situs bisa dibuka lewat `https://`.
2. Buka `https://domain-anda.id` dan cek: beranda tampil dengan gaya (CSS) lengkap, halaman Pustaka Rasa dan Pasar terbuka, dan form Kontak berhasil terkirim.
3. Buka `/admin/login`, masuk, lalu klik **Impor resep bawaan** sekali. Cek juga menu **Produk**.
4. Buat satu pesanan percobaan, lalu cek apakah muncul di phpMyAdmin (tabel `orders`).
5. Jika semua beres, **bagikan link** `https://domain-anda.id` ke siapa saja.

### Memperbarui situs setelah ada perubahan

- **Jalur A:** `git pull`, lalu `composer install --no-dev --optimize-autoloader`, `php artisan migrate --force`, `npm run build` (atau unggah `public/build/` hasil build lokal), dan `php artisan config:cache && php artisan route:cache && php artisan view:cache`.
- **Jalur B:** unggah ulang file yang berubah lewat File Manager/FTP (jangan menimpa `.env` dan `public/uploads/`). Jika CSS/JS berubah, unggah ulang seluruh `public/build/`.

### Hanya untuk demo sementara (tanpa hosting)

Jalankan `php artisan serve`, lalu bagikan lewat [Cloudflare Tunnel](https://developers.cloudflare.com/cloudflare-one/connections/connect-networks/) (`cloudflared tunnel --url http://localhost:8000`) atau ngrok. Link hanya aktif selama komputer Anda menyala dan terhubung internet.

### Alternatif: Railway / Render / Fly.io

Layanan ini bisa terhubung langsung ke repository GitHub (deploy otomatis setiap `git push`), tetapi butuh `Dockerfile` atau konfigurasi PHP sendiri. Dua hal yang perlu diperhatikan: pakai database MySQL dari layanan itu (isi `DB_*`), dan folder `public/uploads/recipes/` (foto dari admin) harus dipasang *persistent volume*, karena file lokal hilang pada setiap deploy.

### Jika ada masalah

| Gejala | Penyebab & solusi |
|---|---|
| Halaman putih / error 500 | Lihat `storage/logs/laravel.log`. Biasanya `APP_KEY` kosong, atau `storage/` dan `bootstrap/cache/` belum bisa ditulis (izin `775`). |
| `Access denied for user` / `Unknown database` | `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` di `.env` tidak sama dengan yang dibuat di cPanel. Setelah mengubah `.env`, jalankan `php artisan config:clear` (atau hapus `bootstrap/cache/config.php`). |
| `Table '...' doesn't exist` | Tabel belum dibuat: jalankan `php artisan migrate --force`, atau impor `database/schema.sql` lewat phpMyAdmin. |
| Tampilan tanpa gaya (CSS tidak muncul) | Folder `public/build/` belum diunggah. Jalankan `npm run build` lalu unggah folder itu. |
| Semua halaman selain beranda 404 | Document root belum ke folder `public/`, atau file `public/.htaccess` tidak ikut terunggah. |
| Foto resep dari admin gagal diunggah | Folder `public/uploads/recipes/` belum bisa ditulis (izin `775`). |
| Pasar Oleh-oleh kosong / produk contoh muncul terus | Tabel `products` belum dibuat atau masih kosong: jalankan `php artisan migrate` lalu `php artisan db:seed`, atau tambah produk di Admin → Produk. |
| `.env` terbaca dari browser | Folder proyek harus di luar `public_html`, atau document root harus mengarah ke `public/`. |

## Catatan konten

Data produk (nama perajin, harga, rating, ulasan), nama penulis hikayat, kutipan bahasa Aceh, dan nomor WhatsApp pada versi ini adalah **contoh dari draf awal**. Ganti dengan data asli dan minta penutur Aceh memeriksa ejaan kutipan sebelum situs diluncurkan.
