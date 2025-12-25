# Toko Parfum - Aplikasi E-Commerce dengan Laravel dan Tailwind CSS

Aplikasi e-commerce untuk toko parfum dengan sistem role user dan admin, menggunakan Laravel sebagai backend dan Tailwind CSS untuk frontend.

## Fitur

### Fitur Dasar
- ✅ **Register**: Pengguna dapat melakukan pendaftaran akun baru
- ✅ **Login**: Pengguna dapat login untuk mengakses dashboard
- ✅ **Proteksi JWT**: Semua fitur CRUD dan halaman Dashboard terproteksi menggunakan JWT

### Manajemen Data
- ✅ **CRUD Perfume**: Admin dapat melakukan Create, Read, Update, Delete pada data parfum
- ✅ **Checkout**: User dapat melakukan checkout parfum
- ✅ **Stock Management**: Stock otomatis berkurang ketika transaksi berhasil dilakukan
- ✅ **Relasi Database**: Tabel Transaction berelasi dengan tabel Perfume dan User

### Role System
- **User**: Dapat melihat produk dan melakukan checkout
- **Admin**: Dapat melakukan CRUD pada produk dan melihat semua transaksi

## Teknologi yang Digunakan

### Backend
- Laravel 12
- PHP 8.2+
- JWT Authentication (php-open-source-saver/jwt-auth)
- MySQL/PostgreSQL

### Frontend
- HTML5
- Tailwind CSS
- Vanilla JavaScript (ES6 Modules)
- Fetch API untuk komunikasi dengan backend

## Instalasi dan Setup

### Backend (Laravel)

1. Masuk ke folder backend:
```bash
cd backend
```

2. Install dependencies:
```bash
composer install
```

3. Copy file .env:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Generate JWT secret:
```bash
php artisan jwt:secret
```

6. Konfigurasi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

7. Jalankan migration:
```bash
php artisan migrate
```

8. Buat symbolic link untuk storage (jika diperlukan):
```bash
php artisan storage:link
```

9. Jalankan server:
```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

### Frontend

1. Masuk ke folder frontend:
```bash
cd frondend
```

2. Install dependencies (jika menggunakan npm/node):
```bash
npm install
```

3. Build Tailwind CSS (jika diperlukan):
```bash
npm run build
```

Atau jika sudah ada file `output.css`, bisa langsung digunakan.

4. Buka file `index.html` di browser atau menggunakan live server.

### Konfigurasi API URL

Pastikan API URL di file `frondend/src/api.js` sesuai dengan URL backend Anda:

```javascript
const API_BASE_URL = 'http://localhost:8000/api';
```

## Struktur Database

### Tabel Users
- id
- name
- email
- password
- role (enum: 'user', 'admin') - default: 'user'
- timestamps

### Tabel Perfumes
- id
- name
- brand
- description
- price
- stock
- size_ml
- category
- file_path (nullable)
- timestamps
- deleted_at (soft delete)

### Tabel Transactions
- id
- user_id (foreign key ke users)
- perfume_id (foreign key ke perfumes)
- quantity
- total_price
- status (enum: 'pending', 'completed', 'cancelled')
- timestamps

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register user baru
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout (protected)
- `GET /api/auth/me` - Get current user info (protected)
- `POST /api/auth/refresh` - Refresh token (protected)

### Perfumes
- `GET /api/perfumes` - Get all perfumes (public)
- `GET /api/perfumes/{id}` - Get perfume detail (public)
- `POST /api/perfumes` - Create perfume (protected - admin only)
- `PATCH /api/perfumes/{id}` - Update perfume (protected - admin only)
- `DELETE /api/perfumes/{id}` - Delete perfume (protected - admin only)

### Transactions
- `GET /api/transactions` - Get all transactions (protected)
  - Admin: melihat semua transaksi
  - User: melihat transaksi sendiri
- `GET /api/transactions/{id}` - Get transaction detail (protected)
- `POST /api/transactions` - Create transaction/checkout (protected - user)

## Cara Menggunakan

### Sebagai User

1. Daftar akun baru di halaman Register
2. Login dengan email dan password
3. Lihat daftar produk di Dashboard
4. Klik "Beli Sekarang" pada produk yang diinginkan
5. Masukkan jumlah yang ingin dibeli
6. Klik "Checkout" untuk menyelesaikan transaksi
7. Stock otomatis akan berkurang setelah checkout berhasil

### Sebagai Admin

1. Buat akun admin dengan cara:
   - Daftar seperti biasa, kemudian update role di database menjadi 'admin'
   - Atau langsung insert ke database dengan role 'admin'
   
2. Login dengan akun admin
3. Di Admin Dashboard, admin dapat:
   - Melihat semua produk
   - Menambah produk baru (klik "Tambah Produk")
   - Edit produk yang ada (klik "Edit")
   - Hapus produk (klik "Hapus")
   - Melihat semua transaksi

## Catatan Penting

- Semua endpoint CRUD (Create, Update, Delete) memerlukan autentikasi JWT
- Hanya admin yang dapat melakukan CRUD pada produk
- Stock otomatis berkurang ketika user melakukan checkout
- File gambar produk disimpan di `storage/app/public/perfumes`
- Pastikan symbolic link storage sudah dibuat untuk mengakses gambar

## Troubleshooting

### CORS Error
Jika mengalami CORS error, pastikan middleware CORS sudah dikonfigurasi di `backend/bootstrap/app.php`

### JWT Error
Pastikan JWT secret sudah di-generate dengan `php artisan jwt:secret`

### Storage Link
Jika gambar tidak muncul, pastikan symbolic link sudah dibuat dengan `php artisan storage:link`

### Database Connection
Pastikan konfigurasi database di `.env` sudah benar dan database sudah dibuat

## Kontributor

- Developer

## License

MIT License

