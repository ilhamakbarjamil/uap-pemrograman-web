# Quick Fix: Login Failed

## Langkah Cepat untuk Memperbaiki Login

### ✅ Step 1: Pastikan Backend Running

Buka terminal baru (jangan tutup yang ini) dan jalankan:

```bash
cd backend
php artisan serve
```

**PENTING**: Backend HARUS berjalan di `http://localhost:8000` sebelum login bisa bekerja!

### ✅ Step 2: Cek Browser Console

1. Buka halaman login di browser
2. Tekan **F12** untuk buka Developer Tools
3. Buka tab **Console**
4. Coba login lagi
5. Lihat apakah ada error merah di console

**Error yang mungkin muncul:**
- `Failed to fetch` = Backend tidak running atau URL salah
- `CORS error` = CORS belum dikonfigurasi
- `401 Unauthorized` = Email/password salah

### ✅ Step 3: Cek Network Tab

1. Di Developer Tools, buka tab **Network**
2. Coba login lagi
3. Cari request ke `/api/auth/login`
4. Klik request tersebut
5. Lihat tab **Response**

**Response yang benar (success):**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

**Response yang salah:**
```json
{
  "error": "Unauthorized",
  "message": "Email atau password salah"
}
```

### ✅ Step 4: Reset Admin Password (Jika Perlu)

Jika password tidak cocok, reset dengan:

```bash
cd backend
php artisan tinker
```

Kemudian copy-paste ini:
```php
$admin = App\Models\User::where('email', 'admin@admin.com')->first();
$admin->password = Hash::make('admin123');
$admin->save();
echo "Password reset!";
exit
```

### ✅ Step 5: Test API Langsung

Buka terminal dan test API dengan curl:

```bash
curl -X POST http://localhost:8000/api/auth/login ^
  -H "Content-Type: application/json" ^
  -d "{\"email\":\"admin@admin.com\",\"password\":\"admin123\"}"
```

Jika berhasil, akan dapat token. Jika gagal, akan dapat error message.

### ✅ Step 6: Pastikan Frontend Berjalan di HTTP Server

Frontend HARUS dijalankan dengan HTTP server, bukan langsung buka file HTML!

```bash
cd frondend
npm run serve
```

Atau:

```bash
cd frondend/src
python -m http.server 3000
```

## Checklist

Sebelum login, pastikan:

- [ ] Backend running di `http://localhost:8000`
- [ ] Frontend running di `http://localhost:3000` (atau port lain)
- [ ] Admin user sudah dibuat (jalankan seeder)
- [ ] Tidak ada error di browser console
- [ ] Network request ke `/api/auth/login` berhasil (status 200)
- [ ] CORS sudah dikonfigurasi

## Default Credentials

- **Email**: `admin@admin.com`
- **Password**: `admin123`

## Jika Masih Error

1. Buka browser console (F12)
2. Copy semua error yang muncul
3. Screenshot Network tab saat login
4. Cek apakah backend log ada error (lihat terminal `php artisan serve`)

