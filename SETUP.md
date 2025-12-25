# Quick Setup Guide

## Prerequisites
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & npm (untuk build Tailwind CSS)

## Backend Setup (Laravel)

1. **Install dependencies:**
```bash
cd backend
composer install
```

2. **Setup environment:**
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

3. **Configure database in `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_parfum
DB_USERNAME=root
DB_PASSWORD=
```

4. **Run migrations:**
```bash
php artisan migrate
```

5. **Create storage link (untuk upload gambar):**
```bash
php artisan storage:link
```

6. **Start server:**
```bash
php artisan serve
```

Backend akan berjalan di `http://localhost:8000`

## Frontend Setup

1. **Masuk ke folder frontend:**
```bash
cd frondend
```

2. **Buka file di browser:**
- Buka `src/index.html` untuk halaman utama
- Atau gunakan live server (VS Code extension, Python http.server, dll)

**Catatan:** Pastikan API URL di `src/api.js` sesuai dengan URL backend Anda:
```javascript
const API_BASE_URL = 'http://localhost:8000/api';
```

## Membuat Admin User

Setelah registrasi user pertama, update role di database:

```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

Atau langsung insert admin user:

```sql
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES ('Admin', 'admin@example.com', '$2y$10$...', 'admin', NOW(), NOW());
```

## Testing

### Test sebagai User:
1. Buka `register.html` dan daftar akun baru
2. Login dengan akun tersebut
3. Lihat produk di dashboard
4. Klik "Beli Sekarang" pada produk
5. Masukkan quantity dan checkout
6. Stock akan otomatis berkurang

### Test sebagai Admin:
1. Login dengan akun admin
2. Di admin dashboard, bisa:
   - Tambah produk baru
   - Edit produk
   - Hapus produk
   - Lihat semua transaksi

## Troubleshooting

### Error: Class 'Illuminate\Http\Middleware\HandleCors' not found
Install CORS package:
```bash
composer require fruitcake/laravel-cors
```

### Error: JWT secret not set
Run:
```bash
php artisan jwt:secret
```

### Error: Storage link not found
Run:
```bash
php artisan storage:link
```

### CORS Error di Browser
Pastikan middleware CORS sudah dikonfigurasi di `backend/bootstrap/app.php`

### Gambar tidak muncul
Pastikan:
1. Storage link sudah dibuat: `php artisan storage:link`
2. Folder `storage/app/public/perfumes` ada dan writable
3. File gambar di-upload dengan benar

## File Structure

```
uap/
├── backend/
│   ├── app/
│   │   ├── Http/Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── PerfumeController.php
│   │   │   └── TransactionController.php
│   │   └── Models/
│   │       ├── User.php
│   │       ├── Perfume.php
│   │       └── Transaction.php
│   ├── database/migrations/
│   │   ├── ..._create_users_table.php
│   │   ├── ..._add_role_to_users_table.php
│   │   ├── ..._create_perfumes_table.php
│   │   └── ..._create_transactions_table.php
│   └── routes/
│       └── api.php
└── frondend/
    └── src/
        ├── index.html
        ├── login.html
        ├── register.html
        ├── dashboard.html
        ├── admin-dashboard.html
        ├── api.js
        └── output.css
```

## API Endpoints Summary

### Public Endpoints
- `GET /api/perfumes` - List semua parfum
- `GET /api/perfumes/{id}` - Detail parfum
- `POST /api/auth/register` - Register
- `POST /api/auth/login` - Login

### Protected Endpoints (requires JWT)
- `POST /api/auth/logout` - Logout
- `GET /api/auth/me` - Get current user
- `POST /api/auth/refresh` - Refresh token
- `POST /api/perfumes` - Create parfum (admin only)
- `PATCH /api/perfumes/{id}` - Update parfum (admin only)
- `DELETE /api/perfumes/{id}` - Delete parfum (admin only)
- `GET /api/transactions` - List transaksi
- `GET /api/transactions/{id}` - Detail transaksi
- `POST /api/transactions` - Checkout (user)

## Next Steps

1. Setup database dan jalankan migrations
2. Buat user admin di database
3. Test aplikasi sebagai user dan admin
4. Upload gambar produk untuk testing
5. Test checkout dan verifikasi stock berkurang

