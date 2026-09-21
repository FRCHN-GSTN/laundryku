# AGENTS.md - Laundryku Project

## Project Overview

Laundryku adalah aplikasi web pemesanan laundry online yang dibangun dengan CodeIgniter 4.

## Tech Stack

- Backend: CodeIgniter 4 (PHP 8.2+)
- Database: PostgreSQL
- Frontend: Tailwind CSS, vanilla JavaScript
- Icons: Heroicons (SVG inline)

## Development Guidelines

### Code Style

- Gunakan 4 spasi untuk indentasi
- Ikuti PSR-12 coding standard
- Gunakan snake_case untuk nama method dan variable di PHP
- Gunakan camelCase untuk nama variable di JavaScript

### File Structure

```
app/
  Controllers/    - Controller classes
  Models/         - Model classes
  Views/          - Template files
  Config/         - Configuration
  Database/       - Migration files
```

### Database

- Gunakan PostgreSQL sebagai database
- Setiap tabel harus memiliki created_at dan updated_at
- Gunakan soft deletes untuk data penting

### Security

- Selalu validasi input dari user
- Gunakan CSRF protection
- Hash password dengan password_hash()
- Gunakan prepared statements untuk query

### Testing

- Tulis unit test untuk setiap model
- Test setiap controller method
- Pastikan semua test pass sebelum commit
