# Setup Midtrans Payment Gateway

## 1. Daftar Akun Midtrans

1. Kunjungi [Midtrans Dashboard](https://dashboard.midtrans.com)
2. Daftar akun baru atau login jika sudah punya akun
3. Pilih environment **Sandbox** untuk testing

## 2. Dapatkan API Keys

1. Login ke Midtrans Dashboard
2. Pilih environment **Sandbox**
3. Pergi ke **Settings** → **Access Keys**
4. Copy **Server Key** dan **Client Key**

## 3. Konfigurasi di Laravel

✅ **SUDAH DIKONFIGURASI** - File `.env` sudah diupdate dengan keys production:

```env
# Midtrans Configuration
# Production keys for Merchant ID: G318853598
MIDTRANS_SERVER_KEY=Mid-server-U7rf59QxieySbeJo25vlEUIt
MIDTRANS_CLIENT_KEY=Mid-client-mkuHa5UeGZ4wQG7B
MIDTRANS_IS_PRODUCTION=true
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

## 4. Setup Webhook URL

1. Di Midtrans Dashboard, pergi ke **Settings** → **Configuration**
2. Set **Payment Notification URL** ke:
   ```
   https://yourdomain.com/payment/notification
   ```
3. Untuk development lokal, gunakan ngrok:
   ```bash
   ngrok http 8000
   ```
   Kemudian set URL ke: `https://your-ngrok-url.ngrok.io/payment/notification`

## 5. Test Konfigurasi

Jalankan command untuk test koneksi:

```bash
php artisan midtrans:test
```

## 6. Test Payment

1. Akses aplikasi di browser
2. Pilih kamar dan lakukan booking
3. Di halaman payment, gunakan test card numbers:
   - **Visa**: 4811 1111 1111 1114
   - **Mastercard**: 5211 1111 1111 1117
   - **CVV**: 123
   - **Expiry**: 12/25

## 7. Production Setup

Untuk production:

1. Upgrade akun Midtrans ke Production
2. Dapatkan Production keys
3. Update `.env`:
   ```env
   MIDTRANS_IS_PRODUCTION=true
   MIDTRANS_SERVER_KEY=Mid-server-YOUR_PRODUCTION_SERVER_KEY
   MIDTRANS_CLIENT_KEY=Mid-client-YOUR_PRODUCTION_CLIENT_KEY
   ```

## Troubleshooting

### Error 401 - Unauthorized
- Pastikan Server Key dan Client Key benar
- Pastikan menggunakan Sandbox keys untuk testing
- Pastikan tidak ada spasi di awal/akhir keys

### Webhook tidak berfungsi
- Pastikan URL webhook dapat diakses dari internet
- Gunakan ngrok untuk development lokal
- Check firewall settings

### Payment tidak redirect
- Pastikan callback URLs sudah benar di konfigurasi
- Check browser console untuk error JavaScript

## Demo Mode

Jika belum punya akun Midtrans, aplikasi akan berjalan dalam demo mode dengan simulasi pembayaran.
