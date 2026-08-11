# RENCANA PENGEMBANGAN — TV Slideshow Display App

## 1. Ringkasan Proyek

Aplikasi web untuk mengelola dan menampilkan slideshow konten (gambar, video, chart dari data manual) pada beberapa TV/display secara independen. Setiap TV memiliki playlist konten sendiri yang disusun dari kombinasi kategori, dengan update konten real-time tanpa perlu refresh manual di sisi TV.

### Tech Stack

| Layer | Teknologi |
|---|---|
| Backend Framework | CodeIgniter 4 (HMVC-style modular structure) |
| PHP Version | 8.3+ |
| Database | MySQL 8.4 |
| CSS Framework | Tailwind CSS v4 |
| Chart Rendering | Chart.js |
| Real-time Update | Server-Sent Events (SSE) — native PHP, tanpa dependency eksternal |
| Konfirmasi & Alert | SweetAlert2 |
| AJAX | jQuery |
| Drag & Drop Reorder | SortableJS |
| Video Upload | Native file upload (server-side, max 100MB) + dukungan embed YouTube |

### Alur Pengguna Utama

1. **Operator TV**: buka landing page publik → pilih kartu TV yang sesuai → masukkan PIN → masuk ke halaman display → slideshow berjalan otomatis dan menerima update real-time via SSE.
2. **Admin**: login ke dashboard → kelola konten (upload gambar/video, input data chart) → assign konten ke kategori → atur durasi & urutan slide.
3. **Superadmin**: semua akses Admin + kelola data TV (buat/edit/hapus TV, regenerate PIN), kelola kategori, kelola user/role.

---

## 2. Struktur Role & Hak Akses

| Fitur | Superadmin | Admin | Public (Landing + Display) |
|---|:---:|:---:|:---:|
| Kelola TV (CRUD, regenerate PIN) | ✅ | ❌ | ❌ |
| Kelola kategori | ✅ | ❌ | ❌ |
| Kelola user | ✅ | ❌ | ❌ |
| Kelola konten (gambar/video/chart) | ✅ | ✅ | ❌ |
| Assign konten ke kategori | ✅ | ✅ | ❌ |
| Atur urutan slide per TV | ✅ | ✅ | ❌ |
| Lihat landing page TV | ✅ | ✅ | ✅ |
| Masuk display (dengan PIN) | ✅ | ✅ | ✅ (jika tahu PIN) |

---

## 3. Skema Database

```sql
-- =============================================
-- USERS & ROLES
-- =============================================
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin') NOT NULL DEFAULT 'admin',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
) ENGINE=InnoDB;

-- =============================================
-- TV / DISPLAY DEVICES
-- =============================================
CREATE TABLE tvs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    location VARCHAR(150) NULL,
    thumbnail VARCHAR(255) NULL COMMENT 'gambar background kartu di landing page',
    pin VARCHAR(6) NOT NULL COMMENT 'PIN 6 digit, regenerable',
    pin_updated_at DATETIME NULL,
    orientation ENUM('landscape') NOT NULL DEFAULT 'landscape',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    last_seen_at DATETIME NULL COMMENT 'update saat TV membuka SSE connection',
    created_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =============================================
-- CATEGORIES
-- =============================================
CREATE TABLE categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    color VARCHAR(7) NULL COMMENT 'hex color untuk badge UI, misal #3B82F6',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
) ENGINE=InnoDB;

-- =============================================
-- TV <-> CATEGORY (many-to-many)
-- Menentukan kategori mana saja yang tampil di TV tsb
-- =============================================
CREATE TABLE tv_categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tv_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_tv_category (tv_id, category_id),
    FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- CONTENTS (gambar, video, chart — polymorphic-lite via type)
-- =============================================
CREATE TABLE contents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id INT UNSIGNED NOT NULL,
    type ENUM('image', 'video', 'chart') NOT NULL,
    title VARCHAR(150) NOT NULL,

    -- untuk type = image / video (upload file)
    file_path VARCHAR(255) NULL,
    file_size INT UNSIGNED NULL COMMENT 'bytes',

    -- untuk type = video via embed YouTube
    video_source ENUM('upload', 'youtube') NULL,
    youtube_url VARCHAR(255) NULL,
    video_duration_seconds INT UNSIGNED NULL COMMENT 'auto-detect saat upload, atau dari YouTube API',

    -- durasi tampil (khusus image & chart, video pakai durasi asli)
    display_duration_seconds SMALLINT UNSIGNED NULL DEFAULT 10,

    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_by INT UNSIGNED NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =============================================
-- CHART DATA (khusus content type = chart)
-- Data manual yang diinput admin, disimpan sebagai JSON
-- agar fleksibel untuk bar/line/pie
-- =============================================
CREATE TABLE content_charts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content_id INT UNSIGNED NOT NULL,
    chart_type ENUM('bar', 'line', 'pie') NOT NULL DEFAULT 'bar',
    chart_labels JSON NOT NULL COMMENT '["Jan","Feb","Mar"]',
    chart_datasets JSON NOT NULL COMMENT '[{"label":"Penjualan","data":[10,20,30],"color":"#3B82F6"}]',
    updated_at DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (content_id) REFERENCES contents(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- TV PLAYLIST ORDER
-- Urutan slide manual (drag-drop) per TV.
-- Karena 1 TV bisa multi kategori, urutan ditentukan di sini,
-- bukan hanya berdasar category_id content.
-- =============================================
CREATE TABLE tv_playlist_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tv_id INT UNSIGNED NOT NULL,
    content_id INT UNSIGNED NOT NULL,
    sort_order INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_tv_content (tv_id, content_id),
    FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE,
    FOREIGN KEY (content_id) REFERENCES contents(id) ON DELETE CASCADE,
    INDEX idx_tv_sort (tv_id, sort_order)
) ENGINE=InnoDB;

-- =============================================
-- TV SESSION LOG (opsional, untuk tracking PIN access & SSE connection)
-- =============================================
CREATE TABLE tv_access_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tv_id INT UNSIGNED NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    accessed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =============================================
-- CONTENT UPDATE EVENTS (trigger untuk SSE)
-- Setiap kali ada perubahan konten yang relevan untuk TV tertentu,
-- insert row di sini. SSE endpoint poll tabel ini tiap 1-2 detik
-- untuk tv_id terkait, lalu push event ke client jika ada row baru.
-- =============================================
CREATE TABLE tv_update_events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tv_id INT UNSIGNED NOT NULL,
    event_type ENUM('playlist_changed', 'content_changed', 'pin_regenerated') NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tv_id) REFERENCES tvs(id) ON DELETE CASCADE,
    INDEX idx_tv_created (tv_id, created_at)
) ENGINE=InnoDB;
```

### Catatan Desain Skema

- **`tv_update_events`** adalah mekanisme SSE tanpa perlu Redis/message queue: PHP SSE endpoint melakukan polling ringan ke tabel ini tiap 1-2 detik (query super cepat dengan index `idx_tv_created`), dan hanya push ke client kalau ada event baru sejak timestamp terakhir. Ini realistis untuk PHP-FPM tanpa perlu proses long-running khusus.
- **`content_charts.chart_datasets`** disimpan sebagai JSON agar 1 struktur tabel bisa menangani bar/line/pie tanpa perlu tabel terpisah per tipe chart.
- **`tv_playlist_items`** dipisah dari `contents.category_id` supaya urutan drag-drop per TV independen — bahkan jika 2 TV pakai kategori yang sama, urutannya bisa beda.
- Soft delete (`deleted_at`) dipakai konsisten agar histori tidak hilang saat admin menghapus TV/kategori/konten yang mungkin masih direferensikan di log.

---

## 4. Arsitektur Real-Time (SSE)

```
[Admin Dashboard]                    [TV Display Page]
       │                                     │
       │ 1. Admin edit playlist/PIN          │ 3. TV buka koneksi SSE
       │    → INSERT ke tv_update_events     │    GET /display/{slug}/events
       ▼                                     ▼
┌─────────────────────┐          ┌──────────────────────────┐
│   MySQL Database     │◄─────────│  SSE Controller (PHP)     │
│  tv_update_events     │  poll    │  loop tiap 1-2 detik,     │
│  (per tv_id)          │  1-2 dtk │  cek event baru           │
└─────────────────────┘          └──────────────────────────┘
                                             │
                                             │ 4. Jika ada event baru,
                                             │    push via SSE stream
                                             ▼
                                   [Browser TV: EventSource]
                                   → re-fetch playlist JSON
                                   → update slideshow tanpa reload
```

**Alur teknis:**
1. Saat admin menyimpan perubahan (playlist reorder, tambah/edit konten, regenerate PIN), controller terkait menyisipkan 1 row ke `tv_update_events` untuk `tv_id` yang terdampak.
2. Halaman display TV membuka koneksi `EventSource` ke endpoint `/display/{slug}/events` begitu PIN diverifikasi.
3. Endpoint SSE (pakai `Content-Type: text/event-stream`, `flush()` + `ob_flush()` loop) polling tabel `tv_update_events` untuk `tv_id` tersebut setiap 1-2 detik.
4. Jika ditemukan event baru, kirim event ke client → JS di sisi TV fetch ulang playlist via endpoint JSON biasa → update slideshow secara mulus tanpa reload halaman.
5. Koneksi SSE auto-reconnect bawaan browser (`EventSource` retry otomatis) jika PHP timeout/worker restart — cocok untuk device yang menyala 24/7.

**Catatan penting untuk implementasi:** PHP-FPM punya `max_execution_time` yang bisa memutus koneksi long-running. Perlu setting `set_time_limit(0)` di controller SSE dan pastikan web server (Nginx/Apache) tidak buffering response (`X-Accel-Buffering: no` untuk Nginx). Ini akan didetailkan di fase development terkait.

---

## 5. Struktur Folder (CI4 HMVC-style)

```
app/
├── Controllers/
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── TvController.php
│   │   ├── CategoryController.php
│   │   ├── ContentController.php
│   │   ├── PlaylistController.php
│   │   └── UserController.php
│   ├── Auth/
│   │   └── AuthController.php
│   └── Display/
│       ├── LandingController.php      // landing page kartu TV
│       ├── DisplayController.php      // halaman slideshow + verifikasi PIN
│       └── SseController.php          // endpoint SSE
├── Models/
│   ├── UserModel.php
│   ├── TvModel.php
│   ├── CategoryModel.php
│   ├── ContentModel.php
│   ├── ContentChartModel.php
│   ├── TvPlaylistItemModel.php
│   └── TvUpdateEventModel.php
├── Filters/
│   ├── AuthFilter.php
│   ├── SuperadminFilter.php
│   └── TvPinSessionFilter.php         // pastikan TV sudah verifikasi PIN sebelum akses display
├── Views/
│   ├── admin/
│   │   ├── dashboard/
│   │   ├── tv/
│   │   ├── category/
│   │   ├── content/
│   │   └── user/
│   ├── auth/
│   ├── display/
│   │   ├── landing.php                // grid kartu TV
│   │   ├── pin_modal.php
│   │   └── slideshow.php
│   └── layouts/
│       ├── admin_layout.php
│       └── display_layout.php
└── Libraries/
    └── SseStreamer.php                 // helper untuk format SSE event + flush loop
```

---

## 6. Fase Pengembangan

### **Fase 1 — Setup & Fondasi**
- Install CI4, konfigurasi `.env`, koneksi database
- Setup Tailwind v4 (build pipeline via npm/Vite atau CDN untuk awal)
- Migration untuk semua tabel di skema di atas
- Seeder: 1 user superadmin default

### **Fase 2 — Autentikasi & Role**
- Login/logout untuk Admin & Superadmin (session-based)
- Filter `AuthFilter` dan `SuperadminFilter`
- Halaman dashboard kosong dengan sidebar navigasi sesuai role

### **Fase 3 — Manajemen User (Superadmin only)**
- CRUD user dengan modal SweetAlert2, tanpa reload (pola yang sama seperti survey app kamu)
- Assign role saat create/edit
- DataTables server-side untuk listing user

### **Fase 4 — Manajemen Kategori**
- CRUD kategori (nama, slug auto-generate, warna badge)
- Modal-based CRUD + SweetAlert2 confirmation

### **Fase 5 — Manajemen TV**
- CRUD data TV (nama, lokasi, thumbnail, slug auto-generate)
- Generate PIN 6-digit otomatis saat create
- Tombol "Regenerate PIN" dengan konfirmasi SweetAlert2 → insert event `pin_regenerated` ke `tv_update_events`
- Assign kategori ke TV (checkbox multi-select kategori per TV)

### **Fase 6 — Manajemen Konten: Gambar**
- Upload gambar dengan validasi tipe & ukuran file
- Set durasi tampil (default 10 detik, bisa diubah)
- Assign ke kategori

### **Fase 7 — Manajemen Konten: Video**
- Toggle sumber: Upload file (max 100MB, validasi format mp4/webm) ATAU embed YouTube (paste URL, auto-extract video ID)
- Auto-detect durasi video upload (via `getID3` atau ffprobe jika tersedia di server; fallback: admin input manual durasi)
- Untuk YouTube, durasi bisa diambil dari YouTube Data API (opsional, atau admin input manual sebagai fallback sederhana)

### **Fase 8 — Manajemen Konten: Chart**
- Form input dinamis: pilih tipe chart (bar/line/pie), input label & value secara manual (tabel input dengan tombol tambah/hapus baris)
- Preview chart langsung di form (live preview pakai Chart.js sebelum simpan)
- Simpan sebagai JSON ke `content_charts`
- Durasi tampil default 10 detik

### **Fase 9 — Playlist & Drag-Drop Reorder**
- Halaman "Kelola Playlist per TV": tampilkan semua konten dari kategori yang di-assign ke TV tsb
- Drag-drop reorder pakai SortableJS, simpan `sort_order` via AJAX tiap kali drop
- Toggle aktif/nonaktif konten per TV tanpa menghapus dari kategori
- Setiap perubahan playlist → insert event `playlist_changed` ke `tv_update_events`

### **Fase 10 — Landing Page Publik & Verifikasi PIN**
- Landing page grid kartu TV (nama, thumbnail background, lokasi) — publik, tanpa login
- Klik kartu → modal input PIN 6 digit
- Verifikasi PIN via AJAX → jika benar, redirect ke `/display/{slug}` dengan session token sementara (agar URL display tidak bisa diakses ulang tanpa PIN jika session habis/browser lain)

### **Fase 11 — Halaman Display & SSE Integration**
- Halaman slideshow fullscreen, orientasi landscape, auto-rotate sesuai `display_duration_seconds` per konten (gambar/chart) atau durasi asli video
- Render chart pakai Chart.js dari data JSON
- Koneksi `EventSource` ke endpoint SSE, auto re-fetch playlist saat menerima event
- Endpoint SSE (`SseController`) dengan polling `tv_update_events`, header anti-buffering
- Update `last_seen_at` di tabel `tvs` saat koneksi SSE aktif (untuk monitoring TV mana yang online)

### **Fase 12 — Polish, Monitoring & Testing**
- Dashboard superadmin: status TV (online/offline berdasar `last_seen_at`), jumlah konten per kategori
- Responsive check untuk halaman admin (mobile-friendly untuk operator yang kelola dari HP)
- Error handling: fallback jika konten kosong (tampilkan pesan "Belum ada konten" di TV, bukan layar putih)
- Testing menyeluruh: multi-TV dengan kategori berbeda, drag-drop reorder, regenerate PIN saat TV sedang aktif menonton (harus otomatis logout/minta PIN ulang)

---

## 7. Pertimbangan Teknis Tambahan

- **Preload konten**: agar transisi slide mulus (terutama video), pertimbangkan preload aset berikutnya di background sebelum slide saat ini selesai.
- **Storage video**: dengan limit 100MB per video dan banyak TV, disarankan folder upload terpisah dari `public/` langsung diakses, atau gunakan CI4 `Response::download()` dengan proper caching headers agar tidak membebani server tiap request.
- **PIN session expiry**: pertimbangkan durasi session PIN (misal 12 jam) agar TV yang menyala terus tidak perlu re-verifikasi tiap hari, tapi tetap aman jika PIN di-regenerate oleh admin.
- **Fallback jika SSE gagal**: tambahkan polling JS biasa sebagai fallback (tiap 60 detik) jika `EventSource` gagal connect (misal karena proxy/firewall di jaringan TV), supaya display tidak "macet" total.

---

Dokumen ini menjadi acuan utama untuk pengembangan bertahap. Setiap fase bisa dikerjakan sebagai unit kerja terpisah dengan Claude Code atau sesi chat berikutnya, dengan skema SQL di atas sebagai kontrak data yang konsisten di seluruh fase.
