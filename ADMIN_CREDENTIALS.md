# Admin Credentials

## Default Admin Account

Setelah menjalankan seeder, akun admin default adalah:

- **Email**: `admin@admin.com`
- **Password**: `admin123`
- **Role**: `admin`

## Cara Membuat Admin

### Opsi 1: Menggunakan Seeder (Recommended)

Jalankan seeder untuk membuat admin default:

```bash
cd backend
php artisan db:seed --class=AdminSeeder
```

Atau jalankan semua seeder:

```bash
php artisan db:seed
```

### Opsi 2: Update User yang Sudah Ada

Jika sudah punya user, update role menjadi admin:

**Via SQL:**
```sql
UPDATE users SET role = 'admin' WHERE email = 'your-email@example.com';
```

**Via Laravel Tinker:**
```bash
cd backend
php artisan tinker
```

Kemudian di tinker:
```php
$user = App\Models\User::where('email', 'your-email@example.com')->first();
$user->role = 'admin';
$user->save();
```

### Opsi 3: Buat Manual via Database

Insert langsung ke database:

```sql
INSERT INTO users (name, email, password, role, created_at, updated_at) 
VALUES ('Admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW());
```

**Catatan:** Password di atas adalah hash untuk 'password', ganti dengan hash password yang Anda inginkan.

## Login sebagai Admin

1. Buka frontend: `http://localhost:3000/login.html`
2. Masukkan:
   - Email: `admin@admin.com`
   - Password: `admin123`
3. Setelah login, akan diarahkan ke Admin Dashboard

## Mengubah Password Admin

**Via Laravel Tinker:**
```bash
php artisan tinker
```

```php
$admin = App\Models\User::where('email', 'admin@admin.com')->first();
$admin->password = Hash::make('password-baru');
$admin->save();
```

## Security Note

⚠️ **PENTING**: Setelah deploy ke production, **WAJIB** mengubah password default!

Untuk production, buat admin dengan password yang kuat dan jangan gunakan password default.

