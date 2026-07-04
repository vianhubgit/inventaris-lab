# 🧪 Inventaris Laboratorium TKJ

Aplikasi web **inventaris laboratorium** untuk jurusan TKJ yang mengelola tiga
laboratorium: **Lab A**, **Lab B**, dan **TEFA**. Dibangun dengan **Laravel 12**,
**Blade**, **Tailwind CSS**, dan **MariaDB/MySQL**, dirancang untuk berjalan di
**jaringan lokal sekolah (LAN)** di atas Ubuntu Server + Nginx + PHP-FPM.

> Login menggunakan **username & password** (tanpa email).

---

## ✨ Deskripsi Project

Aplikasi memiliki dua peran:

| Peran | Hak Akses |
|-------|-----------|
| **Admin** | Kontrol penuh: CRUD pengguna, kategori, barang, tata letak lab; atur jumlah; kelola laporan & perbaikan; menyetujui pengajuan; audit; dashboard lengkap; export PDF & Excel; log aktivitas. |
| **Sekretaris** (1 akun) | Lihat inventaris (read-only); lapor barang rusak/hilang (dengan foto); ajukan penambahan barang; lihat status pengajuan & riwayat laporannya. **Tidak boleh** menambah/mengubah/menghapus inventaris. |

**Struktur lab:**
- **Lab A** & **Lab B** → 6 kelompok × 6 meja, tiap meja berisi **PC, Monitor, Keyboard, Mouse**.
- **TEFA** → tanpa kelompok, hanya inventaris umum.

---

## 📁 Struktur Folder

```
project-lab/
├── app/
│   ├── Console/Commands/        # inventaris:backup
│   ├── Enums/                   # UserRole, ItemStatus, ReportType/Status, dll.
│   ├── Exports/                 # ItemsExport, ReportsExport (Excel)
│   ├── Http/
│   │   ├── Controllers/{Auth,Admin,Sekretaris}/
│   │   ├── Middleware/          # EnsureUserHasRole (role:admin|sekretaris)
│   │   └── Requests/            # FormRequest (validasi)
│   ├── Models/                  # User, Category, Lab, LabGroup, LabTable, Item, Report, Procurement, Repair, StockAudit, ActivityLog
│   ├── Observers/               # ItemObserver, ReportObserver, ProcurementObserver
│   ├── Policies/                # Otorisasi per model
│   ├── Providers/               # App & Auth service provider
│   └── Services/                # DashboardService, ActivityLogger
├── bootstrap/                   # app.php (Laravel 12), providers.php
├── config/
├── database/
│   ├── factories/               # Factory semua model
│   ├── migrations/              # Skema tabel + index + FK
│   └── seeders/                 # Role/User, Category, LabLayout, Demo
├── resources/
│   ├── css/app.css              # Tailwind + komponen @apply
│   ├── js/                      # app.js, charts.js, cascade.js, dark-mode.js, loader.js
│   └── views/
│       ├── layouts/             # app, partials (sidebar, topbar, flash)
│       ├── components/          # stat-card, badge, empty
│       ├── auth/                # login
│       ├── admin/               # dashboard, users, categories, labs, items, reports, repairs, procurements, audits, activities
│       ├── sekretaris/          # dashboard, inventory, reports, procurements
│       └── pdf/                 # template export PDF
├── routes/web.php               # rute web + console.php
├── tests/                       # Feature + Unit
├── DEPLOY_SERVER.txt            # panduan deploy lengkap
└── README.md
```

---

## 🗺️ ERD (Entity Relationship Diagram)

```
users ──< reports >── items ──> categories
  │           │         │
  │           │         ├──> labs ──< lab_groups ──< lab_tables
  │           │         │                                 │
  │           │         └─────────────(lab_table_id)──────┘
  │           └──> labs / lab_groups / lab_tables (lokasi laporan)
  │
  ├──< procurements >── items / categories
  ├──< activity_logs
  │
items ──< repairs >── reports
items ──< stock_audits
```

### Relasi Database

| Tabel | Relasi |
|-------|--------|
| `users` | hasMany `reports`, `procurements`, `activity_logs` |
| `categories` | hasMany `items`, `procurements` |
| `labs` | hasMany `lab_groups`, `items`; hasManyThrough `lab_tables` |
| `lab_groups` | belongsTo `labs`; hasMany `lab_tables` |
| `lab_tables` | belongsTo `lab_groups`; hasMany `items` |
| `items` | belongsTo `categories`, `labs`, `lab_tables`; hasMany `reports`, `repairs`, `stock_audits` |
| `reports` | belongsTo `users`, `labs`, `lab_groups`, `lab_tables`, `items`; hasMany `repairs` |
| `procurements` | belongsTo `users`, `categories`, `items` |
| `repairs` | belongsTo `items`, `reports`, `users` |
| `stock_audits` | belongsTo `items`, `users` |
| `activity_logs` | belongsTo `users` (subject polimorfik via subject_type/id) |

Semua tabel utama memakai **foreign key**, **index**, **constraint**, dan
**soft delete** (kecuali tabel pivot lokasi & log).

---

## 🚀 Daftar Fitur

**Umum**
- Autentikasi username/password (rate-limited), dark mode, responsif, loading indicator, flash message, pagination, search & filter.
- **Notifikasi realtime** (database notifications + lonceng di topbar dengan badge jumlah belum dibaca).
  Badge & isi dropdown diperbarui otomatis tiap 20 detik via polling endpoint JSON `GET /notifications/feed`
  (tanpa WebSocket/server tambahan — cocok untuk LAN; polling berhenti saat tab tidak aktif).
  - **Admin** menerima notifikasi saat ada **laporan baru** (rusak/hilang) dan **pengajuan baru**.
  - **Sekretaris** menerima notifikasi saat admin **menambah barang baru** dan saat **status pengajuannya berubah**.

**Admin**
- Dashboard: statistik, grafik (Chart.js — tren laporan, barang per lab/kategori), barang terbanyak rusak/hilang, pengajuan terbaru, log aktivitas.
- Master data: CRUD Pengguna, Kategori, Tata Letak Lab (lab → kelompok → meja).
- Barang: CRUD + atur jumlah cepat (tambah/kurang/set), status, lokasi.
- Laporan rusak/hilang: kelola & ubah status, lihat foto.
- Riwayat perbaikan: catat perbaikan (sinkron status barang & laporan).
- Pengajuan: ubah status (Menunggu/Disetujui/Ditolak/Sudah Dibeli) + catatan.
- Audit inventaris: bandingkan jumlah tercatat vs fisik (opsional sinkron).
- Export **PDF** (dompdf) & **Excel** (Maatwebsite) untuk barang & laporan.
- Log aktivitas (Observer otomatis).

**Sekretaris**
- Dashboard ringkas + aksi cepat.
- Lihat inventaris (read-only) dengan filter.
- Lapor barang **rusak** & **hilang** — lokasi via **dropdown bertingkat** (lab → kelompok → meja → barang), upload foto.
- Pengajuan barang (barang lama atau **Barang Baru**).
- Riwayat laporan & status pengajuan.

---

## 🛠️ Cara Install (Development di Laptop)

Prasyarat: **PHP 8.3+**, **Composer**, **Node.js 20+**. Database boleh MySQL/MariaDB **atau** SQLite.

### ⚡ Cara tercepat — script otomatis

Linux/macOS:
```bash
cd project-lab
./setup.sh sqlite      # paling mudah: pakai SQLite, tanpa setup MySQL
# atau: ./setup.sh     # pakai MySQL/MariaDB sesuai .env
php artisan serve
```

Windows:
```bat
cd project-lab
setup.bat sqlite
php artisan serve
```

Script akan menjalankan composer install, membuat `.env`, generate key, migrate + seed,
`storage:link`, npm install, dan build aset. Setelah itu buka **http://127.0.0.1:8000**.

### 🔧 Manual

```bash
cd project-lab
composer install
cp .env.example .env
php artisan key:generate

# Opsi A — SQLite (cepat untuk uji coba):
#   set di .env: DB_CONNECTION=sqlite  dan kosongkan DB_DATABASE atau isi path absolut,
#   lalu: touch database/database.sqlite
# Opsi B — MySQL/MariaDB: set DB_DATABASE/DB_USERNAME/DB_PASSWORD dan buat database-nya.

php artisan migrate --seed
php artisan storage:link

npm install
npm run build      # atau: npm run dev (mode pengembangan, hot reload)

php artisan serve  # http://127.0.0.1:8000
```

> **Menguji notifikasi:** buka dua browser (atau mode incognito). Login sebagai
> **sekretaris** lalu buat laporan/pengajuan → lonceng **admin** akan menampilkan badge.
> Login sebagai **admin** lalu tambah barang / ubah status pengajuan → lonceng
> **sekretaris** akan menampilkan badge.

---

## 💻 Cara Development

- Jalankan Vite dev server agar perubahan CSS/JS langsung ter-refresh:
  ```bash
  npm run dev
  ```
- Jalankan test:
  ```bash
  php artisan test
  ```
- Format kode (opsional, Laravel Pint):
  ```bash
  ./vendor/bin/pint
  ```
- Reset & isi ulang data demo:
  ```bash
  php artisan migrate:fresh --seed
  ```

---

## 🌐 Cara Deploy

Lihat **`DEPLOY_SERVER.txt`** untuk panduan sangat lengkap (Ubuntu 24.04 +
Nginx + PHP-FPM 8.3 + MariaDB), mulai dari instalasi server, konfigurasi `.env`,
migrasi/seeder, permission, server block Nginx, firewall, backup otomatis,
hingga update & maintenance.

---

## 👤 Default User

| Peran | Username | Password |
|-------|----------|----------|
| Admin | `admin` | `admin123` |
| Sekretaris | `sekretaris` | `sekretaris123` |

> ⚠️ **Wajib ganti password** setelah login pertama (menu Pengguna).

---

## 🧭 Roadmap Pengembangan

- [ ] Notifikasi in-app saat status pengajuan/laporan berubah.
- [ ] QR Code / barcode per barang untuk audit cepat.
- [ ] Cetak label aset & kartu inventaris per meja.
- [ ] Multi-sekretaris per lab dengan pembagian wilayah.
- [ ] Dashboard ekspor terjadwal (PDF bulanan otomatis via scheduler).
- [ ] Import data barang massal dari Excel.
- [ ] Mode 2 bahasa (ID/EN) dan tema warna sekolah.
- [ ] Riwayat perubahan stok (stock ledger) yang lebih detail.

---

© {tahun} Inventaris Laboratorium TKJ — Lab A, Lab B, TEFA. Lisensi MIT.
