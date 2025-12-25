# Troubleshooting Login Issues

## Masalah: Login Failed

Jika login sebagai admin gagal, ikuti langkah-langkah berikut:

### 1. Pastikan Backend Berjalan

Buka terminal baru dan jalankan:
```bash
cd backend
php artisan serve
```

Backend harus berjalan di `http://localhost:8000`

### 2. Pastikan Admin User Sudah Dibuat

Jalankan seeder:
```bash
cd backend
php artisan db:seed --class=AdminSeeder
```

Atau cek di database apakah admin sudah ada:
```bash
php artisan tinker
```

Di tinker:
```php
$admin = App\Models\User::where('email', 'admin@admin.com')->first();
if ($admin) {
    echo "Admin found: " . $admin->name . " - Role: " . $admin->role;
} else {
    echo "Admin not found!";
}
```

### 3. Reset Password Admin (Jika Perlu)

Jika admin sudah ada tapi password tidak cocok, reset password:
```bash
php artisan tinker
```

Di tinker:
```php
$admin = App\Models\User::where('email', 'admin@admin.com')->first();
$admin->password = Hash::make('admin123');
$admin->save();
echo "Password reset successfully!";
```

### 4. Cek Browser Console

Buka Developer Tools (F12) di browser, lihat tab Console dan Network:
- **Console**: Cek apakah ada error JavaScript
- **Network**: Cek request ke `/api/auth/login`, lihat response-nya

### 5. Cek API URL

Pastikan API URL di `frondend/src/api.js` benar:
```javascript
const API_BASE_URL = 'http://localhost:8000/api';
```

### 6. Test API Langsung

Test endpoint login dengan curl atau Postman:
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@admin.com","password":"admin123"}'
```

Jika berhasil, akan mendapat response dengan `access_token`.

### 7. Cek CORS Configuration

Pastikan CORS sudah dikonfigurasi di `backend/bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->api(prepend: [
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
})
```

### 8. Clear Cache Laravel

Jika masih error, clear cache:
```bash
cd backend
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Credentials Default

- **Email**: `admin@admin.com`
- **Password**: `admin123`
- **Role**: `admin`

## Buat Admin Manual (Alternatif)

Jika seeder tidak bekerja, buat manual dengan tinker:

```bash
php artisan tinker
```

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;

User::create([
    'name' => 'Admin',
    'email' => 'admin@admin.com',
    'password' => Hash::make('admin123'),
    'role' => 'admin',
]);

echo "Admin created successfully!";
```

