<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Manajemen Display TV</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan perangkat TV, atur lokasi, assign kombinasi kategori, dan kelola PIN keamanan.</p>
    </div>
    <div>
        <button type="button" id="btnCreateTv" class="w-full sm:w-auto px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center space-x-2 active:scale-95 ux-hover cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah TV Display Baru</span>
        </button>
    </div>
</div>

<!-- Table Card & Mobile Card View Container -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-4 sm:p-6 shadow-xs relative">
    
    <!-- =========================================================================
         1. Sticky Search & Filter Toolbar on Mobile (sticky top-0 z-20)
         Desktop: standard clean search bar + quick status filter
         ========================================================================= -->
    <div class="sticky top-0 z-20 -mx-4 -mt-4 p-4 sm:mx-0 sm:mt-0 sm:p-0 bg-white/95 dark:bg-slate-900/95 sm:bg-transparent backdrop-blur-md sm:backdrop-blur-none border-b border-slate-200/80 dark:border-slate-800/80 sm:border-0 mb-4 sm:mb-6 rounded-t-2xl">
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Search Input -->
            <div class="relative flex-1 sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama TV, lokasi, atau PIN..." 
                    class="w-full pl-9 pr-8 py-2.5 sm:py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                <button type="button" id="btnClearSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hidden" title="Hapus Pencarian">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Mobile Filter & Sort Button (< md) -->
            <button type="button" id="btnOpenTvFilterSort" class="md:hidden relative px-3 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 flex items-center space-x-1.5 active:scale-95 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span>Filter</span>
                <span id="tvFilterBadge" class="hidden w-2 h-2 rounded-full bg-blue-600 ring-2 ring-white dark:ring-slate-900"></span>
            </button>

            <!-- Desktop Filter Controls (>= md) -->
            <div class="hidden md:flex items-center space-x-3">
                <!-- Status Filter Pills -->
                <div class="flex items-center bg-slate-100 dark:bg-slate-950 p-1 rounded-xl border border-slate-200/80 dark:border-slate-800 text-xs">
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-semibold transition-all bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs cursor-pointer" data-status="all">Semua</button>
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all cursor-pointer" data-status="online">Online</button>
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all cursor-pointer" data-status="standby">Standby</button>
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all cursor-pointer" data-status="inactive">Nonaktif</button>
                </div>

                <!-- Desktop Sort Select -->
                <select id="desktopTvSort" class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 px-3 py-2 focus:outline-hidden focus:border-blue-500 transition-all cursor-pointer">
                    <option value="newest">Urut: Terbaru</option>
                    <option value="oldest">Urut: Terlama</option>
                    <option value="name_asc">Nama: A - Z</option>
                    <option value="name_desc">Nama: Z - A</option>
                    <option value="online_first">Status: Online Duluan</option>
                </select>
            </div>
        </div>

        <!-- Active Filter Badges Row (Mobile & Desktop) -->
        <div id="activeFilterTags" class="flex flex-wrap items-center gap-1.5 pt-2 hidden">
            <!-- Dynamic chips rendered by JS -->
        </div>
    </div>

    <!-- =========================================================================
         2. Mobile Responsive Card View (Visible only on < md screens)
         Touch targets >= 44px, status badges, copyable PIN, and 3-dots action sheet
         ========================================================================= -->
    <div id="tvMobileCardContainer" class="md:hidden space-y-3">
        <!-- Rendered dynamically by renderTable() -->
        <div class="py-12 text-center text-slate-400 dark:text-slate-500">
            <svg class="w-8 h-8 animate-spin mx-auto mb-2 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                <path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="0.8"></path>
            </svg>
            <span class="text-xs">Memuat data Display TV...</span>
        </div>
    </div>

    <!-- =========================================================================
         3. Desktop Dense Data Table (Visible only on >= md screens)
         ========================================================================= -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse" id="tvTable">
            <thead>
                <tr class="border-b border-slate-200/80 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-4">Display TV</th>
                    <th class="py-3.5 px-4">PIN Akses</th>
                    <th class="py-3.5 px-4">Kategori Ter-assign</th>
                    <th class="py-3.5 px-4">Status Device</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/80 dark:divide-slate-800/50 text-xs" id="tvTableBody">
                <!-- Loading State -->
                <tr>
                    <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-spinner fa-spin text-lg mb-2 block"></i>
                        <span>Memuat data Display TV...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Summary Count Footer -->
    <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
        <span id="tvTotalCount">Menampilkan 0 Display TV</span>
        <span class="flex items-center space-x-1.5 text-emerald-600 dark:text-emerald-400">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span id="tvOnlineCount">0 Online</span>
        </span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<!-- =========================================================================
     4. Filter & Sorting Bottom Sheet Drawer (Mobile)
     ========================================================================= -->
<div id="tvFilterSortModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-250 flex flex-col justify-end md:hidden">
    <div class="w-full max-w-lg mx-auto p-4">
        <div id="tvFilterSortPanel" class="w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 shadow-2xl translate-y-full transition-transform duration-300 ease-out max-h-[85vh] flex flex-col">
            <!-- Drag Handle -->
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 shrink-0"></div>

            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4 shrink-0">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Filter & Urutan TV</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Sesuaikan tampilan daftar perangkat TV</p>
                </div>
                <button type="button" class="btnCloseTvFilterModal p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl" title="Tutup">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Status Koneksi TV</label>
                    <div class="grid grid-cols-2 gap-2" id="mobileFilterStatusGroup">
                        <button type="button" class="btnMobileStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30" data-status="all">
                            Semua Status
                        </button>
                        <button type="button" class="btnMobileStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-status="online">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1 animate-ping"></span>Online
                        </button>
                        <button type="button" class="btnMobileStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-status="standby">
                            Standby
                        </button>
                        <button type="button" class="btnMobileStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-status="inactive">
                            Nonaktif
                        </button>
                    </div>
                </div>

                <!-- Sort Options -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Urutan Tampilan</label>
                    <div class="space-y-1.5" id="mobileSortGroup">
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Terbaru Ditambahkan</span>
                            <input type="radio" name="mobileTvSort" value="newest" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Online Teratas</span>
                            <input type="radio" name="mobileTvSort" value="online_first" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Nama TV: A - Z</span>
                            <input type="radio" name="mobileTvSort" value="name_asc" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Nama TV: Z - A</span>
                            <input type="radio" name="mobileTvSort" value="name_desc" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Terlama Ditambahkan</span>
                            <input type="radio" name="mobileTvSort" value="oldest" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3 shrink-0">
                <button type="button" id="btnResetMobileTvFilter" class="flex-1 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs text-center cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Reset
                </button>
                <button type="button" id="btnApplyMobileTvFilter" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs text-center shadow-lg shadow-blue-500/20 cursor-pointer transition-colors">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     5. Card Action Sheet Drawer (Mobile - Triggered on card tap / 3-dots)
     ========================================================================= -->
<div id="tvActionSheetModal" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-250 flex flex-col justify-end md:hidden">
    <div class="w-full max-w-lg mx-auto p-4">
        <div id="tvActionSheetPanel" class="w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 shadow-2xl translate-y-full transition-transform duration-300 ease-out">
            <!-- Drag Handle -->
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 shrink-0"></div>

            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                <div class="overflow-hidden">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate" id="actionSheetTvName">Display TV</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate" id="actionSheetTvLocation">-</p>
                </div>
                <button type="button" class="btnCloseTvActionSheet p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl" title="Tutup">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Action Buttons Stack (Min 44px touch targets) -->
            <div class="space-y-1.5">
                <!-- Copy PIN Action -->
                <button type="button" id="btnSheetCopyPin" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <i class="fa-regular fa-copy text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Salin PIN Akses</p>
                        <span class="text-[10px] text-slate-400 font-mono" id="actionSheetTvPinBadge">PIN: ------</span>
                    </div>
                </button>

                <!-- Regenerate PIN Action -->
                <button type="button" id="btnSheetRegenPin" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-amber-500/10 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:text-amber-600 dark:hover:text-amber-400 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                        <i class="fa-solid fa-rotate text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Regenerate PIN Baru</p>
                        <span class="text-[10px] text-slate-400">Putuskan sesi lama & buat PIN baru</span>
                    </div>
                </button>

                <!-- Edit TV Action -->
                <button type="button" id="btnSheetEditTv" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Edit Data TV</p>
                        <span class="text-[10px] text-slate-400">Ubah nama, lokasi, kategori, thumbnail</span>
                    </div>
                </button>

                <!-- Open Public TV Landing -->
                <a href="<?= base_url('/') ?>" target="_blank" id="btnSheetViewTv" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Buka Landing Page TV</p>
                        <span class="text-[10px] text-slate-400">Preview tayangan layar</span>
                    </div>
                </a>

                <!-- Delete TV Action (Danger) -->
                <button type="button" id="btnSheetDeleteTv" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-rose-500/10 text-xs font-semibold text-rose-600 dark:text-rose-400 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Hapus Display TV</p>
                        <span class="text-[10px] text-rose-500/80">Hapus perangkat ini dari sistem</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form TV (Tambah / Edit) -->
<div id="tvModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-xl p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-5 shrink-0">
            <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalTitle">Tambah TV Display Baru</h3>
            <button type="button" class="btnCloseModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Form (Scrollable Content) -->
        <form id="tvForm" enctype="multipart/form-data" class="flex-1 overflow-y-auto pr-1">
            <input type="hidden" id="tvId" name="id">

            <div class="space-y-4">
                <!-- Nama TV Input -->
                <div>
                    <label for="tvName" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Display TV</label>
                    <input type="text" id="tvName" name="name" required placeholder="Contoh: TV Lobby Utama, TV Ruang Tunggu A"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_name"></p>
                </div>

                <!-- Lokasi Input -->
                <div>
                    <label for="tvLocation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Lokasi Perangkat</label>
                    <input type="text" id="tvLocation" name="location" placeholder="Contoh: Gedung Rektorat Lt. 1"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_location"></p>
                </div>

                <!-- Multi-select Categories -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Assign Kategori Konten ke TV Ini</label>
                    <?php if (empty($categories)): ?>
                        <p class="text-xs text-amber-500 italic">Belum ada kategori. <a href="<?= base_url('admin/category') ?>" class="underline">Buat kategori dulu</a>.</p>
                    <?php else: ?>
                        <div class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl">
                            <?php foreach ($categories as $cat): ?>
                                <label class="flex items-center space-x-2.5 cursor-pointer p-1.5 rounded hover:bg-slate-200/60 dark:hover:bg-slate-800/50 transition-colors">
                                    <input type="checkbox" name="category_ids[]" value="<?= $cat['id'] ?>" class="categoryCheckbox w-4 h-4 rounded bg-slate-100 dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $cat['color'] ?: '#6366f1' ?>;"></span>
                                    <span class="text-xs text-slate-700 dark:text-slate-200 truncate"><?= esc($cat['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Thumbnail Upload -->
                <div>
                    <label for="tvThumbnail" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Gambar Thumbnail Kartu Landing Page <span class="text-slate-400 dark:text-slate-500 font-normal text-[10px]">(Opsional)</span>
                    </label>
                    <input type="file" id="tvThumbnail" name="thumbnail" accept="image/*"
                        class="w-full text-xs text-slate-500 dark:text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-200 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-200 hover:file:bg-slate-300 dark:hover:file:bg-slate-700 cursor-pointer">
                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">Format: JPG, PNG, WEBP (Max 2MB)</p>
                    
                    <!-- Preview Image -->
                    <div id="thumbnailPreviewContainer" class="mt-3 hidden">
                        <img id="thumbnailPreview" src="" alt="Preview" class="w-32 h-20 object-cover rounded-xl border border-slate-200 dark:border-slate-800">
                    </div>
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="tvIsActive" name="is_active" value="1" checked
                        class="w-4 h-4 rounded bg-slate-100 dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-blue-600 focus:ring-blue-500">
                    <label for="tvIsActive" class="text-xs text-slate-700 dark:text-slate-300 font-medium">Status TV Aktif</label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 ux-hover">
                    Batal
                </button>
                <button type="submit" id="btnSubmitTv" class="px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
                    <span id="btnSubmitText">Simpan Perangkat TV</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let tvsData = [];
        let currentStatusFilter = 'all';
        let currentSort = 'newest';
        let selectedTvForActionSheet = null;

        function showToast(icon, title) {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            }).fire({ icon, title });
        }

        // Fetch TV List
        function loadTvs() {
            $.ajax({
                url: '<?= base_url('admin/tv/list') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        tvsData = res.data;
                        renderTable();
                    }
                },
                error: function () {
                    $('#tvTableBody').html(`
                        <tr>
                            <td colspan="5" class="py-8 text-center text-rose-500">
                                Gagal memuat data Display TV.
                            </td>
                        </tr>
                    `);
                    $('#tvMobileCardContainer').html(`
                        <div class="py-8 text-center text-rose-500 text-xs">
                            Gagal memuat data Display TV.
                        </div>
                    `);
                }
            });
        }

        // Render Data to Table & Mobile Card Stack
        function renderTable() {
            const search = ($('#searchInput').val() || '').toLowerCase();

            // 1. Filter by Search & Status
            let filtered = tvsData.filter(tv => {
                const nameMatch = (tv.name || '').toLowerCase().includes(search);
                const locMatch = (tv.location || '').toLowerCase().includes(search);
                const pinMatch = (tv.pin || '').includes(search);
                const matchesSearch = nameMatch || locMatch || pinMatch;

                if (!matchesSearch) return false;

                if (currentStatusFilter === 'online') {
                    return tv.is_online === true;
                } else if (currentStatusFilter === 'standby') {
                    return !tv.is_online && tv.is_active == 1;
                } else if (currentStatusFilter === 'inactive') {
                    return tv.is_active != 1;
                }

                return true;
            });

            // 2. Sorting
            filtered.sort((a, b) => {
                if (currentSort === 'newest') {
                    return new Date(b.created_at || 0) - new Date(a.created_at || 0);
                } else if (currentSort === 'oldest') {
                    return new Date(a.created_at || 0) - new Date(b.created_at || 0);
                } else if (currentSort === 'name_asc') {
                    return a.name.localeCompare(b.name);
                } else if (currentSort === 'name_desc') {
                    return b.name.localeCompare(a.name);
                } else if (currentSort === 'online_first') {
                    return (b.is_online ? 1 : 0) - (a.is_online ? 1 : 0);
                }
                return 0;
            });

            // 3. Update Counters & Filter Badges
            const onlineCount = tvsData.filter(t => t.is_online).length;
            $('#tvTotalCount').text(`Menampilkan ${filtered.length} dari ${tvsData.length} Display TV`);
            $('#tvOnlineCount').text(`${onlineCount} Online`);

            // Active Filter Indicator
            const isFiltered = currentStatusFilter !== 'all' || currentSort !== 'newest' || search !== '';
            if (isFiltered) {
                $('#tvFilterBadge').removeClass('hidden');
                renderActiveFilterTags(search);
            } else {
                $('#tvFilterBadge').addClass('hidden');
                $('#activeFilterTags').addClass('hidden').html('');
            }

            // Search Clear Button
            if (search.length > 0) {
                $('#btnClearSearch').removeClass('hidden');
            } else {
                $('#btnClearSearch').addClass('hidden');
            }

            // Empty State
            if (filtered.length === 0) {
                $('#tvTableBody').html(`
                    <tr>
                        <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            Tidak ditemukan data Display TV yang cocok.
                        </td>
                    </tr>
                `);
                $('#tvMobileCardContainer').html(`
                    <div class="py-12 text-center text-slate-400 dark:text-slate-500 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-6">
                        <i class="fa-solid fa-tv text-2xl mb-2 block"></i>
                        <p class="text-xs font-semibold">Tidak ditemukan Display TV</p>
                        <p class="text-[11px] text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau ubah filter.</p>
                    </div>
                `);
                return;
            }

            let tableHtml = '';
            let cardHtml = '';

            filtered.forEach(tv => {
                const location = tv.location ? tv.location : '-';

                // Categories Badges
                let catBadges = '';
                if (tv.categories && tv.categories.length > 0) {
                    tv.categories.forEach(c => {
                        const color = c.color || '#6366f1';
                        catBadges += `
                            <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-semibold border mb-1 mr-1"
                                  style="background-color: ${color}15; color: ${color}; border-color: ${color}30;">
                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: ${color};"></span>
                                <span>${c.name}</span>
                            </span>
                        `;
                    });
                } else {
                    catBadges = `<span class="text-slate-400 dark:text-slate-500 italic text-[11px]">Belum ada kategori</span>`;
                }

                // Status Badge
                let onlineBadge = '';
                let cardStatusPill = '';
                if (tv.is_online) {
                    onlineBadge = `
                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20" title="TV sedang aktif menerima stream SSE">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-ping"></span><span>Online</span>
                        </span>
                    `;
                    cardStatusPill = `
                        <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span><span>ONLINE</span>
                        </span>
                    `;
                } else if (tv.is_active == 1) {
                    onlineBadge = `
                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span><span>Standby</span>
                        </span>
                    `;
                    cardStatusPill = `
                        <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span><span>Standby</span>
                        </span>
                    `;
                } else {
                    onlineBadge = `
                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                            <span>Nonaktif</span>
                        </span>
                    `;
                    cardStatusPill = `
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                            <span>Nonaktif</span>
                        </span>
                    `;
                }

                const thumbHtml = tv.thumbnail_url 
                    ? `<img src="${tv.thumbnail_url}" class="w-10 h-8 object-cover rounded-lg border border-slate-300 dark:border-slate-700 shrink-0">`
                    : `<div class="w-10 h-8 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-500 shrink-0"><i class="fa-solid fa-tv text-xs"></i></div>`;

                // --- 1. Desktop Table Row ---
                tableHtml += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-3">
                                ${thumbHtml}
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">${tv.name}</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400"><i class="fa-solid fa-location-dot text-[10px] mr-1 text-slate-400 dark:text-slate-500"></i>${location}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-mono">
                            <div class="flex items-center space-x-1.5">
                                <span class="bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 font-bold px-2.5 py-1 rounded-lg tracking-widest text-xs">
                                    ${tv.pin}
                                </span>
                                <button type="button" class="btnCopyTvPin text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-1.5 ux-hover cursor-pointer" data-pin="${tv.pin}" title="Copy PIN ke Clipboard">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <button type="button" class="btnRegeneratePin text-slate-400 hover:text-amber-500 p-1.5 ux-hover cursor-pointer" data-id="${tv.id}" data-name="${tv.name}" title="Regenerate PIN Baru">
                                    <i class="fa-solid fa-rotate text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">${catBadges}</td>
                        <td class="py-3.5 px-4">${onlineBadge}</td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            <button type="button" class="btnEditTv p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-500/10 rounded-lg ux-hover cursor-pointer" data-id="${tv.id}" title="Edit TV">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button type="button" class="btnDeleteTv p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg ux-hover cursor-pointer" data-id="${tv.id}" data-name="${tv.name}" title="Hapus TV">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </td>
                    </tr>
                `;

                // --- 2. Mobile Responsive Card (Touch Target >= 44px, Card Stack) ---
                cardHtml += `
                    <div class="mobileTvCard p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3 transition-all active:scale-[0.99]" data-id="${tv.id}">
                        <!-- Top Header: TV Info & Status -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3 overflow-hidden">
                                ${thumbHtml}
                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm truncate">${tv.name}</h4>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-0.5 truncate">
                                        <i class="fa-solid fa-location-dot text-[10px] text-slate-400"></i>
                                        <span>${location}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                ${cardStatusPill}
                            </div>
                        </div>

                        <!-- Mid Section: PIN & Categories -->
                        <div class="p-2.5 rounded-xl bg-slate-50/80 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between text-xs">
                            <div class="flex items-center space-x-2">
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">PIN:</span>
                                <span class="font-mono font-bold text-xs tracking-wider bg-blue-500/10 text-blue-600 dark:text-blue-400 px-2 py-0.5 rounded-md border border-blue-500/20">
                                    ${tv.pin}
                                </span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <button type="button" class="btnCopyTvPin min-w-[36px] min-h-[36px] flex items-center justify-center text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" data-pin="${tv.pin}" title="Salin PIN">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <button type="button" class="btnRegeneratePin min-w-[36px] min-h-[36px] flex items-center justify-center text-slate-400 hover:text-amber-500 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 transition-colors" data-id="${tv.id}" data-name="${tv.name}" title="Regen PIN">
                                    <i class="fa-solid fa-rotate text-xs"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Categories Badges -->
                        <div class="flex flex-wrap items-center gap-1">
                            ${catBadges}
                        </div>

                        <!-- Bottom Touch Action Bar (Min 44x44px touch targets) -->
                        <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-2">
                            <div class="flex items-center space-x-2 flex-1">
                                <button type="button" class="btnEditTv min-h-[44px] flex-1 px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 rounded-xl text-xs font-semibold flex items-center justify-center space-x-1.5 active:scale-95 transition-all" data-id="${tv.id}">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    <span>Edit</span>
                                </button>
                                <button type="button" class="btnDeleteTv min-h-[44px] px-3.5 py-2 bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs font-semibold flex items-center justify-center active:scale-95 transition-all" data-id="${tv.id}" data-name="${tv.name}" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                            
                            <!-- 3-Dots Action Sheet Trigger (Touch target 44x44px) -->
                            <button type="button" class="btnOpenTvCardActionSheet min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 active:scale-90 transition-all" data-id="${tv.id}" title="Opsi TV Lengkap">
                                <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            $('#tvTableBody').html(tableHtml);
            $('#tvMobileCardContainer').html(cardHtml);
        }

        // Render Active Filter Chips
        function renderActiveFilterTags(search) {
            let tags = '';

            if (search) {
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                        <span>Cari: "${search}"</span>
                        <button type="button" class="btnClearSpecificFilter ml-1 hover:text-blue-800 cursor-pointer" data-type="search">✕</button>
                    </span>
                `;
            }

            if (currentStatusFilter !== 'all') {
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <span>Status: ${currentStatusFilter.toUpperCase()}</span>
                        <button type="button" class="btnClearSpecificFilter ml-1 hover:text-emerald-800 cursor-pointer" data-type="status">✕</button>
                    </span>
                `;
            }

            if (currentSort !== 'newest') {
                const sortLabels = {
                    oldest: 'Terlama',
                    name_asc: 'Nama A-Z',
                    name_desc: 'Nama Z-A',
                    online_first: 'Online Teratas'
                };
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                        <span>Urut: ${sortLabels[currentSort] || currentSort}</span>
                        <button type="button" class="btnClearSpecificFilter ml-1 hover:text-indigo-800 cursor-pointer" data-type="sort">✕</button>
                    </span>
                `;
            }

            if (tags) {
                $('#activeFilterTags').removeClass('hidden').html(tags);
            } else {
                $('#activeFilterTags').addClass('hidden').html('');
            }
        }

        // Search Input Listener
        $('#searchInput').on('input', function () {
            renderTable();
        });

        // Clear Search Button
        $('#btnClearSearch').on('click', function () {
            $('#searchInput').val('');
            renderTable();
        });

        // Clear Specific Filter Chip
        $(document).on('click', '.btnClearSpecificFilter', function () {
            const type = $(this).data('type');
            if (type === 'search') {
                $('#searchInput').val('');
            } else if (type === 'status') {
                currentStatusFilter = 'all';
                updateStatusFilterUI();
            } else if (type === 'sort') {
                currentSort = 'newest';
                $('#desktopTvSort').val('newest');
                $('input[name="mobileTvSort"][value="newest"]').prop('checked', true);
            }
            renderTable();
        });

        // Desktop Status Filter Buttons
        $('.btnDesktopStatusFilter').on('click', function () {
            currentStatusFilter = $(this).data('status');
            updateStatusFilterUI();
            renderTable();
        });

        // Desktop Sort Select
        $('#desktopTvSort').on('change', function () {
            currentSort = $(this).val();
            $('input[name="mobileTvSort"][value="' + currentSort + '"]').prop('checked', true);
            renderTable();
        });

        function updateStatusFilterUI() {
            // Update Desktop buttons
            $('.btnDesktopStatusFilter').each(function () {
                const s = $(this).data('status');
                if (s === currentStatusFilter) {
                    $(this).removeClass('text-slate-500 dark:text-slate-400 font-medium').addClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 font-semibold shadow-xs');
                } else {
                    $(this).removeClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 font-semibold shadow-xs').addClass('text-slate-500 dark:text-slate-400 font-medium');
                }
            });

            // Update Mobile chips in Bottom Sheet
            $('.btnMobileStatusChip').each(function () {
                const s = $(this).data('status');
                if (s === currentStatusFilter) {
                    $(this).removeClass('bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700')
                           .addClass('bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30');
                } else {
                    $(this).removeClass('bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30')
                           .addClass('bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700');
                }
            });
        }

        // =========================================================================
        // Mobile Filter & Sort Bottom Sheet Drawer Handlers
        // =========================================================================
        function openTvFilterModal() {
            const overlay = document.getElementById('tvFilterSortModal');
            const panel   = document.getElementById('tvFilterSortPanel');
            if (!overlay || !panel) return;
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            panel.classList.remove('translate-y-full');
            panel.classList.add('translate-y-0');
        }

        function closeTvFilterModal() {
            const overlay = document.getElementById('tvFilterSortModal');
            const panel   = document.getElementById('tvFilterSortPanel');
            if (!overlay || !panel) return;
            panel.classList.remove('translate-y-0');
            panel.classList.add('translate-y-full');
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            overlay.classList.add('opacity-0', 'pointer-events-none');
        }

        $('#btnOpenTvFilterSort').on('click', openTvFilterModal);
        $('.btnCloseTvFilterModal').on('click', closeTvFilterModal);
        $('#tvFilterSortModal').on('click', function (e) {
            if (e.target === this) closeTvFilterModal();
        });

        // Mobile Status Chip Click
        $('.btnMobileStatusChip').on('click', function () {
            currentStatusFilter = $(this).data('status');
            updateStatusFilterUI();
        });

        // Apply Mobile Filter
        $('#btnApplyMobileTvFilter').on('click', function () {
            const selectedSort = $('input[name="mobileTvSort"]:checked').val() || 'newest';
            currentSort = selectedSort;
            $('#desktopTvSort').val(currentSort);
            closeTvFilterModal();
            renderTable();
        });

        // Reset Mobile Filter
        $('#btnResetMobileTvFilter').on('click', function () {
            currentStatusFilter = 'all';
            currentSort = 'newest';
            $('#searchInput').val('');
            $('#desktopTvSort').val('newest');
            $('input[name="mobileTvSort"][value="newest"]').prop('checked', true);
            updateStatusFilterUI();
            closeTvFilterModal();
            renderTable();
        });

        // =========================================================================
        // Mobile Card Action Sheet Drawer Handlers
        // =========================================================================
        function openTvActionSheet(tv) {
            selectedTvForActionSheet = tv;
            $('#actionSheetTvName').text(tv.name);
            $('#actionSheetTvLocation').text(tv.location || '-');
            $('#actionSheetTvPinBadge').text(`PIN: ${tv.pin}`);

            const overlay = document.getElementById('tvActionSheetModal');
            const panel   = document.getElementById('tvActionSheetPanel');
            if (!overlay || !panel) return;
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            panel.classList.remove('translate-y-full');
            panel.classList.add('translate-y-0');
        }

        function closeTvActionSheet() {
            const overlay = document.getElementById('tvActionSheetModal');
            const panel   = document.getElementById('tvActionSheetPanel');
            if (!overlay || !panel) return;
            panel.classList.remove('translate-y-0');
            panel.classList.add('translate-y-full');
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            selectedTvForActionSheet = null;
        }

        $(document).on('click', '.btnOpenTvCardActionSheet', function (e) {
            e.stopPropagation();
            const id = $(this).data('id');
            const tv = tvsData.find(t => t.id == id);
            if (tv) openTvActionSheet(tv);
        });

        $('.btnCloseTvActionSheet').on('click', closeTvActionSheet);
        $('#tvActionSheetModal').on('click', function (e) {
            if (e.target === this) closeTvActionSheet();
        });

        // Action Sheet Internal Buttons
        $('#btnSheetCopyPin').on('click', function () {
            if (!selectedTvForActionSheet) return;
            copyPinToClipboard(selectedTvForActionSheet.pin, this);
            closeTvActionSheet();
        });

        $('#btnSheetRegenPin').on('click', function () {
            if (!selectedTvForActionSheet) return;
            const tv = selectedTvForActionSheet;
            closeTvActionSheet();
            triggerRegeneratePin(tv.id, tv.name);
        });

        $('#btnSheetEditTv').on('click', function () {
            if (!selectedTvForActionSheet) return;
            const tv = selectedTvForActionSheet;
            closeTvActionSheet();
            triggerEditTv(tv.id);
        });

        $('#btnSheetDeleteTv').on('click', function () {
            if (!selectedTvForActionSheet) return;
            const tv = selectedTvForActionSheet;
            closeTvActionSheet();
            triggerDeleteTv(tv.id, tv.name);
        });

        // Open Modal Create
        $('#btnCreateTv').on('click', function () {
            $('#tvForm')[0].reset();
            $('#tvId').val('');
            $('.categoryCheckbox').prop('checked', false);
            $('#thumbnailPreviewContainer').addClass('hidden');
            $('#modalTitle').text('Tambah TV Display Baru');
            $('.text-rose-500').addClass('hidden').text('');
            $('#tvModal').removeClass('hidden');
        });

        // Close Modal
        $('.btnCloseModal').on('click', function () {
            $('#tvModal').addClass('hidden');
        });

        // Close Modal on Backdrop Click
        $('#tvModal').on('click', function (e) {
            if (e.target === this) {
                $('#tvModal').addClass('hidden');
            }
        });

        // Thumbnail Preview
        $('#tvThumbnail').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#thumbnailPreview').attr('src', e.target.result);
                    $('#thumbnailPreviewContainer').removeClass('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Open Modal Edit function
        function triggerEditTv(id) {
            $('.text-rose-500').addClass('hidden').text('');

            $.ajax({
                url: `<?= base_url('admin/tv/get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        const tv = res.data;
                        $('#tvId').val(tv.id);
                        $('#tvName').val(tv.name);
                        $('#tvLocation').val(tv.location);
                        $('#tvIsActive').prop('checked', tv.is_active == 1);

                        // Category Checkboxes
                        $('.categoryCheckbox').prop('checked', false);
                        if (tv.category_ids && tv.category_ids.length > 0) {
                            tv.category_ids.forEach(catId => {
                                $(`.categoryCheckbox[value="${catId}"]`).prop('checked', true);
                            });
                        }

                        // Thumbnail Preview
                        if (tv.thumbnail_url) {
                            $('#thumbnailPreview').attr('src', tv.thumbnail_url);
                            $('#thumbnailPreviewContainer').removeClass('hidden');
                        } else {
                            $('#thumbnailPreviewContainer').addClass('hidden');
                        }

                        $('#modalTitle').text('Edit Display TV');
                        $('#tvModal').removeClass('hidden');
                    }
                }
            });
        }

        $(document).on('click', '.btnEditTv', function () {
            const id = $(this).data('id');
            triggerEditTv(id);
        });

        // Submit Form via FormData (Multipart Upload)
        $('#tvForm').on('submit', function (e) {
            e.preventDefault();
            $('.text-rose-500').addClass('hidden').text('');

            const id = $('#tvId').val();
            const isEdit = id !== '';
            const url = isEdit ? `<?= base_url('admin/tv/update') ?>/${id}` : '<?= base_url('admin/tv/store') ?>';

            const formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#tvModal').addClass('hidden');
                        showToast('success', res.message);
                        loadTvs();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errs = xhr.responseJSON.errors;
                        for (const key in errs) {
                            $(`#err_${key}`).removeClass('hidden').text(errs[key]);
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        showToast('error', xhr.responseJSON.message);
                    }
                }
            });
        });

        // Copy TV PIN
        $(document).on('click', '.btnCopyTvPin', function () {
            const pin = $(this).data('pin');
            copyPinToClipboard(pin, this);
        });

        // Regenerate PIN Function
        function triggerRegeneratePin(id, name) {
            Swal.fire({
                title: 'Regenerate PIN?',
                text: `Apakah Anda yakin ingin memperbarui PIN 6-digit untuk "${name}"? Jika TV sedang online, TV akan dipaksa memasukkan PIN baru.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Buat PIN Baru!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('admin/tv/regenerate-pin') ?>/${id}`,
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                showToast('success', `PIN Baru ${name}: ${res.new_pin}`);
                                loadTvs();
                            }
                        }
                    });
                }
            });
        }

        $(document).on('click', '.btnRegeneratePin', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            triggerRegeneratePin(id, name);
        });

        // Delete TV Function
        function triggerDeleteTv(id, name) {
            Swal.fire({
                title: 'Hapus Display TV?',
                text: `Apakah Anda yakin ingin menghapus "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('admin/tv/delete') ?>/${id}`,
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                showToast('success', res.message);
                                loadTvs();
                            }
                        }
                    });
                }
            });
        }

        $(document).on('click', '.btnDeleteTv', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            triggerDeleteTv(id, name);
        });

        // Initial Load
        loadTvs();
    });
</script>
<?= $this->endSection() ?>
