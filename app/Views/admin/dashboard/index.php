<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Flash Message Notifications -->
<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showToast('success', '<?= esc(session()->getFlashdata('success')) ?>');
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showToast('error', '<?= esc(session()->getFlashdata('error')) ?>');
        });
    </script>
<?php endif; ?>

<!-- 1. Header Section: User Greeting & Quick Actions -->
<div class="mb-6 p-5 sm:p-6 rounded-3xl bg-linear-to-r from-blue-600 via-indigo-600 to-sky-700 dark:from-blue-950/80 dark:via-slate-900/90 dark:to-slate-900 border border-blue-500/20 text-white relative overflow-hidden shadow-xl">
    <div class="absolute right-0 top-0 translate-x-8 -translate-y-8 w-72 h-72 bg-blue-400/10 dark:bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute left-1/3 bottom-0 w-48 h-48 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- User Info & Avatar -->
        <div class="flex items-start sm:items-center space-x-3.5">
            <div class="relative shrink-0">
                <div class="w-13 h-13 rounded-2xl bg-white/15 dark:bg-blue-600/30 backdrop-blur-md border border-white/30 dark:border-blue-400/30 flex items-center justify-center text-white font-bold text-lg shadow-md">
                    <?= strtoupper(substr(session()->get('name') ?? 'Admin', 0, 2)) ?>
                </div>
                <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-900" title="Online"></span>
            </div>
            <div>
                <div class="flex items-center space-x-2 mb-1">
                    <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider rounded-md bg-white/20 dark:bg-blue-500/20 text-white dark:text-blue-300 border border-white/30 dark:border-blue-400/30">
                        <?= session()->get('role') ?? 'Admin' ?>
                    </span>
                    <span class="text-xs text-blue-100 dark:text-slate-400 font-medium">OSS Display TV Hub</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">Halo, <?= session()->get('name') ?? 'Admin' ?>! 👋</h2>
                <p class="text-xs text-blue-100/90 dark:text-slate-400 mt-0.5 max-w-lg leading-relaxed">
                    Pantau tayangan layar, jadwalkan playlist, dan kelola display secara real-time.
                </p>
            </div>
        </div>

        <!-- Header Quick Buttons (Tablet / Desktop) -->
        <div class="flex items-center space-x-2.5 shrink-0 pt-2 md:pt-0">
            <a href="<?= base_url('admin/playlist') ?>" class="flex-1 sm:flex-initial px-3.5 py-2.5 bg-white/10 hover:bg-white/20 dark:bg-slate-800/80 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold border border-white/20 dark:border-slate-700 transition-all flex items-center justify-center space-x-2 active:scale-95 ux-hover">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="10" x2="21" y1="6" y2="6"></line>
                    <line x1="10" x2="21" y1="12" y2="12"></line>
                    <line x1="10" x2="21" y1="18" y2="18"></line>
                    <path d="M4 6h1v4"></path>
                    <path d="M4 10h2"></path>
                    <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                </svg>
                <span>Urutan Playlist</span>
            </a>
            <a href="<?= base_url('admin/content') ?>" class="flex-1 sm:flex-initial px-4 py-2.5 bg-white text-blue-700 hover:bg-blue-50 dark:bg-blue-600 dark:hover:bg-blue-500 dark:text-white rounded-xl text-xs font-semibold shadow-lg transition-all flex items-center justify-center space-x-2 active:scale-95 ux-hover">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Upload Media</span>
            </a>
        </div>
    </div>
</div>

<!-- 2. Quick Metrics / Summary Cards (Mobile 2-cols, Desktop 4-cols) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <!-- Stat 1: TV Online -->
    <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-blue-500/30 shadow-xs transition-all ux-card">
        <div class="flex items-center justify-between mb-2.5">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="15" x="2" y="7" rx="2" ry="2"></rect>
                    <polyline points="17 2 12 7 7 2"></polyline>
                </svg>
            </div>
            <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span>Live</span>
            </span>
        </div>
        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">
            <?= $stats['online_tvs'] ?> <span class="text-xs sm:text-sm font-semibold text-slate-400">/ <?= $stats['total_tvs'] ?></span>
        </h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Display TV Online</p>
        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium inline-flex items-center space-x-1 mt-1">
            <span><?= $stats['active_tvs'] ?> Unit Aktif</span>
        </span>
    </div>

    <!-- Stat 2: Media Konten -->
    <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-indigo-500/30 shadow-xs transition-all ux-card">
        <div class="flex items-center justify-between mb-2.5">
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                </svg>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                Media
            </span>
        </div>
        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">
            <?= $stats['total_contents'] ?>
        </h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Total Konten Media</p>
        <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 block">Gambar, Video, Chart</span>
    </div>

    <!-- Stat 3: Total Kategori -->
    <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-sky-500/30 shadow-xs transition-all ux-card">
        <div class="flex items-center justify-between mb-2.5">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z"></path>
                    <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65"></path>
                    <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65"></path>
                </svg>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                Kategori
            </span>
        </div>
        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">
            <?= $stats['total_categories'] ?>
        </h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Kategori Konten</p>
        <span class="text-[11px] text-sky-600 dark:text-sky-400 font-medium inline-flex items-center space-x-1 mt-1">
            <span>Grup Playlist Aktif</span>
        </span>
    </div>

    <!-- Stat 4: Pengguna Sistem -->
    <div class="p-4 sm:p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-amber-500/30 shadow-xs transition-all ux-card">
        <div class="flex items-center justify-between mb-2.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                Admin
            </span>
        </div>
        <h3 class="text-xl sm:text-2xl font-extrabold text-slate-800 dark:text-white tracking-tight">
            <?= $stats['total_users'] ?>
        </h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Pengguna Sistem</p>
        <span class="text-[11px] text-amber-600 dark:text-amber-400 font-medium inline-flex items-center space-x-1 mt-1">
            <span>Akses Panel Kontrol</span>
        </span>
    </div>
</div>

<!-- 3. Quick Action Grid (4 Primary Shortcut Buttons) -->
<div class="mb-6">
    <div class="flex items-center justify-between mb-3 px-1">
        <h3 class="text-sm font-bold text-slate-800 dark:text-white tracking-tight">Aksi Cepat Manajemen</h3>
        <span class="text-[11px] text-slate-400 font-medium">Pintasan Utama</span>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <!-- Shortcut 1: Upload Konten -->
        <a href="<?= base_url('admin/content') ?>" class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-blue-500/40 hover:shadow-md transition-all group active:scale-95 flex flex-col justify-between ux-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-blue-600 to-indigo-500 text-white flex items-center justify-center shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path>
                        <path d="M12 12v9"></path>
                        <path d="m16 16-4-4-4 4"></path>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-blue-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Upload Konten</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Tambah file media & chart</p>
            </div>
        </a>

        <!-- Shortcut 2: Atur Playlist -->
        <a href="<?= base_url('admin/playlist') ?>" class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-indigo-500/40 hover:shadow-md transition-all group active:scale-95 flex flex-col justify-between ux-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="10" x2="21" y1="6" y2="6"></line>
                        <line x1="10" x2="21" y1="12" y2="12"></line>
                        <line x1="10" x2="21" y1="18" y2="18"></line>
                        <path d="M4 6h1v4"></path>
                        <path d="M4 10h2"></path>
                        <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-indigo-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Atur Playlist</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Susun urutan tayang</p>
            </div>
        </a>

        <!-- Shortcut 3: Kelola TV Display -->
        <a href="<?= base_url('admin/tv') ?>" class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-sky-500/40 hover:shadow-md transition-all group active:scale-95 flex flex-col justify-between ux-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-sky-600 to-cyan-500 text-white flex items-center justify-center shadow-md shadow-sky-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="15" x="2" y="7" rx="2" ry="2"></rect>
                        <polyline points="17 2 12 7 7 2"></polyline>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-sky-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">Kelola TV</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Display & PIN akses</p>
            </div>
        </a>

        <!-- Shortcut 4: Lihat TV Publik -->
        <a href="<?= base_url('/') ?>" target="_blank" class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 hover:border-emerald-500/40 hover:shadow-md transition-all group active:scale-95 flex flex-col justify-between ux-card">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-linear-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                </div>
                <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-emerald-500 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                    <polyline points="15 3 21 3 21 9"></polyline>
                    <line x1="10" y1="14" x2="21" y2="3"></line>
                </svg>
            </div>
            <div>
                <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">TV Publik</h4>
                <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Buka landing display</p>
            </div>
        </a>
    </div>
</div>

<!-- 4. Section: TV Status Monitoring + Content Distribution -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-6 mb-6">

    <!-- TV Status Real-Time List (3 cols xl) -->
    <div class="xl:col-span-3 bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-4 sm:p-6 shadow-xs flex flex-col justify-between">
        <div>
            <!-- Header Bar -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white">Monitoring Display TV</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Status online heartbeat real-time</p>
                </div>
                <a href="<?= base_url('admin/tv') ?>" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center space-x-1">
                    <span>Kelola TV</span>
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </div>

            <?php if (empty($recentTvs)): ?>
                <div class="text-center py-10 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="15" x="2" y="7" rx="2" ry="2"></rect>
                        <polyline points="17 2 12 7 7 2"></polyline>
                    </svg>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada TV yang terdaftar.</p>
                </div>
            <?php else: ?>
                <!-- Search & Status Filter Toolbar (Sticky on Mobile) -->
                <div class="sticky top-0 z-10 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md pt-1 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800/60 -mx-1 px-1">
                    <div class="flex flex-col gap-2.5">
                        <!-- Quick Search Input -->
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </div>
                            <input type="text" id="dashboardTvSearch" placeholder="Cari nama TV atau lokasi..."
                                class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                            <button type="button" id="btnDashboardTvSearchClear" class="absolute inset-y-0 right-0 pr-3 items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hidden">
                                <i class="fa-solid fa-circle-xmark text-xs"></i>
                            </button>
                        </div>

                        <!-- Status Filter Chips -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-0.5 text-xs no-scrollbar" id="dashboardTvFilterChips">
                            <button type="button" class="tv-filter-chip px-3 py-1.5 rounded-xl font-semibold text-xs transition-all active:scale-95 border bg-blue-600 text-white border-blue-600 shadow-xs" data-status="all">
                                <span>Semua</span>
                                <span class="ml-1 text-[10px] px-1.5 py-0.2 rounded-full bg-white/20"><?= count($recentTvs) ?></span>
                            </button>
                            <button type="button" class="tv-filter-chip px-3 py-1.5 rounded-xl font-semibold text-xs transition-all active:scale-95 border bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-emerald-500/50" data-status="online">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block mr-1"></span>
                                <span>Online</span>
                            </button>
                            <button type="button" class="tv-filter-chip px-3 py-1.5 rounded-xl font-semibold text-xs transition-all active:scale-95 border bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-slate-400" data-status="standby">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 inline-block mr-1"></span>
                                <span>Standby</span>
                            </button>
                            <button type="button" class="tv-filter-chip px-3 py-1.5 rounded-xl font-semibold text-xs transition-all active:scale-95 border bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-rose-500/50" data-status="inactive">
                                <span>Nonaktif</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Search/Filter State -->
                <div id="dashboardTvEmptyState" class="hidden py-8 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-xl my-2">
                    <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Tidak ada TV yang sesuai dengan filter.</p>
                </div>

                <!-- Mobile Card Feed View (< sm) -->
                <div class="sm:hidden space-y-3" id="dashboardTvMobileList">
                    <?php foreach ($recentTvs as $tv): ?>
                        <?php 
                            $tvStatus = $tv['is_online'] ? 'online' : ($tv['is_active'] ? 'standby' : 'inactive');
                        ?>
                        <div class="dashboard-tv-item p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-200/70 dark:border-slate-800/70 shadow-xs space-y-3 transition-all"
                            data-name="<?= esc(strtolower($tv['name'])) ?>"
                            data-location="<?= esc(strtolower($tv['location'] ?? '')) ?>"
                            data-status="<?= $tvStatus ?>">
                            
                            <!-- Header: Name & Status -->
                            <div class="flex items-center justify-between gap-2">
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-800 dark:text-slate-200 text-sm truncate"><?= esc($tv['name']) ?></h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1.5 mt-0.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
                                            <circle cx="12" cy="10" r="3"></circle>
                                        </svg>
                                        <span class="truncate"><?= esc($tv['location'] ?? 'Lokasi belum diatur') ?></span>
                                    </p>
                                </div>
                                <?php if ($tv['is_online']): ?>
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30 shrink-0">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                                        <span>ONLINE</span>
                                    </span>
                                <?php elseif ($tv['is_active']): ?>
                                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-200/70 dark:bg-slate-700/60 text-slate-600 dark:text-slate-300 shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Standby</span>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 shrink-0">
                                        <span>Nonaktif</span>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Meta info: Last Seen -->
                            <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 pt-2 border-t border-slate-200/60 dark:border-slate-800/60">
                                <span>Akses Terakhir:</span>
                                <span class="font-medium text-slate-600 dark:text-slate-300">
                                    <?= $tv['last_seen_at'] ? date('d M, H:i', strtotime($tv['last_seen_at'])) : 'Belum pernah akses' ?>
                                </span>
                            </div>

                            <!-- Action Buttons Row (Touch Targets >= 44x44px) -->
                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <!-- Copy PIN Button -->
                                <button type="button" class="btnCopyDashboardPin min-h-11 px-3 py-2 rounded-xl bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/20 text-blue-600 dark:text-blue-400 font-semibold text-xs flex items-center justify-center space-x-2 transition-all active:scale-95" data-pin="<?= esc($tv['pin']) ?>">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                    <span>PIN: <strong class="font-mono"><?= esc($tv['pin']) ?></strong></span>
                                </button>

                                <!-- Open Public TV Page -->
                                <a href="<?= base_url('tv/' . $tv['slug']) ?>" target="_blank" class="min-h-11 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs flex items-center justify-center space-x-1.5 transition-all active:scale-95">
                                    <span>Buka TV</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Desktop & Tablet Table View (>= sm) -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="dashboardTvTable">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-4">Nama TV</th>
                                <th class="py-3 px-4">Lokasi</th>
                                <th class="py-3 px-4">PIN</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Akses Terakhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs">
                            <?php foreach ($recentTvs as $tv): ?>
                                <?php 
                                    $tvStatus = $tv['is_online'] ? 'online' : ($tv['is_active'] ? 'standby' : 'inactive');
                                ?>
                                <tr class="dashboard-tv-item hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors"
                                    data-name="<?= esc(strtolower($tv['name'])) ?>"
                                    data-location="<?= esc(strtolower($tv['location'] ?? '')) ?>"
                                    data-status="<?= $tvStatus ?>">
                                    <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                        <a href="<?= base_url('admin/tv') ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                            <?= esc($tv['name']) ?>
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400"><?= esc($tv['location'] ?? '-') ?></td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-blue-600 dark:text-blue-400">
                                        <div class="flex items-center space-x-2">
                                            <span class="bg-blue-500/10 border border-blue-500/20 px-2 py-0.5 rounded-lg text-xs">
                                                <?= esc($tv['pin']) ?>
                                            </span>
                                            <button type="button" class="btnCopyDashboardPin p-1 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors ux-hover" data-pin="<?= esc($tv['pin']) ?>" title="Copy PIN ke Clipboard">
                                                <i class="fa-regular fa-copy text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <?php if ($tv['is_online']): ?>
                                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-300 border border-emerald-500/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-ping"></span>
                                                <span>ONLINE</span>
                                            </span>
                                        <?php elseif ($tv['is_active']): ?>
                                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span>
                                                <span>Standby</span>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                                <span>Nonaktif</span>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 text-[10px]">
                                        <?= $tv['last_seen_at'] ? date('d M, H:i', strtotime($tv['last_seen_at'])) : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content Distribution Card (2 cols xl) -->
    <div class="xl:col-span-2 bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-5 sm:p-6 shadow-xs flex flex-col justify-between">
        <div>
            <div class="mb-4">
                <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white">Distribusi Konten</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Proporsi konten per kategori aktif</p>
            </div>

            <?php if (empty($contentByCategory)): ?>
                <div class="text-center py-10 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                    <svg class="w-10 h-10 text-slate-400 dark:text-slate-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada kategori konten.</p>
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php
                    $maxTotal = max(array_column($contentByCategory, 'total'));
                    foreach ($contentByCategory as $cat):
                        $pct = $maxTotal > 0 ? round(($cat['total'] / $maxTotal) * 100) : 0;
                    ?>
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center space-x-2">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $cat['color'] ?>"></span>
                                    <span class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate max-w-40"><?= esc($cat['name']) ?></span>
                                </div>
                                <span class="text-xs font-bold text-slate-900 dark:text-white ml-2"><?= $cat['total'] ?> item</span>
                            </div>
                            <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-700" style="width: <?= $pct ?>%; background-color: <?= $cat['color'] ?>"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Type Distribution Chips -->
        <?php if (!empty($contentTypes)): ?>
            <div class="pt-4 border-t border-slate-200/80 dark:border-slate-800 mt-4 flex flex-wrap gap-2">
                <?php foreach ($contentTypes as $ct): ?>
                    <?php
                    $typeColors = [
                        'image' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-500/20'],
                        'video' => ['bg' => 'bg-indigo-500/10', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'border-indigo-500/20'],
                        'chart' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'border-amber-500/20'],
                    ];
                    $tc = $typeColors[$ct['type']] ?? ['bg' => 'bg-slate-100 dark:bg-slate-800', 'text' => 'text-slate-600 dark:text-slate-400', 'border' => 'border-slate-200 dark:border-slate-700'];
                    ?>
                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-bold border <?= $tc['bg'] . ' ' . $tc['text'] . ' ' . $tc['border'] ?> inline-flex items-center space-x-1.5">
                        <i class="fa-solid <?= $ct['type'] === 'image' ? 'fa-image' : ($ct['type'] === 'video' ? 'fa-video' : 'fa-chart-column') ?>"></i>
                        <span><?= ucfirst($ct['type']) ?>: <?= $ct['total'] ?></span>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- 5. Section: Recent PIN Access Activity Feed -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-4 sm:p-6 shadow-xs">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white">Riwayat Verifikasi PIN TV</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Audit log akses terakhir dari perangkat display</p>
        </div>
        <a href="<?= base_url('admin/audit-logs') ?>" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline flex items-center space-x-1">
            <span>Lihat Semua Log</span>
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </a>
    </div>

    <?php if (empty($recentAccessLogs)): ?>
        <div class="text-center py-10 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
            <svg class="w-10 h-10 text-slate-400 dark:text-slate-600 mx-auto mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="7.5" cy="15.5" r="5.5"></circle>
                <path d="m21 2-9.6 9.6"></path>
                <path d="m15.5 7.5 3 3L22 7l-3-3"></path>
            </svg>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada riwayat verifikasi PIN tercatat.</p>
        </div>
    <?php else: ?>
        <!-- Quick Search Toolbar (Sticky on Mobile) -->
        <div class="sticky top-0 z-10 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md pt-1 pb-3 mb-3 border-b border-slate-100 dark:border-slate-800/60 -mx-1 px-1">
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" id="dashboardLogSearch" placeholder="Cari TV atau IP address..."
                    class="w-full pl-9 pr-8 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                <button type="button" id="btnDashboardLogSearchClear" class="absolute inset-y-0 right-0 pr-3 items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hidden">
                    <i class="fa-solid fa-circle-xmark text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Empty Search State -->
        <div id="dashboardLogEmptyState" class="hidden py-8 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-xl my-2">
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Tidak ada log akses yang sesuai dengan pencarian.</p>
        </div>

        <!-- Mobile Feed Card List (< sm) -->
        <div class="sm:hidden space-y-2.5" id="dashboardLogMobileList">
            <?php foreach ($recentAccessLogs as $log): ?>
                <div class="dashboard-log-item p-3.5 rounded-xl bg-slate-50/70 dark:bg-slate-800/40 border border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between gap-3 transition-all"
                    data-name="<?= esc(strtolower($log['tv_name'])) ?>"
                    data-location="<?= esc(strtolower($log['tv_location'] ?? '')) ?>"
                    data-ip="<?= esc(strtolower($log['ip_address'])) ?>">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/20">
                            <i class="fa-solid fa-key text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate"><?= esc($log['tv_name']) ?></p>
                            <div class="flex items-center space-x-2 mt-0.5">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-mono"><?= esc($log['ip_address']) ?></span>
                                <?php if (!empty($log['tv_location'])): ?>
                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 truncate"><?= esc($log['tv_location']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[11px] font-medium text-slate-700 dark:text-slate-300 block">
                            <?= date('H:i', strtotime($log['accessed_at'])) ?>
                        </span>
                        <span class="text-[9px] text-slate-400 dark:text-slate-500 block">
                            <?= date('d M', strtotime($log['accessed_at'])) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Desktop & Tablet Table (>= sm) -->
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse" id="dashboardLogTable">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Display TV</th>
                        <th class="py-3 px-4">Lokasi</th>
                        <th class="py-3 px-4">IP Address</th>
                        <th class="py-3 px-4">Waktu Akses</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs">
                    <?php foreach ($recentAccessLogs as $log): ?>
                        <tr class="dashboard-log-item hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors"
                            data-name="<?= esc(strtolower($log['tv_name'])) ?>"
                            data-location="<?= esc(strtolower($log['tv_location'] ?? '')) ?>"
                            data-ip="<?= esc(strtolower($log['ip_address'])) ?>">
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                <?= esc($log['tv_name']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= esc($log['tv_location'] ?? '-') ?>
                            </td>
                            <td class="py-3 px-4 font-mono text-blue-600 dark:text-blue-400">
                                <?= esc($log['ip_address']) ?>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                <?= date('d M Y, H:i:s', strtotime($log['accessed_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        // Copy PIN Action
        $(document).on('click', '.btnCopyDashboardPin', function () {
            const pin = $(this).data('pin');
            copyPinToClipboard(pin, this);
        });

        // TV Monitoring Live Filter
        let activeTvStatus = 'all';
        function filterDashboardTvs() {
            const query = $('#dashboardTvSearch').val().toLowerCase().trim();
            let visibleCount = 0;

            $('.dashboard-tv-item').each(function () {
                const name = $(this).data('name') || '';
                const location = $(this).data('location') || '';
                const status = $(this).data('status') || '';

                const matchQuery = !query || name.includes(query) || location.includes(query);
                const matchStatus = (activeTvStatus === 'all') || (status === activeTvStatus);

                if (matchQuery && matchStatus) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            if (visibleCount === 0) {
                $('#dashboardTvEmptyState').removeClass('hidden');
            } else {
                $('#dashboardTvEmptyState').addClass('hidden');
            }

            if (query) {
                $('#btnDashboardTvSearchClear').removeClass('hidden').addClass('flex');
            } else {
                $('#btnDashboardTvSearchClear').addClass('hidden').removeClass('flex');
            }
        }

        $('#dashboardTvSearch').on('input', filterDashboardTvs);

        $('#btnDashboardTvSearchClear').on('click', function () {
            $('#dashboardTvSearch').val('');
            filterDashboardTvs();
        });

        $('.tv-filter-chip').on('click', function () {
            $('.tv-filter-chip').removeClass('bg-blue-600 text-white border-blue-600 shadow-xs')
                                .addClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700');
            $(this).removeClass('bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700')
                   .addClass('bg-blue-600 text-white border-blue-600 shadow-xs');

            activeTvStatus = $(this).data('status');
            filterDashboardTvs();
        });

        // Access Logs Live Filter
        function filterDashboardLogs() {
            const query = $('#dashboardLogSearch').val().toLowerCase().trim();
            let visibleCount = 0;

            $('.dashboard-log-item').each(function () {
                const name = $(this).data('name') || '';
                const location = $(this).data('location') || '';
                const ip = $(this).data('ip') || '';

                const matchQuery = !query || name.includes(query) || location.includes(query) || ip.includes(query);

                if (matchQuery) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            if (visibleCount === 0) {
                $('#dashboardLogEmptyState').removeClass('hidden');
            } else {
                $('#dashboardLogEmptyState').addClass('hidden');
            }

            if (query) {
                $('#btnDashboardLogSearchClear').removeClass('hidden').addClass('flex');
            } else {
                $('#btnDashboardLogSearchClear').addClass('hidden').removeClass('flex');
            }
        }

        $('#dashboardLogSearch').on('input', filterDashboardLogs);

        $('#btnDashboardLogSearchClear').on('click', function () {
            $('#dashboardLogSearch').val('');
            filterDashboardLogs();
        });
    });
</script>
<?= $this->endSection() ?>
