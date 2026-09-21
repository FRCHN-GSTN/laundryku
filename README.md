# Laundryku

Aplikasi web pemesanan laundry online yang memudahkan pelanggan dalam melakukan pemesanan layanan laundry secara digital. Dibangun dengan CodeIgniter 4 dan desain Dark Neo-Glow Minimalism.

## Fitur Utama

### Pelanggan
- Registrasi dan login
- Pemesanan laundry baru dengan kalkulasi harga real-time
- Riwayat pemesanan dan detail status
- Pembatalan pesanan (status pending)
- Manajemen profil

### Admin
- Dashboard dengan statistik hari ini (pesanan, revenue, status)
- Manajemen pesanan dengan alur status: `pending` > `confirmed` > `washing` > `drying` > `ironing` > `ready` > `delivered` > `completed`
- CRUD layanan laundry (kiloan, satuan, express, spa)
- Manajemen pelanggan
- Manajemen promosi dan FAQ
- Pengaturan dinamis (nama perusahaan, kontak, konten hero, dll)
- Laporan revenue berdasarkan rentang tanggal

### Landing Page
- Hero section dinamis dari database
- Layanan unggulan
- Cara kerja
- Promosi aktif
- FAQ
- Testimonial pelanggan

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | CodeIgniter 4 |
| PHP | 8.2+ |
| Database | MySQL |
| CSS | Tailwind CSS (CDN) |
| Icons | Heroicons (SVG inline) |
| JavaScript | Vanilla JS |

## Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL
- Web server (Laragon, XAMPP, atau sejenisnya)

### Langkah-langkah

1. Clone repository
```bash
git clone https://github.com/username/laundryku.git
cd laundryku
```

2. Install dependencies
```bash
composer install
```

3. Copy `.env` dan konfigurasi
```bash
cp env .env
```
Edit `.env` sesuai kebutuhan:
- `app.baseURL` = URL aplikasi (default: `http://localhost:8080/`)
- Database: `DB.Database=laundryku`, `DB.Username=root`, `DB.Password=`

4. Buat database MySQL
```sql
CREATE DATABASE laundryku;
```

5. Jalankan migrasi
```bash
php spark migrate
```

6. Jalankan seeder (membuat data awal: layanan, pengaturan, admin)
```bash
php spark db:seed DatabaseSeeder
```

7. Jalankan development server
```bash
php spark serve
```

Atau konfigurasi web server untuk pointing ke folder `public/`.

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@laundryku.com | admin123 |
| Customer | - | Register di `/auth/register` |

## Struktur Database

| Tabel | Keterangan |
|-------|------------|
| `users` | Akun pelanggan dan admin |
| `services` | Layanan laundry |
| `orders` | Pesanan |
| `order_items` | Item dalam pesanan |
| `payments` | Pembayaran |
| `ratings` | Rating dan ulasan |
| `promotions` | Promosi |
| `settings` | Pengaturan dinamis |
| `faqs` | FAQ |
| `features` | Fitur landing page |
| `how_it_steps` | Langkah cara kerja |

## License

MIT
