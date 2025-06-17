# Sistem Informasi Reservasi Hotel

Sistem informasi reservasi hotel dengan integrasi payment gateway Midtrans yang dibangun menggunakan Laravel 11.

## Fitur Utama

### Admin Panel
- **Dashboard** dengan statistik hotel
- **Manajemen Kamar** (CRUD)
- **Manajemen Tamu** (CRUD)
- **Manajemen Transaksi** (CRUD)
- **Laporan** dan export data
- **Authentication** untuk admin/staff

### Payment Gateway
- Integrasi **Midtrans** untuk pembayaran
- Support multiple payment methods
- Webhook notification handling
- Transaction status tracking

### Database Structure
- `tb_petugas` - Data admin/staff
- `tb_tamu` - Data tamu/customer
- `tb_kamar` - Data kamar hotel
- `tb_tipe_kamar` - Tipe kamar
- `tb_layanan` - Layanan hotel
- `tb_transaksi` - Data transaksi/reservasi

## Instalasi

### Requirements
- PHP 8.2+
- Composer
- SQLite/MySQL
- Node.js & NPM (optional)

### Langkah Instalasi

1. **Clone repository**
```bash
git clone <repository-url>
cd hotel
```

2. **Install dependencies**
```bash
composer install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database**
Edit file `.env` sesuai kebutuhan:
```env
DB_CONNECTION=sqlite
# atau untuk MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hotel_db
# DB_USERNAME=root
# DB_PASSWORD=
```

5. **Konfigurasi Midtrans**
Daftar di [Midtrans](https://midtrans.com) dan dapatkan Server Key & Client Key, lalu update `.env`:
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=SB-Mid-client-YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
```

6. **Jalankan migration dan seeder**
```bash
php artisan migrate
php artisan db:seed
```

7. **Buat direktori upload**
```bash
mkdir -p public/uploads/kamar
```

8. **Jalankan server**
```bash
php artisan serve
```

## Login Admin

Setelah menjalankan seeder, gunakan kredensial berikut:

**Admin:**
- Username: `admin`
- Password: `admin123`

**Staff:**
- Username: `staff`
- Password: `staff123`

## Akses Aplikasi

- **Admin Panel:** http://localhost:8000/admin/login
- **Dashboard:** http://localhost:8000/admin/dashboard

## Struktur Project

```
app/
├── Http/Controllers/
│   ├── Admin/           # Controllers untuk admin panel
│   └── PaymentController.php
├── Models/              # Eloquent models
├── Services/
│   └── MidtransService.php
└── Http/Middleware/
    └── AdminAuth.php

resources/views/
├── admin/
│   ├── layouts/         # Layout admin
│   ├── auth/           # Login admin
│   ├── dashboard.blade.php
│   └── kamar/          # CRUD kamar
└── payment/            # Payment views

database/
├── migrations/         # Database migrations
└── seeders/           # Data seeder
```

## API Endpoints

### Admin Routes
- `GET /admin/login` - Halaman login
- `POST /admin/login` - Proses login
- `GET /admin/dashboard` - Dashboard admin
- `GET /admin/kamar` - Daftar kamar
- `POST /admin/kamar` - Tambah kamar
- `PUT /admin/kamar/{id}` - Update kamar
- `DELETE /admin/kamar/{id}` - Hapus kamar

### Payment Routes
- `GET /payment/create/{transaksi}` - Buat pembayaran
- `POST /payment/notification` - Webhook Midtrans
- `GET /payment/finish` - Halaman sukses
- `GET /payment/unfinish` - Halaman pending
- `GET /payment/error` - Halaman error

## Konfigurasi Midtrans

1. Login ke [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Pilih environment (Sandbox untuk testing)
3. Dapatkan Server Key dan Client Key
4. Setup webhook URL: `https://yourdomain.com/payment/notification`
5. Update konfigurasi di `.env`

## Contributing

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## License

Project ini menggunakan [MIT License](LICENSE).
