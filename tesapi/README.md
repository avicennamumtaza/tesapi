# TES API

Backend API berbasis Laravel 12 untuk manajemen kategori, produk, pesanan, dan ringkasan dashboard. Respons API berbentuk JSON dan semua endpoint saat ini tersedia di bawah prefix `/api`.

## Fitur

- CRUD produk dengan pencarian dan pagination.
- Relasi kategori ke produk.
- Pembuatan pesanan dengan validasi stok dan transaksi database.
- Ringkasan dashboard yang di-cache selama 5 menit.
- Riwayat pesanan per user dengan ringkasan produk dan kategori.
- Seed user awal untuk kebutuhan pengembangan.

## Teknologi

- PHP 8.2+
- Laravel 12
- Composer
- Vite
- Tailwind CSS 4
- SQLite, MySQL, PostgreSQL, atau database lain yang didukung Laravel

## Prasyarat

- PHP 8.2 atau lebih baru.
- Composer.
- Node.js dan npm.
- Database yang dikonfigurasi di file `.env`.

## Instalasi

1. Masuk ke folder proyek.
2. Jalankan `composer install`.
3. Salin `.env.example` menjadi `.env`.
4. Atur kredensial database di `.env`.
5. Jalankan `php artisan key:generate`.
6. Jalankan migrasi dengan `php artisan migrate`.
7. Jika perlu data awal, jalankan `php artisan db:seed`.
8. Instal dependensi frontend dengan `npm install`.

## Menjalankan Aplikasi

Untuk mode pengembangan, jalankan dua proses berikut:

```bash
php artisan serve
npm run dev
```

Atau jalankan semua proses yang sudah disiapkan di `composer.json`:

```bash
composer run dev
```

## Testing

Jalankan test suite dengan:

```bash
php artisan test
```

Atau gunakan script Composer:

```bash
composer test
```

Environment testing sudah dikonfigurasi memakai SQLite in-memory melalui `phpunit.xml`.

## Konfigurasi Environment

Minimal variabel yang perlu diperhatikan:

- `APP_NAME`
- `APP_ENV`
- `APP_URL`
- `DB_CONNECTION`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

Jika memakai SQLite lokal, pastikan file database tersedia dan nilai `DB_DATABASE` mengarah ke path yang benar.

## Domain Data

- `categories`: nama dan slug kategori.
- `products`: nama, kategori, harga, stok, dan status aktif.
- `orders`: user, produk, jumlah, total harga, dan status pesanan.
- `users`: data autentikasi dasar ditambah field `api_token` untuk kebutuhan integrasi token di masa depan.

## API Endpoint

Base URL contoh: `http://127.0.0.1:8000/api`

### Produk

- `GET /products` - daftar produk aktif, mendukung query `search`.
- `POST /products` - membuat produk baru.
- `GET /products/{product}` - detail produk.
- `PUT /products/{product}` - memperbarui produk.
- `PATCH /products/{product}` - memperbarui produk parsial.
- `DELETE /products/{product}` - menghapus produk.

Contoh request:

```bash
curl "http://127.0.0.1:8000/api/products?search=beras"
```

```bash
curl -X POST "http://127.0.0.1:8000/api/products" \
	-H "Content-Type: application/json" \
	-d '{
		"name": "Beras Premium",
		"category_id": 1,
		"price": 65000,
		"stock": 20,
		"is_active": true
	}'
```

### Pesanan

- `POST /orders` - membuat pesanan baru.
- `GET /users/{user}/orders/{order}` - ringkasan pesanan untuk user tertentu.

Payload `POST /orders`:

```json
{
	"product_id": 1,
	"qty": 2
}
```

Jika stok tidak cukup, API akan mengembalikan status `422` dengan pesan `Stok tidak cukup.`

### Dashboard

- `GET /dashboard/summary` - statistik ringkas, produk terlaris, dan pesanan terbaru.
- `DELETE /dashboard/cache` - membersihkan cache dashboard.

Dashboard summary memakai cache selama 300 detik dan mengembalikan flag `from_cache`.

## Catatan Implementasi

- Route API sudah didaftarkan di `bootstrap/app.php` dan diprefix otomatis dengan `/api`.
- Saat ini route API belum diproteksi middleware autentikasi, jadi aksesnya masih terbuka.
- `orders` dibuat dalam transaksi agar pengurangan stok dan pembuatan pesanan tetap konsisten.

## Struktur Utama

- `app/Http/Controllers/Api` - controller endpoint API.
- `app/Http/Requests` - validasi request.
- `app/Http/Resources` - transformasi response JSON.
- `app/Models` - model Eloquent utama.
- `database/migrations` - skema database.
- `database/seeders` - data awal.