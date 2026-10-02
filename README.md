# TV Display Management System

![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)
![Framework](https://img.shields.io/badge/CodeIgniter-4.7.4-EF4223?style=flat-square&logo=codeigniter&logoColor=white)
![CSS](https://img.shields.io/badge/TailwindCSS-v4.3.3-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Database](https://img.shields.io/badge/MySQL-8.4%2B-4479A1?style=flat-square&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-Open%20Source-green?style=flat-square)
[![Trakteer](https://img.shields.io/badge/Trakteer-Support-red?style=flat-square)](https://trakteer.id/rey_kosasih)
[![PayPal](https://img.shields.io/badge/PayPal-Donate-00457C?style=flat-square&logo=paypal&logoColor=white)](https://paypal.me/reydwikosasih)

**TV Display Management System** adalah aplikasi web berbasis CodeIgniter 4 yang bersifat **Open Source Software** guna mengelola dan menayangkan slideshow media secara otomatis, terpusat, dan real-time pada beberapa layar TV / Display di lingkungan kerja atau pribadi.

---

## 📌 Fitur Utama

- 📺 **Multi-Display & PIN Verification**:
  - Manajemen beberapa unit TV/Display secara independen.
  - Akses halaman display dilindungi PIN 6-digit per TV.
  - **Instant PIN Invalidation**: Sesi TV langsung hangus otomatis jika Superadmin melakukan *regenerate* PIN.
- ⚡ **Real-Time Update via SSE (Server-Sent Events)**:
  - Perubahan playlist, penambahan konten, atau pergantian PIN langsung berdampak pada tampilan TV tanpa perlu refresh halaman manual.
  - Mekanisme SSE native PHP yang ringan dan efisien tanpa perlu dependensi external broker.
- 🎬 **Dukungan Media Multimorfik**:
  - **Gambar**: Upload gambar banner/informasi (JPG, PNG, WebP) dengan durasi rotasi kustom.
  - **Video**: Upload video MP4/WebM (hingga 100MB dengan auto-detect durasi via `getID3`) atau Embed YouTube tanpa memerlukan YouTube Data API key.
  - **Chart**: Visualisasi data interaktif (Bar, Line, Pie) berbasis JSON di-render via Chart.js.
- 📂 **Kategori & Dynamic Drag-and-Drop Playlist**:
  - Pengelompokan konten berdasarkan kategori dengan badge warna Hex.
  - Penyusunan urutan slide per TV secara fleksibel menggunakan fitur *drag-and-drop* (SortableJS).
- 👥 **Role-Based Access Control (RBAC)**:
  - **Superadmin**: Akses penuh (Manajemen User, TV, Kategori, Konten, & Playlist).
  - **Admin**: Akses pengelolaan konten & playlist per TV.
- 📊 **Display Monitoring**:
  - Melacak status TV *Online* / *Offline* secara real-time di Dashboard berdasarkan heartbeat `last_seen_at`.

---

## 🛠️ Tech Stack & Dependencies

| Komponen | Teknologi / Library | Versi | Deskripsi |
|---|---|---|---|
| **Backend Framework** | CodeIgniter | v4.7.4 | Architecture HMVC-style modular |
| **Runtime Environment** | PHP | v8.3+ | Stack Laragon / Web Server |
| **Database** | MySQL / MariaDB | v8.4+ | InnoDB Engine dengan Relational Constraints |
| **CSS Engine** | Tailwind CSS | v4.3.3 | `@tailwindcss/cli` dikompilasi ke `public/css/app.css` |
| **Icon Library** | FontAwesome | v6.5.1 | Vendor Lokal (`vendor/fontawesome`) |
| **Frontend JS** | jQuery | v3.7.1 | Vendor Lokal (`vendor/jquery`) |
| **Chart Engine** | Chart.js | v4.4.x | Vendor Lokal (`vendor/chartjs`) |
| **Reorder Component** | SortableJS | v1.15.2 | Vendor Lokal (`vendor/sortablejs`) |
| **Alert & Notification** | SweetAlert2 | v11.x | Vendor Lokal (`vendor/sweetalert2`) |
| **Dropdown Component** | Select2 | v4.1.0-rc.0 | Vendor Lokal (`vendor/select2`) |
| **Real-Time Handler** | Custom `SseStreamer` | - | Library internal dengan *burst-looping* & *session-release* |
| **Video Metadata** | getID3 | - | Auto-duration extractor untuk file video MP4 |

---

## 🚀 Panduan Instalasi & Pengaturan

### 1. Persyaratan Sistem
- PHP >= 8.3 dengan ekstensi: `gd`, `intl`, `mbstring`, `mysqli`, `curl`, `json`.
- Composer 2.x
- Node.js >= 18.x & npm
- Web Server (Apache/Nginx/Laragon) atau PHP Built-in CLI Server.

### 2. Langkah Instalasi

1. **Clone / Persiapkan Repositori**:
   Pastikan direktori proyek berada di lokasi web server (contoh Laragon):
   ```bash
   c:\laragon\www\oss-displaytv
   ```

2. **Instal Dependensi PHP (Composer)**:
   ```bash
   composer install
   ```

3. **Instal Dependensi Frontend & Build Tool (npm)**:
   ```bash
   npm install
   ```
   > [!NOTE]
   > File library vendor frontend untuk browser (jQuery, FontAwesome, Chart.js, Select2, SortableJS, SweetAlert2) sudah tersedia langsung secara lokal di folder `public/vendor/` sehingga aplikasi dapat langsung berjalan tanpa perlu setup CDN eksternal.

4. **Konfigurasi Environment**:
   Salin file `env` menjadi `.env`:
   ```bash
   cp env .env
   ```
   Buka file `.env` dan atur konfigurasi database serta URL aplikasi:
   ```ini
   CI_ENVIRONMENT = development

   app.baseURL = 'http://localhost:8080/'

   database.default.hostname = localhost
   database.default.database = oss_displaytv
   database.default.username = root
   database.default.password = 
   database.default.DBDriver = MySQLi
   ```

5. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php spark migrate
   php spark db:seed SuperadminSeeder
   ```

6. **Kompilasi CSS (Tailwind v4)**:
   ```bash
   npm run build:css
   ```

7. **Jalankan Server Development**:
   ```bash
   php spark serve
   ```
   Aplikasi dapat diakses di browser melalui URL: `http://localhost:8080` (atau `http://localhost/oss-displaytv/public` via Laragon).

---

## 🔑 Kredensial Default

Setelah menjalankan `SuperadminSeeder`, gunakan akun berikut untuk masuk ke Dashboard Admin:

| Role | Email | Password | Hak Akses |
|---|---|---|---|
| **Superadmin** | `superadmin@displaytv.com` | `admin123` | Akses Penuh (TV, User, Kategori, Konten, Playlist) |
| **Admin** | `admin@displaytv.com` | `admin123` | Akses Konten & Playlist |

---

## ⚠️ Developer Guidelines & Catatan Penting

> [!IMPORTANT]
> **Single-Threaded Server & SSE Handling**:
> Perulangan `while` pada `SseController` **HARUS** memanggil `session_write_close()` dan dibatasi iterasi max burst (misal 5 iterasi / 10 detik per koneksi). Jangan membuat infinite loop tanpa melepas session lock, karena akan membekukan PHP built-in web server (`php spark serve`) dan membuat request gambar/halaman lain mengalami *infinite loading*.

> [!IMPORTANT]
> **Tailwind CSS v4 & Dynamic Visibility**:
> `@tailwindcss/cli` v4 hanya mengompilasi utility class yang terdeteksi secara statis di file template. Jangan mengandalkan `classList.add('opacity-100')` secara dinamik via JS jika class `opacity-100` tidak pernah ditulis di HTML statis. Gunakan inline style `element.style.opacity = '1'` atau `style="opacity:0"` untuk elemen manipulasi DOM JS.

> [!TIP]
> **Asset Vendor Lokal**:
> Semua library frontend (FontAwesome, jQuery, Chart.js, Select2, SortableJS, SweetAlert2) disimpan secara lokal di folder `public/vendor/`. Gunakan helper `<?= base_url('vendor/...') ?>` ketimbang CDN eksternal untuk menjamin keandalan saat jaringan offline/intranet.

> [!NOTE]
> **YouTube Embed tanpa API**:
> Untuk slide YouTube, gunakan format URL `https://www.youtube-nocookie.com/embed/{youtube_id}?autoplay=1&mute=1&controls=0` melalui tag `<iframe>`. Hindari ketergantungan pada `www.youtube.com/iframe_api` untuk mencegah isu blocking CORS atau ad-blocker pada TV display.

---

## 📂 Struktur Direktori Utama

```
oss-displaytv/
├── app/
│   ├── Controllers/
│   │   ├── Admin/         # Dashboard, TV, Category, Content, Playlist, User
│   │   ├── Auth/          # Login / Logout Authentication
│   │   └── Display/       # Landing Page, Display Slideshow, SSE Controller
│   ├── Filters/           # AuthFilter, SuperadminFilter, TvPinSessionFilter
│   ├── Libraries/         # SseStreamer (SSE Event Streamer Engine)
│   ├── Models/            # TvModel, ContentModel, CategoryModel, UserModel, dll.
│   └── Views/
│       ├── admin/         # Halaman Panel Kelola Admin
│       ├── display/       # Landing TV, PIN Modal, & Slideshow Display
│       └── layouts/       # Master Template Layouts
├── public/
│   ├── css/app.css        # Output kompilasi Tailwind CSS v4
│   ├── uploads/           # Folder penyimpanan media (Gambar/Video)
│   └── vendor/            # Vendor Frontend (Chart.js, jQuery, Select2, SortableJS, SweetAlert2, FA)
├── AGENTS.md              # Context & Rules panduan pengembang AI/Human
└── README.md              # Dokumentasi Utama Proyek
```

---

## 💻 Perintah CLI Penting (Commands)

```bash
# Instal dependensi backend (Composer)
composer install

# Instal dependensi frontend & build tool (npm)
npm install

# Menjalankan dev server PHP Spark
php spark serve

# Kompilasi CSS Tailwind v4
npm run build:css

# Watch CSS Tailwind v4 (mode pengembangan)
npm run watch:css

# Menjalankan DB migration & seeder
php spark migrate
php spark db:seed SuperadminSeeder

# Menampilkan seluruh daftar rute aplikasi
php spark routes
```

## ☕ Dukungan & Donasi (Support & Donation)

Jika Anda menyukai proyek ini, merasa terbantu, atau menggunakannya untuk kebutuhan operasional/bisnis, Anda dapat memberikan apresiasi dan mendukung pengembangan lebih lanjut melalui:

- 🇮🇩 **Trakteer**: [https://trakteer.id/rey_kosasih](https://trakteer.id/rey_kosasih)
- 🌐 **PayPal**: [https://paypal.me/reydwikosasih](https://paypal.me/reydwikosasih)

Dukungan Anda sangat berarti untuk menjaga proyek ini tetap aktif dan terus berkembang. Terima kasih banyak! ❤️

---

## 📄 Lisensi & Hak Cipta

Hak Cipta © 2026 **Rey Dwi Kosasih**. Seluruh hak dilindungi undang-undang. Open Source Software. Community Project. Share knowledge for humanity. Donate at [PayPal](https://paypal.me/reydwikosasih) or [Trakteer](https://trakteer.id/rey_kosasih).


---

## 🔗 Social Media & Contact

| Platform | Link | Deskripsi |
|---|---|---|
| **Instagram** | [https://instagram.com/rey_dk](https://instagram.com/rey_dk) | Personal & Project Updates |
| **Trakteer** | [https://trakteer.id/rey_kosasih](https://trakteer.id/rey_kosasih) | Support Me |
| **GitHub** | [https://github.com/reydkosasih](https://github.com/reydkosasih) | Source Code |
| **LinkedIn** | [https://linkedin.com/in/rey-dwi-kosasih](https://linkedin.com/in/rey-dwi-kosasih) | Professional Network |
| **X / Twitter** | [https://x.com/rey_dk](https://x.com/rey_dk) | Thoughts & Updates |

