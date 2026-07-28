# Sistem Pengajuan Cuti Karyawan

## Deskripsi

Aplikasi web manajemen pengajuan cuti karyawan yang dibangun dengan Laravel 12. Sistem ini memungkinkan karyawan mengajukan cuti, admin menyetujui/menolak pengajuan, dan tracking kuota cuti secara otomatis.

## Fitur Utama

- **Autentikasi** - Login, registrasi, dan manajemen profil (Laravel Breeze)
- **Role-based Access Control** - Admin dan Karyawan dengan hak akses berbeda
- **Manajemen Jenis Cuti** - CRUD jenis cuti (Cuti Tahunan, Sakit, Melahirkan, dll)
- **Pengajuan Cuti** - Karyawan mengajukan cuti dengan validasi kuota
- **Approval Cuti** - Admin menyetujui/menolak pengajuan dengan alasan
- **Kuota Cuti** - Tracking otomatis sisa kuota cuti per jenis per tahun
- **Dashboard** - Statistik real-time untuk admin dan karyawan

## Teknologi

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.2, Laravel 12 |
| Frontend | Blade Templates, Tailwind CSS, Alpine.js |
| Database | SQLite (dev), MySQL (production) |
| Build | Vite 7 |
| Auth | Laravel Breeze |

## Instalasi

```bash
# Clone repository
git clone <repository-url>
cd leave-management-system

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate:fresh --seed

# Build frontend
npm run build

# Jalankan aplikasi
php artisan serve
```

## Akun Testing

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@cuti.com | password |
| Karyawan | budi@cuti.com | password |
| Karyawan | siti@cuti.com | password |
| Karyawan | ahmad@cuti.com | password |

## Struktur Proyek

```
├── app/
│   ├── Exceptions/          # Custom exception handling
│   ├── Events/              # Domain events
│   ├── Http/
│   │   ├── Controllers/     # Request handlers
│   │   ├── Middleware/       # Role middleware
│   │   └── Requests/        # Form validation
│   ├── Listeners/           # Event listeners
│   ├── Models/              # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Services/            # Business logic layer
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Sample data
├── docs/
│   └── uml/                 # Diagram UML (PlantUML)
├── resources/
│   └── views/               # Blade templates
└── routes/
    ├── web.php              # Web routes
    └── auth.php             # Auth routes
```

## Dokumentasi

- [Arsitektur Sistem](docs/architecture.md)
- [Diagram UML](docs/uml/README.md)
- [Panduan API](docs/api.md)
