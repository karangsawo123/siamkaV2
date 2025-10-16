# SIAMKA - Sistem Informasi Aset Manajemen Kampus

## 📋 Deskripsi
Sistem manajemen aset kampus berbasis web untuk mengelola peminjaman, maintenance, dan tracking aset.

## 👥 Tim Pengembang
- **Scrum Master:** [Nama]
- **Product Owner 1 (UI/UX):** [Nama]
- **Product Owner 2 (Business):** [Nama]
- **Developer 1 (Auth & Users):** [Nama]
- **Developer 2 (Assets):** [Nama]
- **Developer 3 (Transactions):** [Nama]

## 🛠️ Technology Stack
- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Server:** Apache (XAMPP)

## ✨ Fitur Utama
1. Authentication & Authorization (Role-based)
2. Asset Management (CRUD dengan foto)
3. Loan Management (Request, Approve, Return)
4. Damage Report System
5. Maintenance Scheduling
6. Dashboard & Statistics
7. Reports & Export (CSV)

## 🚀 Instalasi

### Requirements
- XAMPP (PHP 7.4+, MySQL 5.7+)
- Web Browser (Chrome, Firefox, Edge)
- Text Editor (VSCode recommended)

### Langkah Instalasi
1. Clone/Download project ke `htdocs/`
2. Import database dari `database/siamka_empty.sql`
3. Copy `config/database.example.php` ke `config/database.php`
4. Edit `config/database.php` dengan kredensial database Anda
5. Buka browser: `http://localhost/SIAMKA`

### Default Login
- **Admin:** admin@kampus.ac.id / admin123
- **User:** user@kampus.ac.id / user123
- **Management:** management@kampus.ac.id / mgmt123

## 📁 Struktur Folder
```
SIAMKA/
├── config/          # Konfigurasi
├── includes/        # Components & helpers
├── modules/         # Main application modules
├── assets/          # CSS, JS, Images
├── database/        # SQL files
└── docs/            # Dokumentasi
```

## 📝 License
MIT License - Free for educational purposes

## 📧 Kontak
- Email: [team.email@example.com]
- GitHub: [repository-url]

---
Developed with ❤️ by [Team Name]
