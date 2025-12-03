# 🎫 PENS Help Desk Ticket System

<p align="center">
  <img src="public/aset/logo-PensHelpDes.svg" alt="PENS Help Desk Logo" width="300">
</p>

<p align="center">
  <a href="https://penshelpdesk.web.id" target="_blank">
    <img src="https://img.shields.io/badge/🌐_Live_Demo-penshelpdesk.web.id-blue?style=for-the-badge" alt="Live Demo">
  </a>
</p>

Sistem manajemen tiket help desk untuk Politeknik Elektronika Negeri Surabaya (PENS). Aplikasi ini memungkinkan mahasiswa melaporkan keluhan fasilitas kampus, admin mengelola tiket, dan teknisi menangani perbaikan secara real-time.

> **Live Demo**: [https://penshelpdesk.web.id](https://penshelpdesk.web.id)

## Fitur Utama

- **Multi-Role Authentication**: Admin, Teknisi, dan Mahasiswa
- **Manajemen Tiket**: Buat, edit, update status tiket
- **Real-time Chat & Komentar**: Laravel Reverb WebSocket
- **Sistem Pengumuman**: Broadcast informasi penting
- **Dashboard Interaktif**: Statistik dan monitoring tiket
- **Upload Gambar**: Dokumentasi bukti keluhan
- **Notifikasi Email**: Update status tiket otomatis
- **Responsive UI**: Tailwind CSS + Alpine.js

## Tech Stack

- **Backend**: Laravel 11.x
- **Database**: PostgreSQL 16
- **Frontend**: Blade, Tailwind CSS, Alpine.js
- **Real-time**: Laravel Reverb (WebSocket)
- **Build Tool**: Vite
- **Containerization**: Docker & Docker Compose

## Prequisites

### Manual Installation
- PHP >= 8.2
- Composer
- Node.js >= 18.x & NPM/PNPM
- PostgreSQL >= 14
- Git

### Docker Installation
- Docker >= 24.x
- Docker Compose >= 2.x

---

## Instalasi & Setup

### Metode 1: Docker Compose (Recommended)

#### Clone Repository
```bash
git clone https://github.com/RahmatBayu18/PnesHelpDeskTicket.git
cd PnesHelpDeskTicket
```

#### Konfigurasi Environment
```bash
# Copy file .env.example
cp .env.example .env

# generate application key 
php artisan key:generate
```

**Edit `.env` untuk Docker:**
```env
APP_NAME=PensHelpDesk
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=penshelpdesk
DB_USERNAME=exampleriski
DB_PASSWORD=exampleriski

REVERB_HOST=reverb
REVERB_PORT=8080
REVERB_SCHEME=http
```

#### Build & Run Containers
```bash
docker-compose up -d --build

docker-compose ps
```

**Services yang berjalan:**
- **App** (Laravel): http://localhost:80
- **Reverb** (WebSocket): http://localhost:8080
- **PostgreSQL**: localhost:5432
- **pgAdmin**: http://localhost:5050

#### Generate Application Key
```bash
# Generate APP_KEY di container
docker-compose exec app php artisan key:generate
```

#### Akses Aplikasi
- **Web App**: http://localhost
- **pgAdmin**: http://localhost:5050
  - Email: `admin@admin.com`
  - Password: `admin`

**Default User Credentials:**
| Role | Email | Password | NIM/ID |
|------|-------|----------|--------|
| Admin | admin@pens.ac.id | password | ADM001 |
| Teknisi | teknisi@pens.ac.id | password | TEK001 |
| Mahasiswa 1 | mahasiswa1@pens.ac.id | password | 3120500001 |
| Mahasiswa 2 | mahasiswa2@pens.ac.id | password | 3120500002 |


#### Troubleshooting Docker

**Jika migrasi gagal saat startup:**
```bash
docker-compose exec app php artisan migrate:fresh --seed
```

**Jika permission error:**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Rebuild frontend assets:**
```bash
docker-compose exec app npm run build
```

---

### Metode 2: Manual Installation

#### Clone Repository
```bash
git clone https://github.com/RahmatBayu18/PnesHelpDeskTicket.git
cd PnesHelpDeskTicket
```

#### Install Dependencies
```bash
# Install dependencies
composer install

# Install Node.js dependencies
npm install
# atau jika menggunakan pnpm
pnpm install
```

#### Konfigurasi Environment
```bash
# Copy file .env.example
cp .env.example .env

# Generate application key
php artisan key:generate
```

#### Konfigurasi Database
Edit file `.env` dan sesuaikan dengan database PostgreSQL :
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=penshelpdesk
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Buat database PostgreSQL:**
```bash
# Login ke PostgreSQL
psql -U postgres

# Buat database
CREATE DATABASE penshelpdesk;

# Keluar
\q
```

#### Konfigurasi Email (Opsional)
Untuk notifikasi email, konfigurasi SMTP di `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@pens.ac.id"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Migrasi & Seeding Database
```bash
php artisan migrate

php artisan db:seed --class=UserSeeder
```

#### Storage Link & Build Assets
```bash
# Buat ssymlink untuk storage
php artisan storage:link

npm run build

# untuk development server
npm run dev
```

#### Jalankan Aplikasi
```bash
# Terminal 1: Laravel Development Server
php artisan serve
# atau dngan composer
composer run dev

# Terminal 2: Laravel Reverb WebSocket Server
php artisan reverb:start
```

#### Akses Aplikasi
Buka browser dan akses: **http://localhost:8000**

**Default User Credentials:**
| Role | Email | Password | NIM/ID |
|------|-------|----------|--------|
| Admin | admin@pens.ac.id | password | ADM001 |
| Teknisi | teknisi@pens.ac.id | password | TEK001 |
| Mahasiswa 1 | mahasiswa1@pens.ac.id | password | 3120500001 |
| Mahasiswa 2 | mahasiswa2@pens.ac.id | password | 3120500002 |

---

## Testing

```bash
# Manual
php artisan test

# Docker
docker-compose exec app php artisan test
```

## Struktur Project

```
PnesHelpDeskTicket/
├── app/
│   ├── Http/Controllers/      # Controller logic
│   ├── Models/                # Eloquent models
│   ├── Notifications/         # Email notifications
│   └── Events/                # WebSocket events
├── database/
│   ├── migrations/            # Database schema
│   └── seeders/               # Sample data
├── resources/
│   ├── views/                 # Blade templates
│   ├── js/                    # JavaScript files
│   └── css/                   # Tailwind CSS
├── public/
│   ├── aset/                  # Static assets (images, logos)
│   └── storage/               # Symlink ke storage/app/public
├── routes/
│   ├── web.php                # Web routes
│   └── channels.php           # Broadcasting channels
├── docker-compose.yml         # Docker orchestration
├── Dockerfile                 # Docker image
└── .env.example               # Environment template
```

## Konfigurasi Tambahan

### Real-time Notifications
WebSocket menggunakan Laravel Reverb. Pastikan konfigurasi di `.env`:
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
```

### Email Verification
Aktifkan email verification di `.env`:
```env
MAIL_MAILER=smtp
# ... konfigurasi SMTP
```

## Kontribusi

Kontribusi sangat diterima! Silakan:
1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Catatan 

- **Storage Link**: Pastikan `php artisan storage:link` sudah dijalankan untuk akses file upload
- **WebSocket**: Laravel Reverb harus running untuk fitur real-time chat
- **Database Seeder**: Menggunakan `UserSeeder` untuk membuat sample data (6 mahasiswa, 12 tiket, admin, teknisi)

## Lisensi

Project ini menggunakan [MIT License](https://opensource.org/licenses/MIT).

## Developer

Dikembangkan oleh Tim PENS Help Desk
- GitHub: [@RahmatBayu18](https://github.com/RahmatBayu18)
- GitHub: [@riskyprsty](https://github.com/riskyprsty)
- GitHub: [@pppercivalll](https://github.com/pppercivalll)

## Support

Jika ada pertanyaan atau issues, silakan buka [GitHub Issues](https://github.com/RahmatBayu18/PnesHelpDeskTicket/issues).

---

<p align="center">Made with ❤️ for PENS</p>
