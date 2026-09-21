# Product Requirements Document (PRD)
## Aplikasi Order Laundry - "Laundryku"

---

## 1. Overview
Aplikasi web untuk memudahkan pelanggan dalam melakukan pemesanan laundry secara online, menggantikan sistem pemesanan via WhatsApp yang membingungkan dan tidak terstruktur.

## 2. Problem Statement
- Proses pemesanan via WhatsApp membingungkan dan tidak terstruktur
- Urutan pesanan sering tertukar atau tidak jelas
- Kesulitan melacak status pesanan
- Tidak ada riwayat transaksi yang terorganisir

## 3. Goals
- Menyediakan platform pemesanan yang mudah dan intuitif
- Mengotomatisasi alur pesanan dari penerimaan hingga selesai
- Memberikan visibilitas status pesanan secara real-time
- Menyimpan riwayat transaksi secara digital

## 4. Target Users
| Role | Description |
|------|-------------|
| Customer | Pelanggan yang ingin memesan jasa laundry |
| Admin | Pemilik/karyawan laundry yang mengelola pesanan |

## 5. Fitur Utama

### 5.1 Customer Side
| Fitur | Deskripsi | Priority |
|-------|-----------|----------|
| Register/Login | Daftar dan masuk akun pelanggan | Must Have |
| Home | Dashboard dengan info layanan | Must Have |
| Order Baru | Buat pesanan laundry baru | Must Have |
| Pilih Layanan | Pilih jenis cuci (kiloan, satuan, dll) | Must Have |
| Pilih Pengiriman | Ambil sendiri atau dijemput | Must Have |
| Histori Pesanan | Lihat daftar pesanan sebelumnya | Must Have |
| Detail Pesanan | Lihat detail & status pesanan | Must Have |
| Pembayaran | Konfirmasi pembayaran | Must Have |
| Rating & Review | Beri penilaian setelah selesai | Nice to Have |
| Notifikasi | Notifikasi status pesanan | Nice to Have |

### 5.2 Admin Side
| Fitur | Deskripsi | Priority |
|-------|-----------|----------|
| Dashboard | Ringkasan pesanan hari ini | Must Have |
| Kelola Pesanan | Update status pesanan | Must Have |
| Kelola Layanan | CRUD jenis layanan & harga | Must Have |
| Kelola Pelanggan | Lihat data pelanggan | Must Have |
| Laporan | Laporan pendapatan | Nice to Have |
| Notifikasi | Notifikasi pesanan baru | Must Have |

## 6. Alur Pesanan

```
Customer Buat Order
       ↓
Admin Terima & Konfirmasi
       ↓
Proses Laundry (Cuci → Jemur → Setrika)
       ↓
Siap Diambil/Diantar
       ↓
Pembayaran
       ↓
Selesai
```

## 7. Status Pesanan

| Status | Keterangan |
|--------|------------|
| `pending` | Menunggu konfirmasi admin |
| `confirmed` | Pesanan dikonfirmasi |
| `washing` | Sedang dicuci |
| `drying` | Sedang dijemur |
| `ironing` | Sedang disetrika |
| `ready` | Siap diambil/diantar |
| `delivered` | Sudah diantar/diambil |
| `completed` | Selesai & dibayar |
| `cancelled` | Dibatalkan |

## 8. Layanan (Contoh)

| Layanan | Harga | Satuan |
|---------|-------|--------|
| Cuci Kering | Rp 7.000 | /kg |
| Cuci Setrika | Rp 9.000 | /kg |
| Cuci Kering Setrika | Rp 12.000 | /kg |
| Setrika Saja | Rp 5.000 | /kg |
| Dry Clean | Rp 15.000 | /pcs |
| Bed Cover | Rp 20.000 | /pcs |
| Selimut | Rp 15.000 | /pcs |
| Gorden | Rp 18.000 | /pcs |

## 9. Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | CodeIgniter 4 |
| Database | PostgreSQL |
| Frontend | HTML, CSS, JavaScript (custom UI) |
| Server | Laragon (local development) |
| CSS Framework | Tailwind CSS |
| Icons | Lucide Icons / Phosphor Icons |

## 9.1 Design System - Color Palette

| Name | Hex Code | Usage |
|------|----------|-------|
| Dark BG | `#191825` | Background utama |
| Deep Purple | `#060047` | Background sekunder, sidebar |
| Primary | `#865DFF` | Tombol utama, link, accent |
| Light Purple | `#E384FF` | Hover state, highlight |
| Soft Pink | `#FFA3FD` | Badge, tag, informasi |
| Hot Pink | `#B3005E` | Aksen kuat, warning |
| Magenta | `#E90064` | Error, notifikasi penting |
| Rose Pink | `#FF5F9E` | Success, hover aksen |

### UI/UX Guidelines
- **Style**: Modern, clean, dengan gradient halus
- **Background**: Dark theme dengan `#191825` sebagai base
- **Cards**: Background transparan/blur dengan border subtle
- **Buttons**: Gradient dari `#865DFF` ke `#E384FF`
- **Typography**: Inter / Poppins font family
- **Border Radius**: 12px-16px untuk cards, 8px untuk buttons
- **Shadows**: Soft glow effect menggunakan color palette
- **Animations**: Subtle transition 200-300ms

## 10. Database Schema

### PostgreSQL Tables

#### users
```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### services
```sql
CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    unit ENUM('kg', 'pcs') DEFAULT 'kg',
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### orders
```sql
CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    order_code VARCHAR(20) UNIQUE NOT NULL,
    user_id INTEGER REFERENCES users(id),
    total_weight DECIMAL(5,2),
    total_price DECIMAL(10,2) NOT NULL,
    delivery_type ENUM('pickup', 'delivery') DEFAULT 'pickup',
    delivery_address TEXT,
    notes TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### order_items
```sql
CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id),
    service_id INTEGER REFERENCES services(id),
    quantity DECIMAL(5,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL
);
```

#### payments
```sql
CREATE TABLE payments (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id),
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50),
    payment_date TIMESTAMP,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### ratings
```sql
CREATE TABLE ratings (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id),
    user_id INTEGER REFERENCES users(id),
    rating INTEGER CHECK (rating >= 1 AND rating <= 5),
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 11. UI/UX Design Concept

### Design Style: Modern Dark Gradient
- Dark background dengan gradient subtle
- Glassmorphism effect pada cards
- Neon/glow accent pada interactive elements
- Smooth animations dan transitions

### Color Application
```
Background:     #191825 (main) → #060047 (sidebar/sections)
Primary Action: #865DFF → #E384FF (gradient buttons)
Status Badge:   #FFA3FD (info), #FF5F9E (success), #E90064 (error)
Accent:         #B3005E (highlights, borders)
```

### Layout Structure
```
┌──────────────────────────────────────────────────────┐
│  ░░░░░ SIDEBAR (#060047) ░░░░░░░ MAIN (#191825) ░░░ │
│  ┌─────────┐  ┌──────────────────────────────────┐  │
│  │ Logo    │  │  Header with user info           │  │
│  │         │  ├──────────────────────────────────┤  │
│  │ Menu 1  │  │                                  │  │
│  │ Menu 2  │  │  Content Area                    │  │
│  │ Menu 3  │  │  (Cards with glassmorphism)      │  │
│  │         │  │                                  │  │
│  │         │  │  ┌─────┐ ┌─────┐ ┌─────┐        │  │
│  │         │  │  │Card │ │Card │ │Card │        │  │
│  │         │  │  └─────┘ └─────┘ └─────┘        │  │
│  └─────────┘  └──────────────────────────────────┘  │
└──────────────────────────────────────────────────────┘
```

### Component Styling
| Component | Style |
|-----------|-------|
| Sidebar | Background #060047, active menu glow #865DFF |
| Cards | rgba(255,255,255,0.05) background, backdrop-blur |
| Primary Button | Gradient #865DFF → #E384FF, hover glow |
| Input Fields | Dark bg, border #865DFF on focus |
| Status Badge | Rounded, colored bg sesuai status |
| Navigation | Pill-style active indicator |
| Tables | Zebra rows, hover highlight |

### Responsive Breakpoints
- Desktop: > 1024px (sidebar + content)
- Tablet: 768px - 1024px (collapsed sidebar)
- Mobile: < 768px (bottom navigation)

## 12. Milestones

| Phase | Target | Deliverables |
|-------|--------|--------------|
| Phase 1 | Minggu 1 | Setup project, DB schema, autentikasi |
| Phase 2 | Minggu 2 | Fitur order (customer side) |
| Phase 3 | Minggu 3 | Fitur admin (kelola pesanan) |
| Phase 4 | Minggu 4 | Notifikasi, laporan, finishing |

## 13. Future Enhancement
- Integrasi payment gateway (Midtrans, Xendit)
- Aplikasi mobile (Flutter/React Native)
- Chat dengan admin
- Promo & voucher system
- Multi-outlet support

---

**Created:** September 2026
**Version:** 1.0
