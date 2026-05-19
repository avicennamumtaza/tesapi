# TES API

Backend API berbasis Laravel 12 untuk manajemen kategori, produk, pesanan, dan ringkasan dashboard. Respons API berbentuk JSON dan semua endpoint saat ini tersedia di bawah prefix `/api`.

## Screenshot Hasil Postman

### Active Products
![Active Products](ss_postman/active_products.png)
### Store Order
![Store Order](ss_postman/store_order.png)
### Summary Dashboard
![Summary Dashboard](ss_postman/summary_dashboard.png)
### Clear Cache
![Clear Cache](ss_postman/clear_cache.png)

## Data

- `categories`: nama dan slug kategori.
- `products`: nama, kategori, harga, stok, dan status aktif.
- `orders`: user, produk, jumlah, total harga, dan status pesanan.
- `users`: data autentikasi dasar ditambah field `api_token` untuk kebutuhan integrasi token di masa depan.

## API Endpoint

Base URL contoh: `http://127.0.0.1:8000/api`

### Produk

- `GET /products` (support query `search`)
- `POST /products`
- `GET /products/{product}`
- `PUT /products/{product}`
- `PATCH /products/{product}`
- `DELETE /products/{product}`

### Order

- `POST /orders`
- `GET /users/{user}/orders/{order}`

### Dashboard

- `GET /dashboard/summary
- `DELETE /dashboard/cache`