# Perbaikan Masalah Gambar Tidak Muncul

## Masalah
Gambar produk tidak muncul meskipun sudah ada di database.

## Penyebab Kemungkinan

1. **Storage Link Belum Dibuat**
   - Laravel perlu membuat symbolic link dari `public/storage` ke `storage/app/public`
   
2. **URL Gambar Salah**
   - Path di database mungkin tidak sesuai dengan URL yang digunakan

3. **File Tidak Ada di Storage**
   - File mungkin belum di-upload atau terhapus

## Solusi

### 1. Buat Storage Link

Jalankan command berikut di terminal:

```bash
cd backend
php artisan storage:link
```

Ini akan membuat symbolic link dari `public/storage` ke `storage/app/public`.

### 2. Cek File di Storage

Pastikan file ada di:
```
backend/storage/app/public/perfumes/
```

### 3. Cek URL di Browser

Buka URL gambar langsung di browser:
```
http://localhost:8000/storage/perfumes/[nama-file]
```

Jika muncul 404, berarti:
- Storage link belum dibuat, atau
- File tidak ada di storage

### 4. Perbaikan yang Sudah Dilakukan

- Menambahkan error handling untuk gambar yang tidak bisa dimuat
- Membersihkan path (remove leading slash)
- Fallback ke placeholder jika gambar tidak ada

## Cara Test

1. Pastikan storage link sudah dibuat:
   ```bash
   cd backend
   php artisan storage:link
   ```

2. Cek apakah file ada:
   ```bash
   ls backend/storage/app/public/perfumes/
   ```

3. Test URL langsung di browser:
   ```
   http://localhost:8000/storage/perfumes/[nama-file-dari-database]
   ```

4. Refresh halaman admin dashboard atau user dashboard

## Catatan

- File path di database harus relatif dari `storage/app/public/`
- Contoh: `perfumes/1766050053_Screenshot.jpg`
- URL yang digunakan: `http://localhost:8000/storage/perfumes/1766050053_Screenshot.jpg`

