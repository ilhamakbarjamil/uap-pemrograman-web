# Frontend - Toko Parfum

## Cara Menjalankan Frontend

Ada beberapa cara untuk menjalankan frontend:

### Opsi 1: Menggunakan npm script (Paling Mudah) ✅

```bash
cd frondend
npm run serve
```

Frontend akan berjalan di `http://localhost:3000`

**Catatan:** Pertama kali perlu install http-server:
```bash
npm install --save-dev http-server
```

Atau langsung menggunakan npx (sudah ada di package.json):
```bash
npm run serve
```

### Opsi 2: Menggunakan Python HTTP Server

Jika Python sudah terinstall:

```bash
cd frondend/src
python -m http.server 3000
```

Atau Python 2:
```bash
cd frondend/src
python -m SimpleHTTPServer 3000
```

Frontend akan berjalan di `http://localhost:3000`

### Opsi 3: Menggunakan Node.js http-server (Global)

Install http-server secara global:
```bash
npm install -g http-server
```

Kemudian jalankan:
```bash
cd frondend/src
http-server -p 3000
```

### Opsi 4: Menggunakan VS Code Live Server Extension

1. Install extension "Live Server" di VS Code
2. Klik kanan pada file `index.html`
3. Pilih "Open with Live Server"

### Opsi 5: Menggunakan PHP Built-in Server

```bash
cd frondend/src
php -S localhost:3000
```

## Catatan Penting

⚠️ **TIDAK BISA** langsung double-click file HTML karena:
- ES6 modules (import/export) memerlukan HTTP server
- Fetch API akan error karena CORS policy
- LocalStorage mungkin tidak bekerja dengan baik

✅ **HARUS** menggunakan HTTP server seperti di atas

## URL Frontend

Setelah menjalankan server, buka browser dan akses:
- `http://localhost:3000` - Halaman utama
- `http://localhost:3000/login.html` - Halaman login
- `http://localhost:3000/register.html` - Halaman register
- `http://localhost:3000/dashboard.html` - Dashboard user
- `http://localhost:3000/admin-dashboard.html` - Dashboard admin

## Port yang Digunakan

- Frontend: `http://localhost:3000` (default, bisa diubah)
- Backend: `http://localhost:8000` (sesuai dengan `php artisan serve`)

Pastikan backend juga berjalan di port 8000!

