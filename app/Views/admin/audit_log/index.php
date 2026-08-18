<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>
<div class="space-y-6 animate-page-enter">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white/70 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-blue-600 via-indigo-600 to-sky-400 flex items-center justify-center text-white shadow-lg shadow-blue-500/20 shrink-0">
                <i class="fa-solid fa-clock-rotate-left text-lg"></i>
            </div>
            <div>
                <div class="flex items-center space-x-2.5">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white tracking-tight">Audit Logs & Jejak Sistem</h2>
                    <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                        <?= $isSuperadmin ? 'Superadmin Scope' : 'Personal Scope' ?>
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    <?= $isSuperadmin ? 'Rekam jejak seluruh aktivitas pengguna, perubahan data master, dan autentikasi.' : 'Riwayat jejak aktivitas dan manipulasi data yang Anda lakukan pada sistem.' ?>
                </p>
            </div>
        </div>

        <div class="flex items-center flex-wrap gap-2.5 shrink-0">
            <!-- Refresh Button -->
            <button type="button" id="btnRefreshLogs" class="p-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 border border-slate-200 dark:border-slate-700 ux-hover" title="Muat Ulang Data">
                <i class="fa-solid fa-arrows-rotate text-xs" id="iconRefresh"></i>
                <span class="hidden sm:inline">Refresh</span>
            </button>

            <!-- Export CSV Button -->
            <button type="button" id="btnExportCsv" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-emerald-600/20 transition-all flex items-center space-x-2 ux-hover">
                <i class="fa-solid fa-file-excel text-xs"></i>
                <span>Ekspor CSV</span>
            </button>

            <!-- Purge Logs Button (Superadmin Only) -->
            <?php if ($isSuperadmin): ?>
            <button type="button" id="btnPurge" class="px-4 py-2.5 bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 ux-hover">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Bersihkan Log</span>
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Stats Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total Aktivitas -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 shadow-xs hover:border-blue-500/30 transition-all ux-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Total Aktivitas</span>
                <div class="w-9 h-9 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-database text-sm"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white" id="statTotal">—</h3>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Seluruh entri log tersimpan</p>
        </div>

        <!-- Card 2: Aktivitas Hari Ini -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 shadow-xs hover:border-emerald-500/30 transition-all ux-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <i class="fa-solid fa-calendar-day text-sm"></i>
                </div>
            </div>
            <div class="flex items-baseline space-x-2">
                <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white" id="statToday">—</h3>
                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-pulse"></span>
                    24 Jam
                </span>
            </div>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Aktivitas tercatat hari ini</p>
        </div>

        <!-- Card 3: Filter Terpasang -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 shadow-xs hover:border-sky-500/30 transition-all ux-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Filter Aktif</span>
                <div class="w-9 h-9 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400">
                    <i class="fa-solid fa-filter text-sm"></i>
                </div>
            </div>
            <div class="text-sm font-bold text-slate-800 dark:text-slate-200 truncate" id="statFiltered">Semua Entri</div>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Kriteria pencarian aktif</p>
        </div>

        <!-- Card 4: Data Ditampilkan -->
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 shadow-xs hover:border-indigo-500/30 transition-all ux-card">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Hasil Ditampilkan</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                    <i class="fa-solid fa-list-check text-sm"></i>
                </div>
            </div>
            <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white" id="statShowing">—</h3>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Baris yang memenuhi filter</p>
        </div>
    </div>

    <!-- Filter Control Panel -->
    <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 shadow-xs space-y-4">
        <!-- Quick Module Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs border-b border-slate-100 dark:border-slate-800/60">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1 shrink-0">Pintasan:</span>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-semibold transition-all bg-blue-600 text-white shadow-xs" data-module="">
                <i class="fa-solid fa-table-cells-large mr-1.5"></i>Semua Modul
            </button>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700" data-module="content">
                <i class="fa-solid fa-photo-film mr-1.5 text-sky-500"></i>Konten
            </button>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700" data-module="category">
                <i class="fa-solid fa-layer-group mr-1.5 text-violet-500"></i>Kategori
            </button>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700" data-module="tv">
                <i class="fa-solid fa-tv mr-1.5 text-blue-500"></i>Display TV
            </button>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700" data-module="playlist">
                <i class="fa-solid fa-list-ol mr-1.5 text-indigo-500"></i>Playlist
            </button>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700" data-module="user">
                <i class="fa-solid fa-users-gear mr-1.5 text-amber-500"></i>User
            </button>
            <button type="button" class="quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700" data-module="auth">
                <i class="fa-solid fa-shield-halved mr-1.5 text-teal-500"></i>Autentikasi
            </button>
        </div>

        <!-- Main Filter Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3.5">
            <!-- Search Keyword Input -->
            <div class="lg:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" id="filterSearch" placeholder="Cari deskripsi, entitas, IP, atau user..."
                    class="w-full pl-9 pr-9 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                <button type="button" id="btnClearSearch" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hidden">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <!-- Module Dropdown -->
            <div class="lg:col-span-2">
                <select id="filterModule" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                    <option value="">Semua Modul</option>
                    <option value="content">Konten</option>
                    <option value="category">Kategori</option>
                    <option value="tv">Display TV</option>
                    <option value="playlist">Playlist</option>
                    <option value="user">User</option>
                    <option value="auth">Autentikasi</option>
                </select>
            </div>

            <!-- Action Dropdown -->
            <div class="lg:col-span-2">
                <select id="filterAction" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                    <option value="">Semua Tipe Aksi</option>
                    <option value="create">Create (Tambah)</option>
                    <option value="update">Update (Ubah)</option>
                    <option value="delete">Delete (Hapus)</option>
                    <option value="toggle">Toggle (Status)</option>
                    <option value="reorder">Reorder (Urutan)</option>
                    <option value="regenerate">Regenerate (PIN)</option>
                    <option value="login">Login</option>
                    <option value="logout">Logout</option>
                </select>
            </div>

            <!-- User Dropdown (Superadmin only) -->
            <?php if ($isSuperadmin): ?>
            <div class="lg:col-span-2">
                <select id="filterUser" class="w-full px-3 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                    <option value="">Semua Pengguna</option>
                    <?php foreach ($users as $u): ?>
                    <option value="<?= $u['user_id'] ?>"><?= esc($u['user_name']) ?> (<?= $u['user_role'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php else: ?>
            <div class="lg:col-span-2">
                <input type="text" disabled value="Akun: <?= esc(session()->get('name')) ?>" class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-500">
            </div>
            <?php endif; ?>

            <!-- Date Range Controls -->
            <div class="lg:col-span-2 flex items-center gap-1.5">
                <button type="button" id="btnResetFilter" class="w-full px-3 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold transition-all flex items-center justify-center space-x-1.5" title="Reset Seluruh Filter">
                    <i class="fa-solid fa-rotate-left text-xs"></i>
                    <span>Reset Filter</span>
                </button>
            </div>
        </div>

        <!-- Date Range Filter Row with Presets -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 text-xs">
            <div class="flex items-center flex-wrap gap-2">
                <span class="text-slate-400 font-medium">Rentang Tanggal:</span>
                <input type="date" id="filterDateFrom" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:border-blue-500">
                <span class="text-slate-400">s/d</span>
                <input type="date" id="filterDateTo" class="px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex items-center gap-1.5">
                <button type="button" class="date-preset-btn px-2.5 py-1 text-[11px] font-medium rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700" data-days="0">Hari Ini</button>
                <button type="button" class="date-preset-btn px-2.5 py-1 text-[11px] font-medium rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700" data-days="7">7 Hari</button>
                <button type="button" class="date-preset-btn px-2.5 py-1 text-[11px] font-medium rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700" data-days="30">30 Hari</button>
            </div>
        </div>
    </div>

    <!-- Table Card Container -->
    <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <!-- Table Header Bar -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/40">
            <div class="flex items-center space-x-3">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Daftar Rekam Aktivitas</h3>
                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20" id="badgeTotalFound">0 Data</span>
            </div>

            <div class="flex items-center space-x-2">
                <div id="loadingIndicator" class="hidden flex items-center space-x-2 text-xs text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-circle-notch fa-spin text-xs"></i>
                    <span>Memperbarui data...</span>
                </div>
            </div>
        </div>

        <!-- Scrollable Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="auditTable">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider bg-slate-50/30 dark:bg-slate-950/20">
                        <th class="py-3.5 px-5 whitespace-nowrap">Waktu</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Pengguna</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Modul</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">Aksi</th>
                        <th class="py-3.5 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3.5 px-4 whitespace-nowrap">IP & Jaringan</th>
                        <th class="py-3.5 px-5 text-right whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200/70 dark:divide-slate-800/50 text-xs" id="auditTableBody">
                    <!-- Dynamic Log Rows rendered via JS -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden py-16 text-center px-4">
            <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mx-auto mb-3 text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-scroll text-2xl"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-300">Tidak ada log aktivitas</h4>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Tidak ditemukan riwayat log yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
            <button type="button" id="btnEmptyReset" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold transition-all">
                Reset Semua Filter
            </button>
        </div>

        <!-- Table Footer / Pagination -->
        <div id="paginationBar" class="hidden px-6 py-4 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/40 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="text-slate-500 dark:text-slate-400" id="paginationInfo">
                Menampilkan data...
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" id="btnPrevPage" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all flex items-center space-x-1">
                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    <span>Sebelumnya</span>
                </button>
                <div class="px-3 py-1.5 font-semibold text-slate-700 dark:text-slate-300" id="pageIndicator">
                    Halaman 1
                </div>
                <button type="button" id="btnNextPage" class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all flex items-center space-x-1">
                    <span>Berikutnya</span>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Modal Diff Viewer -->
<div id="modalDiff" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/80 backdrop-blur-md hidden p-4 transition-all duration-200">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-2xl max-h-[90vh] shadow-2xl flex flex-col overflow-hidden animate-scale-up">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <i class="fa-solid fa-code-compare text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Detail Perbandingan Data (Diff)</h3>
                    <p class="text-[11px] text-slate-400" id="diffSubtitle">Snapshot nilai sebelum dan sesudah perubahan</p>
                </div>
            </div>
            <button type="button" id="btnCloseDiff" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Meta Bar -->
        <div class="px-6 py-3 bg-slate-50/80 dark:bg-slate-950/40 border-b border-slate-200 dark:border-slate-800/80 text-xs shrink-0" id="diffMeta">
            <!-- Dynamic info injected by JS -->
        </div>

        <!-- Diff Content Body -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="diffBody">
            <!-- Injected by JS -->
        </div>

        <!-- Footer -->
        <div class="px-6 py-3.5 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 flex justify-end shrink-0">
            <button type="button" id="btnCloseDiffFooter" class="px-4 py-2 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all">
                Tutup Jendela
            </button>
        </div>
    </div>
</div>

<!-- Modal Purge Logs (Superadmin Only) -->
<?php if ($isSuperadmin): ?>
<div id="modalPurge" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-950/80 backdrop-blur-md hidden p-4 transition-all duration-200">
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden animate-scale-up">
        <div class="p-6">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-500 shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Pembersihan Audit Logs</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Hapus log lama untuk menghemat penyimpanan</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Pilih Periode Pembersihan:</label>
                    <div class="grid grid-cols-2 gap-2" id="purgeOptionsGrid">
                        <button type="button" class="purge-opt-btn p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-950/30 transition-all text-left" data-days="30">
                            <div class="font-bold text-slate-800 dark:text-white">Lebih dari 30 Hari</div>
                            <div class="text-[10px] text-slate-400 font-normal">Rekomendasi berkala</div>
                        </button>
                        <button type="button" class="purge-opt-btn p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-950/30 transition-all text-left" data-days="90">
                            <div class="font-bold text-slate-800 dark:text-white">Lebih dari 90 Hari</div>
                            <div class="text-[10px] text-slate-400 font-normal">Arsip 3 bulan</div>
                        </button>
                        <button type="button" class="purge-opt-btn p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-950/30 transition-all text-left" data-days="7">
                            <div class="font-bold text-slate-800 dark:text-white">Lebih dari 7 Hari</div>
                            <div class="text-[10px] text-slate-400 font-normal">1 minggu terakhir</div>
                        </button>
                        <button type="button" class="purge-opt-btn p-2.5 rounded-xl border border-rose-500/30 bg-rose-500/5 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-500/10 transition-all text-left" data-days="0">
                            <div class="font-bold text-rose-600 dark:text-rose-400">Reset Semua Log</div>
                            <div class="text-[10px] text-rose-400/80 font-normal">Kosongkan riwayat</div>
                        </button>
                    </div>
                    <input type="hidden" id="selectedPurgeDays" value="30">
                </div>

                <div class="p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl text-amber-600 dark:text-amber-400 text-xs flex items-start space-x-2.5">
                    <i class="fa-solid fa-circle-info text-sm shrink-0 mt-0.5"></i>
                    <p class="leading-relaxed">
                        Data audit log yang dihapus <strong>tidak dapat dikembalikan</strong>. Aksi pembersihan ini sendiri akan otomatis dicatat sebagai jejak audit baru.
                    </p>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex items-center justify-end space-x-2.5">
            <button type="button" id="btnCancelPurge" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-semibold transition-all">
                Batal
            </button>
            <button type="button" id="btnConfirmPurge" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-rose-600/20 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span id="btnConfirmPurgeText">Hapus Log Terpilih</span>
            </button>
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
    const BASE_URL      = '<?= base_url() ?>';
    const IS_SUPERADMIN = <?= $isSuperadmin ? 'true' : 'false' ?>;
    const LIMIT         = 50;

    let currentPage   = 1;
    let totalRecords  = 0;
    let activeFilters = {};
    let searchTimeout = null;

    // Badges Dictionary
    const ACTION_CONFIG = {
        create:     { label: 'Create',     icon: 'fa-plus',            cls: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20' },
        update:     { label: 'Update',     icon: 'fa-pen-to-square',   cls: 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20' },
        delete:     { label: 'Delete',     icon: 'fa-trash-can',       cls: 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20' },
        toggle:     { label: 'Toggle',     icon: 'fa-toggle-on',       cls: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20' },
        reorder:    { label: 'Reorder',    icon: 'fa-arrow-down-short-wide', cls: 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20' },
        regenerate: { label: 'Regen PIN',  icon: 'fa-key',             cls: 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20' },
        login:      { label: 'Login',      icon: 'fa-right-to-bracket',cls: 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20' },
        logout:     { label: 'Logout',     icon: 'fa-right-from-bracket', cls: 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/20' },
    };

    const MODULE_CONFIG = {
        content:  { label: 'Konten',      icon: 'fa-photo-film',   cls: 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20' },
        category: { label: 'Kategori',    icon: 'fa-layer-group',  cls: 'bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-500/20' },
        tv:       { label: 'Display TV',  icon: 'fa-tv',           cls: 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20' },
        playlist: { label: 'Playlist',    icon: 'fa-list-ol',      cls: 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border-indigo-500/20' },
        user:     { label: 'User',        icon: 'fa-users-gear',   cls: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20' },
        auth:     { label: 'Autentikasi', icon: 'fa-shield-halved',cls: 'bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-500/20' },
    };

    function esc(s) {
        if (!s) return '—';
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function formatDate(dt) {
        if (!dt) return '—';
        const d = new Date(dt.replace(' ', 'T'));
        return d.toLocaleString('id-ID', {
            day: '2-digit', month: 'short', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
    }

    function renderActionBadge(action) {
        const conf = ACTION_CONFIG[action] || { label: action, icon: 'fa-circle', cls: 'bg-slate-100 text-slate-600 border-slate-200' };
        return `<span class="inline-flex items-center space-x-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg border ${conf.cls}">
            <i class="fa-solid ${conf.icon} text-[9px]"></i>
            <span>${conf.label}</span>
        </span>`;
    }

    function renderModuleBadge(mod) {
        const conf = MODULE_CONFIG[mod] || { label: mod, icon: 'fa-cube', cls: 'bg-slate-100 text-slate-600 border-slate-200' };
        return `<span class="inline-flex items-center space-x-1.5 px-2.5 py-1 text-[10px] font-semibold rounded-lg border ${conf.cls}">
            <i class="fa-solid ${conf.icon} text-[9px]"></i>
            <span>${conf.label}</span>
        </span>`;
    }

    function buildQuery(extras = {}) {
        const params = new URLSearchParams({
            ...activeFilters,
            limit: LIMIT,
            offset: (currentPage - 1) * LIMIT,
            ...extras
        });
        return params.toString();
    }

    async function fetchLogs() {
        const loading = document.getElementById('loadingIndicator');
        const iconRef = document.getElementById('iconRefresh');
        const tbody   = document.getElementById('auditTableBody');
        const empty   = document.getElementById('emptyState');
        const pagBar  = document.getElementById('paginationBar');

        loading.classList.remove('hidden');
        if (iconRef) iconRef.classList.add('fa-spin');

        try {
            const res  = await fetch(`${BASE_URL}admin/audit-logs/list?${buildQuery()}`);
            const json = await res.json();

            loading.classList.add('hidden');
            if (iconRef) iconRef.classList.remove('fa-spin');

            if (json.status !== 'success') throw new Error(json.message);

            totalRecords = json.total;

            // Stats Update
            document.getElementById('statTotal').textContent   = json.stats.total.toLocaleString('id-ID');
            document.getElementById('statToday').textContent   = json.stats.today.toLocaleString('id-ID');
            document.getElementById('statShowing').textContent = `${json.data.length} / ${totalRecords}`;
            document.getElementById('badgeTotalFound').textContent = `${totalRecords} Data`;

            // Active Filter summary
            const activeTags = [];
            if (activeFilters.search)    activeTags.push(`"${activeFilters.search}"`);
            if (activeFilters.module)    activeTags.push(MODULE_CONFIG[activeFilters.module]?.label || activeFilters.module);
            if (activeFilters.action)    activeTags.push(ACTION_CONFIG[activeFilters.action]?.label || activeFilters.action);
            if (activeFilters.date_from) activeTags.push(`Dari ${activeFilters.date_from}`);
            if (activeFilters.date_to)   activeTags.push(`s/d ${activeFilters.date_to}`);
            document.getElementById('statFiltered').textContent = activeTags.length ? activeTags.join(', ') : 'Semua Entri';

            tbody.innerHTML = '';

            if (json.data.length === 0) {
                empty.classList.remove('hidden');
                pagBar.classList.add('hidden');
                return;
            }

            empty.classList.add('hidden');

            // Render Rows
            json.data.forEach(log => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors group';

                const hasDiff = Boolean(log.old_values || log.new_values);
                const roleBadge = log.user_role === 'superadmin' 
                    ? '<span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">superadmin</span>'
                    : '<span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">admin</span>';

                const detailBtn = hasDiff
                    ? `<button type="button" class="btnViewDiff px-3 py-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/20 rounded-xl transition-all flex items-center space-x-1.5 ml-auto ux-hover" data-id="${log.id}">
                            <i class="fa-solid fa-code-compare text-[10px]"></i>
                            <span>Diff</span>
                       </button>`
                    : `<span class="text-slate-300 dark:text-slate-700 text-xs">—</span>`;

                tr.innerHTML = `
                    <td class="py-3.5 px-5 whitespace-nowrap">
                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs">${formatDate(log.created_at)}</div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white text-[10px] font-bold shrink-0 shadow-xs">
                                ${esc(log.user_name).substring(0, 2).toUpperCase()}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 dark:text-slate-200 text-xs truncate max-w-[130px]">${esc(log.user_name)}</div>
                                <div class="mt-0.5">${roleBadge}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">${renderModuleBadge(log.module)}</td>
                    <td class="py-3.5 px-4 whitespace-nowrap">${renderActionBadge(log.action)}</td>
                    <td class="py-3.5 px-4">
                        <div class="font-medium text-slate-800 dark:text-slate-200 text-xs max-w-sm leading-snug">${esc(log.description)}</div>
                        ${log.entity_name ? `<div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 flex items-center space-x-1"><i class="fa-solid fa-tag text-[9px]"></i><span>${esc(log.entity_name)}</span></div>` : ''}
                    </td>
                    <td class="py-3.5 px-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-0.5 rounded font-mono text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                            ${esc(log.ip_address)}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-right whitespace-nowrap">${detailBtn}</td>
                `;

                tbody.appendChild(tr);
            });

            // Pagination update
            if (totalRecords > 0) {
                const totalPages = Math.ceil(totalRecords / LIMIT);
                const fromNum    = ((currentPage - 1) * LIMIT) + 1;
                const toNum      = Math.min(currentPage * LIMIT, totalRecords);

                document.getElementById('paginationInfo').textContent = `Menampilkan ${fromNum} – ${toNum} dari total ${totalRecords} data`;
                document.getElementById('pageIndicator').textContent   = `Halaman ${currentPage} / ${totalPages}`;
                document.getElementById('btnPrevPage').disabled        = currentPage <= 1;
                document.getElementById('btnNextPage').disabled        = currentPage >= totalPages;

                pagBar.classList.remove('hidden');
            } else {
                pagBar.classList.add('hidden');
            }

        } catch (err) {
            loading.classList.add('hidden');
            if (iconRef) iconRef.classList.remove('fa-spin');
            console.error('[AuditLog]', err);
            showToast('error', 'Gagal memuat log aktivitas.');
        }
    }

    function collectFilters() {
        const f = {};
        const q = document.getElementById('filterSearch').value.trim();
        if (q) f.search = q;
        const mod = document.getElementById('filterModule').value;
        if (mod) f.module = mod;
        const act = document.getElementById('filterAction').value;
        if (act) f.action = act;
        const dFrom = document.getElementById('filterDateFrom').value;
        if (dFrom) f.date_from = dFrom;
        const dTo = document.getElementById('filterDateTo').value;
        if (dTo) f.date_to = dTo;
        if (IS_SUPERADMIN) {
            const usr = document.getElementById('filterUser')?.value;
            if (usr) f.user_id = usr;
        }
        return f;
    }

    function applyFilterChange() {
        activeFilters = collectFilters();
        currentPage = 1;
        fetchLogs();
    }

    // Quick Filter Module Buttons
    document.querySelectorAll('.quick-mod-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const mod = this.dataset.module;
            document.getElementById('filterModule').value = mod;

            document.querySelectorAll('.quick-mod-btn').forEach(b => {
                b.className = 'quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
            });
            this.className = 'quick-mod-btn px-3 py-1.5 rounded-xl font-semibold transition-all bg-blue-600 text-white shadow-xs';

            applyFilterChange();
        });
    });

    // Date Preset Buttons
    document.querySelectorAll('.date-preset-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const days = parseInt(this.dataset.days);
            const now  = new Date();
            const toStr = now.toISOString().split('T')[0];

            if (days === 0) {
                document.getElementById('filterDateFrom').value = toStr;
                document.getElementById('filterDateTo').value   = toStr;
            } else {
                const past = new Date();
                past.setDate(now.getDate() - days);
                document.getElementById('filterDateFrom').value = past.toISOString().split('T')[0];
                document.getElementById('filterDateTo').value   = toStr;
            }

            applyFilterChange();
        });
    });

    // Search Input with Debounce & Clear Button
    const searchInput = document.getElementById('filterSearch');
    const clearBtn    = document.getElementById('btnClearSearch');

    searchInput.addEventListener('input', function() {
        if (this.value.trim().length > 0) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilterChange, 400);
    });

    clearBtn.addEventListener('click', function() {
        searchInput.value = '';
        this.classList.add('hidden');
        applyFilterChange();
    });

    // Dropdown filters
    ['filterModule', 'filterAction', 'filterDateFrom', 'filterDateTo'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('change', applyFilterChange);
    });

    if (IS_SUPERADMIN && document.getElementById('filterUser')) {
        document.getElementById('filterUser').addEventListener('change', applyFilterChange);
    }

    // Reset Filter Button
    function resetAllFilters() {
        searchInput.value = '';
        clearBtn.classList.add('hidden');
        document.getElementById('filterModule').value   = '';
        document.getElementById('filterAction').value   = '';
        document.getElementById('filterDateFrom').value = '';
        document.getElementById('filterDateTo').value   = '';

        if (IS_SUPERADMIN && document.getElementById('filterUser')) {
            document.getElementById('filterUser').value = '';
        }

        // Reset quick pills
        document.querySelectorAll('.quick-mod-btn').forEach((b, i) => {
            b.className = i === 0 
                ? 'quick-mod-btn px-3 py-1.5 rounded-xl font-semibold transition-all bg-blue-600 text-white shadow-xs'
                : 'quick-mod-btn px-3 py-1.5 rounded-xl font-medium transition-all bg-slate-100 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700';
        });

        applyFilterChange();
    }

    document.getElementById('btnResetFilter').addEventListener('click', resetAllFilters);
    document.getElementById('btnEmptyReset').addEventListener('click', resetAllFilters);
    document.getElementById('btnRefreshLogs').addEventListener('click', fetchLogs);

    // Pagination Click Handlers
    document.getElementById('btnPrevPage').addEventListener('click', () => {
        if (currentPage > 1) { currentPage--; fetchLogs(); }
    });
    document.getElementById('btnNextPage').addEventListener('click', () => {
        const totalPages = Math.ceil(totalRecords / LIMIT);
        if (currentPage < totalPages) { currentPage++; fetchLogs(); }
    });

    // ==========================================
    // DIFF MODAL VIEWER
    // ==========================================
    document.getElementById('auditTableBody').addEventListener('click', async function(e) {
        const btn = e.target.closest('.btnViewDiff');
        if (!btn) return;

        const id = btn.dataset.id;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs"></i>';

        try {
            const res  = await fetch(`${BASE_URL}admin/audit-logs/get/${id}`);
            const json = await res.json();
            btn.innerHTML = '<i class="fa-solid fa-code-compare text-[10px]"></i><span>Diff</span>';

            if (json.status !== 'success') throw new Error(json.message);

            openDiffModal(json.data);
        } catch (err) {
            btn.innerHTML = '<i class="fa-solid fa-code-compare text-[10px]"></i><span>Diff</span>';
            showToast('error', 'Gagal memuat snapshot diff.');
        }
    });

    function openDiffModal(log) {
        document.getElementById('diffSubtitle').textContent = `${log.module.toUpperCase()} / ${log.action.toUpperCase()} — ${formatDate(log.created_at)}`;

        // Meta Bar
        document.getElementById('diffMeta').innerHTML = `
            <div class="flex flex-wrap items-center gap-3 text-slate-600 dark:text-slate-300">
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-user text-slate-400 text-xs"></i>
                    <span class="font-bold text-slate-800 dark:text-white">${esc(log.user_name)}</span>
                    <span class="text-[10px] text-slate-400">(${log.user_role})</span>
                </div>
                <div class="flex items-center space-x-1.5">
                    <i class="fa-solid fa-network-wired text-slate-400 text-xs"></i>
                    <span class="font-mono text-[11px]">${esc(log.ip_address)}</span>
                </div>
                ${log.entity_name ? `<div class="flex items-center space-x-1.5"><i class="fa-solid fa-cube text-slate-400 text-xs"></i><span>${esc(log.entity_name)}</span></div>` : ''}
            </div>
            <div class="mt-2 text-slate-700 dark:text-slate-300 font-medium">${esc(log.description)}</div>
        `;

        let html = '';
        const diffKeys = log.diff ? Object.keys(log.diff) : [];

        if (diffKeys.length > 0) {
            html += `<div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2 flex items-center justify-between">
                <span>Field yang Dimodifikasi (${diffKeys.length})</span>
                <span class="text-[10px] text-slate-400 font-normal">Merah = Nilai Lama, Hijau = Nilai Baru</span>
            </div>`;

            diffKeys.forEach(k => {
                const ov = log.diff[k].old;
                const nv = log.diff[k].new;
                const oldText = ov === null || ov === undefined ? '<em class="text-slate-400">null</em>' : esc(typeof ov === 'object' ? JSON.stringify(ov, null, 2) : String(ov));
                const newText = nv === null || nv === undefined ? '<em class="text-slate-400">null</em>' : esc(typeof nv === 'object' ? JSON.stringify(nv, null, 2) : String(nv));

                html += `
                    <div class="rounded-xl border border-slate-200 dark:border-slate-700/80 overflow-hidden text-xs">
                        <div class="px-3.5 py-2 bg-slate-100/70 dark:bg-slate-800/60 border-b border-slate-200 dark:border-slate-700/80 flex items-center justify-between font-mono font-bold text-slate-700 dark:text-slate-300">
                            <span>${esc(k)}</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-slate-200 dark:divide-slate-700/80 font-mono text-xs">
                            <div class="p-3 bg-rose-500/5 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400">
                                <div class="text-[9px] font-bold text-rose-500 uppercase tracking-wider mb-1">Sebelum:</div>
                                <div class="break-all whitespace-pre-wrap">${oldText}</div>
                            </div>
                            <div class="p-3 bg-emerald-500/5 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400">
                                <div class="text-[9px] font-bold text-emerald-500 uppercase tracking-wider mb-1">Sesudah:</div>
                                <div class="break-all whitespace-pre-wrap">${newText}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else if (log.new_values && Object.keys(log.new_values).length > 0) {
            html += `<div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Snapshot Data Baru</div>`;
            Object.entries(log.new_values).forEach(([k, v]) => {
                const valStr = typeof v === 'object' ? JSON.stringify(v, null, 2) : String(v);
                html += `
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between py-2 border-b border-slate-100 dark:border-slate-800/80 last:border-0 text-xs">
                        <span class="font-mono font-semibold text-slate-500 dark:text-slate-400 sm:w-1/3 shrink-0">${esc(k)}</span>
                        <span class="font-mono text-emerald-600 dark:text-emerald-400 break-all sm:w-2/3 whitespace-pre-wrap">${esc(valStr)}</span>
                    </div>
                `;
            });
        } else if (log.old_values && Object.keys(log.old_values).length > 0) {
            html += `<div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Snapshot Data yang Dihapus</div>`;
            Object.entries(log.old_values).forEach(([k, v]) => {
                const valStr = typeof v === 'object' ? JSON.stringify(v, null, 2) : String(v);
                html += `
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between py-2 border-b border-slate-100 dark:border-slate-800/80 last:border-0 text-xs">
                        <span class="font-mono font-semibold text-slate-500 dark:text-slate-400 sm:w-1/3 shrink-0">${esc(k)}</span>
                        <span class="font-mono text-rose-600 dark:text-rose-400 break-all sm:w-2/3 whitespace-pre-wrap">${esc(valStr)}</span>
                    </div>
                `;
            });
        } else {
            html = '<div class="py-8 text-center text-slate-400 text-xs">Tidak ada data perubahan tersimpan pada log ini.</div>';
        }

        document.getElementById('diffBody').innerHTML = html;
        document.getElementById('modalDiff').classList.remove('hidden');
    }

    function closeDiffModal() {
        document.getElementById('modalDiff').classList.add('hidden');
    }

    document.getElementById('btnCloseDiff').addEventListener('click', closeDiffModal);
    document.getElementById('btnCloseDiffFooter').addEventListener('click', closeDiffModal);
    document.getElementById('modalDiff').addEventListener('click', function(e) {
        if (e.target === this) closeDiffModal();
    });

    // ==========================================
    // EXPORT CSV HANDLER
    // ==========================================
    document.getElementById('btnExportCsv').addEventListener('click', function() {
        showToast('info', 'Memulai pengunduhan file CSV...');
        const exportQuery = buildQuery({ limit: 5000, offset: 0 });
        window.location.href = `${BASE_URL}admin/audit-logs/export-csv?${exportQuery}`;
    });

    // ==========================================
    // PURGE MODAL & ACTION (SUPERADMIN)
    // ==========================================
    <?php if ($isSuperadmin): ?>
    let selectedPurgeDays = 30;

    const modalPurge = document.getElementById('modalPurge');

    document.getElementById('btnPurge').addEventListener('click', () => {
        modalPurge.classList.remove('hidden');
    });

    document.querySelectorAll('.purge-opt-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            selectedPurgeDays = parseInt(this.dataset.days);
            document.getElementById('selectedPurgeDays').value = selectedPurgeDays;

            document.querySelectorAll('.purge-opt-btn').forEach(b => {
                b.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-950/40');
            });
            this.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50/50', 'dark:bg-blue-950/40');

            const confirmBtnText = document.getElementById('btnConfirmPurgeText');
            if (selectedPurgeDays === 0) {
                confirmBtnText.textContent = 'Kosongkan Seluruh Log';
            } else {
                confirmBtnText.textContent = `Hapus Log > ${selectedPurgeDays} Hari`;
            }
        });
    });

    // Select default 30 days
    document.querySelector('.purge-opt-btn[data-days="30"]')?.click();

    function closePurgeModal() {
        modalPurge.classList.add('hidden');
    }

    document.getElementById('btnCancelPurge').addEventListener('click', closePurgeModal);
    modalPurge.addEventListener('click', function(e) {
        if (e.target === this) closePurgeModal();
    });

    document.getElementById('btnConfirmPurge').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin mr-1.5"></i><span>Memproses...</span>';

        try {
            const fd = new FormData();
            fd.append('days', selectedPurgeDays);

            const res  = await fetch(`${BASE_URL}admin/audit-logs/purge`, {
                method: 'POST',
                body: fd
            });
            const json = await res.json();

            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash-can text-xs"></i><span id="btnConfirmPurgeText">Hapus Log Terpilih</span>';

            if (json.status === 'success') {
                closePurgeModal();
                showToast('success', json.message);
                currentPage = 1;
                fetchLogs();
            } else {
                showToast('error', json.message || 'Gagal membersihkan log.');
            }
        } catch (err) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash-can text-xs"></i><span id="btnConfirmPurgeText">Hapus Log Terpilih</span>';
            showToast('error', 'Terjadi kesalahan saat membersihkan log.');
        }
    });
    <?php endif; ?>

    // Initial Load
    fetchLogs();

})();
</script>
<?= $this->endSection() ?>