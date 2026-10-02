<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Manajemen Konten Media</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Unggah dan kelola slideshow gambar, video, dan chart untuk penayangan TV Display.</p>
    </div>
    <div class="flex items-center space-x-2">
        <button type="button" id="btnUploadImage" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 cursor-pointer ux-hover">
            <i class="fa-solid fa-image text-xs"></i>
            <span>Upload Gambar</span>
        </button>
        <button type="button" id="btnAddVideo" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-sky-600/20 transition-all flex items-center space-x-2 cursor-pointer ux-hover">
            <i class="fa-solid fa-video text-xs"></i>
            <span>Tambah Video</span>
        </button>
        <button type="button" id="btnAddChart" class="px-4 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-amber-600/20 transition-all flex items-center space-x-2 cursor-pointer ux-hover">
            <i class="fa-solid fa-chart-column text-xs"></i>
            <span>Buat Chart</span>
        </button>
    </div>
</div>

<!-- Type Filter Tabs -->
<div class="flex items-center space-x-2 border-b border-slate-200 dark:border-slate-800 mb-6 pb-2">
    <button type="button" class="tabFilterBtn active px-4 py-2 rounded-xl text-xs font-semibold transition-all bg-blue-600/20 text-blue-600 dark:text-blue-400 border border-blue-500/30 cursor-pointer ux-hover" data-type="">
        <i class="fa-solid fa-layer-group text-xs mr-1.5"></i>Semua Media
    </button>
    <button type="button" class="tabFilterBtn px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all cursor-pointer ux-hover" data-type="image">
        <i class="fa-solid fa-image text-xs mr-1.5"></i>Gambar
    </button>
    <button type="button" class="tabFilterBtn px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all cursor-pointer ux-hover" data-type="video">
        <i class="fa-solid fa-video text-xs mr-1.5"></i>Video
    </button>
    <button type="button" class="tabFilterBtn px-4 py-2 rounded-xl text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all cursor-pointer ux-hover" data-type="chart">
        <i class="fa-solid fa-chart-column text-xs mr-1.5"></i>Chart Data
    </button>
</div>

<!-- Quick Main Page Dropzone -->
<input type="file" id="mainFileInput" multiple accept="image/jpeg,image/jpg,image/png,image/webp,video/mp4,video/webm" class="hidden">
<div id="mainDropZone" class="mb-6 p-6 border-2 border-dashed border-slate-300 dark:border-slate-700/80 rounded-2xl bg-white dark:bg-slate-900/60 hover:bg-blue-50/50 dark:hover:bg-blue-950/20 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 text-center cursor-pointer group relative overflow-hidden shadow-sm">
    <div class="flex flex-col items-center justify-center space-y-2 pointer-events-none">
        <div class="w-12 h-12 rounded-2xl bg-blue-600/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
            <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
        </div>
        <div>
            <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                Tarik & Lepas file media di sini untuk <span class="text-blue-600 dark:text-blue-400 underline font-semibold">Upload Cepat</span>
            </p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                Mendukung upload banyak file sekaligus (Gambar: JPG, PNG, WEBP max 10MB | Video: MP4, WEBM max 100MB)
            </p>
        </div>
        <div class="flex items-center space-x-3 text-[11px] text-slate-500 dark:text-slate-400 pt-1">
            <span class="inline-flex items-center space-x-1"><i class="fa-solid fa-circle-info text-blue-500"></i><span>Format: JPG, PNG, WEBP, MP4</span></span>
            <span>•</span>
            <span class="inline-flex items-center space-x-1"><i class="fa-solid fa-layer-group text-emerald-500"></i><span>Mendukung Batch Multiple Files</span></span>
        </div>
    </div>
</div>

<!-- Main Table Card & Mobile Card View Container -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-4 sm:p-6 shadow-xs relative">
    
    <!-- Sticky Search & Filter Toolbar (sticky top-0 z-20 on mobile) -->
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
                <input type="text" id="searchInput" placeholder="Cari judul konten..." 
                    class="w-full pl-9 pr-8 py-2.5 sm:py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-hidden focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                <button type="button" id="btnClearSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hidden" title="Hapus Pencarian">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Mobile Filter & Sort Button (< md) -->
            <button type="button" id="btnOpenContentFilterSort" class="md:hidden relative px-3 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 flex items-center space-x-1.5 active:scale-95 transition-all cursor-pointer">
                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span>Filter</span>
                <span id="contentFilterBadge" class="hidden w-2 h-2 rounded-full bg-blue-600 ring-2 ring-white dark:ring-slate-900"></span>
            </button>

            <!-- Desktop Filter Controls (>= md) -->
            <div class="hidden md:flex items-center space-x-3">
                <select id="categoryFilter" class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 px-3 py-2 focus:outline-hidden focus:border-blue-500 transition-all cursor-pointer">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>

                <!-- Status Filter Pills -->
                <div class="flex items-center bg-slate-100 dark:bg-slate-950 p-1 rounded-xl border border-slate-200/80 dark:border-slate-800 text-xs">
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-semibold transition-all bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs cursor-pointer" data-status="all">Semua</button>
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all cursor-pointer" data-status="active">Aktif</button>
                    <button type="button" class="btnDesktopStatusFilter px-3 py-1 rounded-lg font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-all cursor-pointer" data-status="inactive">Nonaktif</button>
                </div>

                <!-- Desktop Sort Select -->
                <select id="desktopContentSort" class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 px-3 py-2 focus:outline-hidden focus:border-blue-500 transition-all cursor-pointer">
                    <option value="newest">Urut: Terbaru</option>
                    <option value="oldest">Urut: Terlama</option>
                    <option value="title_asc">Judul: A - Z</option>
                    <option value="title_desc">Judul: Z - A</option>
                    <option value="duration_desc">Durasi: Terpanjang</option>
                </select>
            </div>
        </div>

        <!-- Active Filter Badges Row -->
        <div id="activeContentFilterTags" class="flex flex-wrap items-center gap-1.5 pt-2 hidden">
            <!-- Dynamic chips rendered by JS -->
        </div>
    </div>

    <!-- Mobile Responsive Card View (< md) -->
    <div id="contentMobileCardContainer" class="md:hidden space-y-3">
        <!-- Rendered dynamically by renderTable() -->
        <div class="py-12 text-center text-slate-400 dark:text-slate-500">
            <svg class="w-8 h-8 animate-spin mx-auto mb-2 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
                <path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="0.8"></path>
            </svg>
            <span class="text-xs">Memuat data konten media...</span>
        </div>
    </div>

    <!-- Desktop Dense Data Table (>= md) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse" id="contentTable">
            <thead>
                <tr class="border-b border-slate-200/80 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-4">Konten Media</th>
                    <th class="py-3.5 px-4">Tipe & Sumber</th>
                    <th class="py-3.5 px-4">Kategori</th>
                    <th class="py-3.5 px-4">Durasi Tayang</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200/80 dark:divide-slate-800/50 text-xs" id="contentTableBody">
                <!-- Loading State -->
                <tr>
                    <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-spinner fa-spin text-lg mb-2 block"></i>
                        <span>Memuat data konten...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Summary Count Footer -->
    <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between text-xs text-slate-400 dark:text-slate-500">
        <span id="contentTotalCount">Menampilkan 0 konten media</span>
        <span class="flex items-center space-x-1.5 text-blue-600 dark:text-blue-400 font-semibold" id="contentActiveCount">
            0 Aktif
        </span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Modal / Bottom Sheet Drawer Form Gambar (Upload / Edit) -->
<div id="imageModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-lg p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-5 shrink-0">
            <h3 class="text-base font-bold text-slate-800 dark:text-white" id="imageModalTitle">Upload Konten Gambar Baru</h3>
            <button type="button" class="btnCloseModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="imageForm" enctype="multipart/form-data" class="flex-1 overflow-y-auto pr-1">
            <input type="hidden" id="imageId" name="id">
            <div class="space-y-4">
                <div>
                    <label for="imageTitle" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Konten</label>
                    <input type="text" id="imageTitle" name="title" required placeholder="Contoh: Poster Jadwal"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_title"></p>
                </div>

                <div>
                    <label for="imageCategory" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kategori Konten</label>
                    <select id="imageCategory" name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_category_id"></p>
                </div>

                <div>
                    <label for="imageDuration" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Durasi Tayang per Slide (Detik)</label>
                    <div class="relative">
                        <input type="number" id="imageDuration" name="display_duration_seconds" value="10" min="3" max="300" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs font-semibold">detik</div>
                    </div>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_display_duration_seconds"></p>
                </div>
                <div>
                    <label for="imageFile" class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                        <span>File Gambar <span id="imageFileHelp" class="text-slate-400 dark:text-slate-500 font-normal text-[10px]">(JPG, PNG, WEBP, Maks 10MB)</span></span>
                        <span id="imageSelectedFileInfo" class="text-[10px] text-blue-600 dark:text-blue-400 font-mono font-semibold hidden"></span>
                    </label>
                    <input type="file" id="imageFile" name="image_file" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden">
                    <div id="imageDropZone" class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-4 text-center cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 hover:bg-blue-50/30 dark:hover:bg-blue-950/20 transition-all">
                        <div class="flex flex-col items-center justify-center space-y-1">
                            <i class="fa-solid fa-cloud-arrow-up text-blue-500 text-lg"></i>
                            <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Tarik & lepas gambar di sini, atau <span class="text-blue-600 dark:text-blue-400 underline font-semibold">pilih berkas</span></p>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500" id="imageDropZoneFileInfo">Format JPG, PNG, WEBP (Maks 1 file • Max 10MB)</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_image_file"></p>

                    <div id="imagePreviewContainer" class="mt-3 hidden relative group">
                        <img id="imagePreview" src="" alt="Preview" class="w-full h-36 object-cover rounded-xl border border-slate-200 dark:border-slate-800">
                        <button type="button" id="btnRemoveImagePreview" class="absolute top-2 right-2 bg-slate-900/80 hover:bg-rose-600 text-white rounded-lg p-1.5 text-xs transition-colors shadow cursor-pointer" title="Hapus Gambar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="imageIsActive" name="is_active" value="1" checked
                        class="w-4 h-4 rounded bg-slate-100 dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-blue-600 focus:ring-blue-500">
                    <label for="imageIsActive" class="text-xs text-slate-700 dark:text-slate-300 font-medium">Aktifkan Konten</label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer ux-hover">Batal</button>
                <button type="submit" id="btnSaveImage" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg transition-all flex items-center space-x-2 cursor-pointer ux-hover">
                    <i class="fa-solid fa-floppy-disk text-xs"></i><span>Simpan Konten</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal / Bottom Sheet Drawer Form Video (Upload / Embed YouTube) -->
<div id="videoModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-lg p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-5 shrink-0">
            <h3 class="text-base font-bold text-slate-800 dark:text-white" id="videoModalTitle">Tambah Konten Video</h3>
            <button type="button" class="btnCloseModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="videoForm" enctype="multipart/form-data" class="flex-1 overflow-y-auto pr-1">
            <input type="hidden" id="videoId" name="id">

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Sumber Video</label>
                    <div class="grid grid-cols-2 gap-2 bg-slate-100 dark:bg-slate-950 p-1 rounded-xl border border-slate-200 dark:border-slate-800">
                        <button type="button" id="srcUploadBtn" class="py-2 text-xs font-semibold rounded-lg bg-blue-600 text-white transition-all cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>Upload File MP4
                        </button>
                        <button type="button" id="srcYoutubeBtn" class="py-2 text-xs font-semibold rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all cursor-pointer">
                            <i class="fa-brands fa-youtube mr-1.5"></i>YouTube Embed
                        </button>
                    </div>
                    <input type="hidden" id="videoSource" name="video_source" value="upload">
                </div>

                <div>
                    <label for="videoTitle" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Video</label>
                    <input type="text" id="videoTitle" name="title" required placeholder="Contoh: Video Profil OSS"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_v_title"></p>
                </div>

                <div>
                    <label for="videoCategory" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kategori Konten</label>
                    <select id="videoCategory" name="category_id" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_v_category_id"></p>
                </div>

                <!-- Section Source: Upload -->
                <div id="sectionUploadVideo" class="space-y-4">
                    <div>
                        <label for="videoFile" class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 flex items-center justify-between">
                            <span>Upload File Video <span class="text-slate-400 dark:text-slate-500 font-normal text-[10px]">(MP4, WEBM, Maks 100MB)</span></span>
                            <span id="videoSelectedFileInfo" class="text-[10px] text-sky-600 dark:text-sky-400 font-mono font-semibold hidden"></span>
                        </label>
                        <input type="file" id="videoFile" name="video_file" accept="video/mp4,video/webm" class="hidden">
                        <div id="videoDropZone" class="border-2 border-dashed border-slate-300 dark:border-slate-700 rounded-xl p-4 text-center cursor-pointer hover:border-sky-500 dark:hover:border-sky-500 hover:bg-sky-50/30 dark:hover:bg-sky-950/20 transition-all">
                            <div class="flex flex-col items-center justify-center space-y-1">
                                <i class="fa-solid fa-file-video text-sky-500 text-lg"></i>
                                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">Tarik & lepas file video di sini, atau <span class="text-sky-600 dark:text-sky-400 underline font-semibold">pilih berkas</span></p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500" id="videoDropZoneFileInfo">Format MP4, WEBM (Maks 1 file • Max 100MB)</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_v_video_file"></p>
                    </div>
                </div>

                <!-- Section Source: YouTube -->
                <div id="sectionYoutubeVideo" class="space-y-4 hidden">
                    <div>
                        <label for="youtubeUrl" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">URL / Link YouTube</label>
                        <input type="text" id="youtubeUrl" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..."
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                        <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_v_youtube_url"></p>
                    </div>

                    <div>
                        <label for="videoDuration" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Durasi Tayang Video (Detik)</label>
                        <div class="relative">
                            <input type="number" id="videoDuration" name="video_duration_seconds" value="30" min="5" max="3600"
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs font-semibold">detik</div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="videoIsActive" name="is_active" value="1" checked
                        class="w-4 h-4 rounded bg-slate-100 dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-blue-600 focus:ring-blue-500">
                    <label for="videoIsActive" class="text-xs text-slate-700 dark:text-slate-300 font-medium">Aktifkan Konten</label>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer ux-hover">Batal</button>
                <button type="submit" id="btnSaveVideo" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg transition-all flex items-center space-x-2 cursor-pointer ux-hover">
                    <i class="fa-solid fa-floppy-disk text-xs"></i><span>Simpan Video</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal / Bottom Sheet Drawer Form Chart (Chart.js Config) -->
<div id="chartModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-3xl p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-5 shrink-0">
            <h3 class="text-base font-bold text-slate-800 dark:text-white" id="chartModalTitle">Buat Konten Chart Data Baru</h3>
            <button type="button" class="btnCloseModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="chartForm" class="flex-1 overflow-y-auto pr-1">
            <input type="hidden" id="chartContentId" name="id">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column: Form Configuration -->
                <div class="space-y-4">
                    <div>
                        <label for="chartTitle" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Judul Chart</label>
                        <input type="text" id="chartTitle" name="title" required placeholder="Contoh: Grafik Capaian Kinerja Q3"
                            class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                        <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_c_title"></p>
                    </div>

                    <div>
                        <label for="chartCategory" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kategori Konten</label>
                        <select id="chartCategory" name="category_id" required class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_c_category_id"></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Visual Chart</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" class="btnChartType active py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-semibold bg-amber-600 text-white transition-all cursor-pointer flex items-center justify-center space-x-1.5" data-type="bar">
                                <i class="fa-solid fa-chart-column text-xs"></i><span>Bar</span>
                            </button>
                            <button type="button" class="btnChartType py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all cursor-pointer flex items-center justify-center space-x-1.5" data-type="line">
                                <i class="fa-solid fa-chart-line text-xs"></i><span>Line</span>
                            </button>
                            <button type="button" class="btnChartType py-2 px-3 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-all cursor-pointer flex items-center justify-center space-x-1.5" data-type="pie">
                                <i class="fa-solid fa-chart-pie text-xs"></i><span>Pie</span>
                            </button>
                        </div>
                        <input type="hidden" id="chartTypeInput" name="chart_type" value="bar">
                    </div>

                    <div>
                        <label for="chartDuration" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Durasi Tayang (Detik)</label>
                        <div class="relative">
                            <input type="number" id="chartDuration" name="display_duration_seconds" value="15" min="5" max="300" required
                                class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-all">
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500 text-xs font-semibold">detik</div>
                        </div>
                    </div>

                    <!-- Dataset Configuration -->
                    <div class="p-3 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl space-y-3">
                        <div>
                            <label for="datasetLabel" class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Label Dataset / Keterangan Data</label>
                            <input type="text" id="datasetLabel" name="dataset_label" value="Target vs Capaian" required
                                class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500">
                        </div>

                        <div>
                            <label for="datasetColor" class="block text-[11px] font-semibold text-slate-700 dark:text-slate-300 mb-1">Warna Utama Chart</label>
                            <div class="flex items-center space-x-2">
                                <input type="color" id="datasetColorPicker" value="#3b82f6" class="w-7 h-7 p-0 bg-transparent border-0 cursor-pointer rounded">
                                <input type="text" id="datasetColor" name="dataset_color" value="#3b82f6" class="flex-1 px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-mono text-slate-800 dark:text-slate-100 uppercase">
                            </div>
                        </div>
                    </div>

                    <!-- Chart Rows (Labels & Values) -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Data Baris (Label & Nilai)</label>
                            <button type="button" id="btnAddChartRow" class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                                + Tambah Baris
                            </button>
                        </div>

                        <div id="chartRowsContainer" class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            <div class="chartRow flex items-center space-x-2">
                                <input type="text" name="labels[]" value="Januari" placeholder="Label" class="chartLabelInput flex-1 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                                <input type="number" step="any" name="values[]" value="75" placeholder="Nilai" class="chartValueInput w-24 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                                <button type="button" class="btnRemoveRow text-slate-400 hover:text-rose-500 p-1.5 cursor-pointer"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </div>
                            <div class="chartRow flex items-center space-x-2">
                                <input type="text" name="labels[]" value="Februari" placeholder="Label" class="chartLabelInput flex-1 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                                <input type="number" step="any" name="values[]" value="90" placeholder="Nilai" class="chartValueInput w-24 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                                <button type="button" class="btnRemoveRow text-slate-400 hover:text-rose-500 p-1.5 cursor-pointer"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="checkbox" id="chartIsActive" name="is_active" value="1" checked
                            class="w-4 h-4 rounded bg-slate-100 dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-blue-600 focus:ring-blue-500">
                        <label for="chartIsActive" class="text-xs text-slate-700 dark:text-slate-300 font-medium">Aktifkan Konten</label>
                    </div>
                </div>

                <!-- Right Column: Live Chart Preview -->
                <div class="flex flex-col justify-between bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800 p-4 rounded-xl">
                    <div>
                        <h4 class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 flex items-center justify-between">
                            <span>Live Canvas Preview</span>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">Real-time</span>
                        </h4>
                        <div class="h-64 relative bg-white dark:bg-slate-900 rounded-lg p-2 border border-slate-200 dark:border-slate-800 flex items-center justify-center">
                            <canvas id="previewChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer ux-hover">Batal</button>
                <button type="submit" id="btnSaveChart" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg transition-all flex items-center space-x-2 cursor-pointer ux-hover">
                    <i class="fa-solid fa-floppy-disk text-xs"></i><span>Simpan Chart</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal / Bottom Sheet Drawer Batch Upload Confirmation -->
<div id="batchUploadModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-3xl p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-4 shrink-0">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Konfirmasi Batch Upload Media</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Periksa dan sesuaikan judul, kategori, serta durasi tayang sebelum mengunggah sekaligus.</p>
            </div>
            <button type="button" class="btnCloseBatchModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Summary & Stats Bar -->
        <div class="flex items-center justify-between bg-blue-50/60 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800/50 rounded-xl px-4 py-2.5 mb-4 shrink-0">
            <div class="flex items-center space-x-2 text-xs font-semibold text-blue-700 dark:text-blue-300">
                <i class="fa-solid fa-folder-open text-blue-500"></i>
                <span id="batchSummaryText">0 Berkas Terpilih</span>
            </div>
            <div id="batchTotalSizeBadge" class="text-[11px] font-mono font-bold px-2.5 py-0.5 rounded-full bg-blue-600/10 dark:bg-blue-400/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                Total Ukuran: 0 MB
            </div>
        </div>

        <!-- Bulk Set Controls -->
        <div class="p-3.5 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800 rounded-xl mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shrink-0">
            <div class="flex items-center space-x-2 text-xs font-semibold text-slate-700 dark:text-slate-300">
                <i class="fa-solid fa-sliders text-blue-500"></i>
                <span>Atur Serentak:</span>
            </div>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <select id="bulkCategorySelect" class="px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500 flex-1 sm:flex-initial">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="relative w-28">
                    <input type="number" id="bulkDurationInput" placeholder="Durasi" value="10" min="3" max="300" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500">
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none text-[10px] text-slate-400 font-semibold">dtk</div>
                </div>
                <button type="button" id="btnApplyBulk" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600 text-white rounded-lg text-xs font-semibold transition-all cursor-pointer ux-hover">
                    Terapkan
                </button>
            </div>
        </div>

        <form id="batchForm" enctype="multipart/form-data" class="flex-1 overflow-y-auto pr-1 flex flex-col">
            <div id="batchItemsContainer" class="space-y-3 flex-1 overflow-y-auto pr-1">
                <!-- Batch items dynamically rendered here -->
            </div>

            <div class="flex items-center justify-between pt-5 mt-5 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseBatchModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 cursor-pointer ux-hover">Batal</button>
                <button type="submit" id="btnSubmitBatch" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 cursor-pointer ux-hover">
                    <i class="fa-solid fa-cloud-arrow-up text-xs"></i><span id="btnSubmitBatchText">Unggah Semua Konten</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- =========================================================================
     Filter & Sorting Bottom Sheet Drawer for Media Content (Mobile)
     ========================================================================= -->
<div id="contentFilterSortModal" class="fixed inset-0 z-60 bg-slate-950/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-250 flex flex-col justify-end md:hidden">
    <div class="w-full max-w-lg mx-auto p-4">
        <div id="contentFilterSortPanel" class="w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 shadow-2xl translate-y-full transition-transform duration-300 ease-out max-h-[85vh] flex flex-col">
            <!-- Drag Handle -->
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 shrink-0"></div>

            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-4 shrink-0">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white">Filter & Urutan Konten</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pilih kriteria filter media slideshow</p>
                </div>
                <button type="button" class="btnCloseContentFilterModal p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl" title="Tutup">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto space-y-4 pr-1">
                <!-- Tipe Media Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Tipe Konten Media</label>
                    <div class="grid grid-cols-2 gap-2" id="mobileFilterTypeGroup">
                        <button type="button" class="btnMobileTypeChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30" data-type="">
                            Semua Tipe
                        </button>
                        <button type="button" class="btnMobileTypeChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-type="image">
                            <i class="fa-solid fa-image mr-1"></i>Gambar
                        </button>
                        <button type="button" class="btnMobileTypeChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-type="video">
                            <i class="fa-solid fa-video mr-1"></i>Video
                        </button>
                        <button type="button" class="btnMobileTypeChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-type="chart">
                            <i class="fa-solid fa-chart-column mr-1"></i>Chart Data
                        </button>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Status Penayangan</label>
                    <div class="grid grid-cols-3 gap-2" id="mobileFilterContentStatusGroup">
                        <button type="button" class="btnMobileContentStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30" data-status="all">
                            Semua
                        </button>
                        <button type="button" class="btnMobileContentStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-status="active">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>Aktif
                        </button>
                        <button type="button" class="btnMobileContentStatusChip px-3 py-2 rounded-xl text-xs font-semibold border transition-all text-center cursor-pointer bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700" data-status="inactive">
                            Nonaktif
                        </button>
                    </div>
                </div>

                <!-- Kategori Filter -->
                <div>
                    <label for="mobileContentCategoryFilter" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Filter Kategori</label>
                    <select id="mobileContentCategoryFilter" class="w-full bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 px-3.5 py-2.5 focus:outline-hidden focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Sort Options -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2">Urutan Tampilan</label>
                    <div class="space-y-1.5" id="mobileContentSortGroup">
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Terbaru Ditambahkan</span>
                            <input type="radio" name="mobileContentSort" value="newest" checked class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Judul: A - Z</span>
                            <input type="radio" name="mobileContentSort" value="title_asc" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Judul: Z - A</span>
                            <input type="radio" name="mobileContentSort" value="title_desc" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Durasi: Terpanjang</span>
                            <input type="radio" name="mobileContentSort" value="duration_desc" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                        <label class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 cursor-pointer">
                            <span class="text-xs font-medium text-slate-800 dark:text-slate-200">Terlama Ditambahkan</span>
                            <input type="radio" name="mobileContentSort" value="oldest" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-800 flex items-center gap-3 shrink-0">
                <button type="button" id="btnResetMobileContentFilter" class="flex-1 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs text-center cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Reset
                </button>
                <button type="button" id="btnApplyMobileContentFilter" class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs text-center shadow-lg shadow-blue-500/20 cursor-pointer transition-colors">
                    Terapkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     Card Action Sheet Drawer for Selected Media (Mobile)
     ========================================================================= -->
<div id="contentActionSheetModal" class="fixed inset-0 z-60 bg-slate-950/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-250 flex flex-col justify-end md:hidden">
    <div class="w-full max-w-lg mx-auto p-4">
        <div id="contentActionSheetPanel" class="w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 shadow-2xl translate-y-full transition-transform duration-300 ease-out">
            <!-- Drag Handle -->
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 shrink-0"></div>

            <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800 mb-3">
                <div class="overflow-hidden pr-2">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate" id="sheetContentTitle">Konten Media</h3>
                    <p class="text-[11px] text-slate-400 truncate" id="sheetContentSubtitle">-</p>
                </div>
                <button type="button" class="btnCloseContentActionSheet p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl" title="Tutup">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Action List (>= 44px touch targets) -->
            <div class="space-y-1.5">
                <!-- Preview Action -->
                <button type="button" id="btnSheetPreviewContent" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Preview Konten</p>
                        <span class="text-[10px] text-slate-400">Tampilkan preview slideshow</span>
                    </div>
                </button>

                <!-- Edit Action -->
                <button type="button" id="btnSheetEditContent" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Edit Data Konten</p>
                        <span class="text-[10px] text-slate-400">Ubah judul, kategori, durasi</span>
                    </div>
                </button>

                <!-- Toggle Status Action -->
                <button type="button" id="btnSheetToggleStatus" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 text-xs font-semibold text-slate-800 dark:text-slate-200 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i class="fa-solid fa-power-off text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p id="sheetToggleStatusText">Ubah Status</p>
                        <span class="text-[10px] text-slate-400">Aktifkan atau nonaktifkan dari playlist</span>
                    </div>
                </button>

                <!-- Delete Action (Danger) -->
                <button type="button" id="btnSheetDeleteContent" class="w-full min-h-[44px] flex items-center space-x-3 px-3.5 py-2.5 rounded-xl hover:bg-rose-500/10 text-xs font-semibold text-rose-600 dark:text-rose-400 transition-colors cursor-pointer">
                    <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </div>
                    <div class="text-left flex-1">
                        <p>Hapus Konten</p>
                        <span class="text-[10px] text-rose-500/80">Hapus file dan data ini secara permanen</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let contentsData = [];
        let currentTypeFilter = '';
        let previewChartInstance = null;

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

        let currentStatusFilter = 'all';
        let currentSort = 'newest';
        let selectedContentForActionSheet = null;

        // Fetch Content List
        function loadContents() {
            const categoryId = $('#categoryFilter').val();
            const search = $('#searchInput').val();

            $.ajax({
                url: '<?= base_url('admin/content/list') ?>',
                type: 'GET',
                data: {
                    type: currentTypeFilter,
                    category_id: categoryId,
                    search: search
                },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        contentsData = res.data;
                        renderTable();
                    }
                },
                error: function () {
                    $('#contentTableBody').html(`
                        <tr>
                            <td colspan="6" class="py-8 text-center text-rose-500">
                                Gagal memuat data konten media.
                            </td>
                        </tr>
                    `);
                    $('#contentMobileCardContainer').html(`
                        <div class="py-8 text-center text-rose-500 text-xs">
                            Gagal memuat data konten media.
                        </div>
                    `);
                }
            });
        }

        // Render Table Rows & Mobile Card Stack
        function renderTable() {
            const search = ($('#searchInput').val() || '').toLowerCase();
            const categoryId = $('#categoryFilter').val();

            // 1. In-memory Filter
            let filtered = contentsData.filter(cnt => {
                const titleMatch = (cnt.title || '').toLowerCase().includes(search);
                const fileMatch = (cnt.file_name || '').toLowerCase().includes(search);
                const catMatch = (cnt.category_name || '').toLowerCase().includes(search);
                const matchesSearch = titleMatch || fileMatch || catMatch;

                if (!matchesSearch) return false;

                // Category filter check (if loaded all)
                if (categoryId && cnt.category_id != categoryId) {
                    return false;
                }

                // Status filter check
                if (currentStatusFilter === 'active') {
                    return cnt.is_active == 1;
                } else if (currentStatusFilter === 'inactive') {
                    return cnt.is_active != 1;
                }

                return true;
            });

            // 2. In-memory Sorting
            filtered.sort((a, b) => {
                if (currentSort === 'newest') {
                    return new Date(b.created_at || 0) - new Date(a.created_at || 0);
                } else if (currentSort === 'oldest') {
                    return new Date(a.created_at || 0) - new Date(b.created_at || 0);
                } else if (currentSort === 'title_asc') {
                    return (a.title || '').localeCompare(b.title || '');
                } else if (currentSort === 'title_desc') {
                    return (b.title || '').localeCompare(a.title || '');
                } else if (currentSort === 'duration_desc') {
                    const durA = parseInt(a.video_duration_seconds || a.display_duration_seconds || 0);
                    const durB = parseInt(b.video_duration_seconds || b.display_duration_seconds || 0);
                    return durB - durA;
                }
                return 0;
            });

            // 3. Update Counters & Active Tags
            const activeCount = filtered.filter(c => c.is_active == 1).length;
            $('#contentTotalCount').text(`Menampilkan ${filtered.length} dari ${contentsData.length} konten media`);
            $('#contentActiveCount').text(`${activeCount} Aktif`);

            const isFiltered = currentStatusFilter !== 'all' || currentSort !== 'newest' || search !== '' || currentTypeFilter !== '' || categoryId !== '';
            if (isFiltered) {
                $('#contentFilterBadge').removeClass('hidden');
                renderActiveContentFilterTags(search, categoryId);
            } else {
                $('#contentFilterBadge').addClass('hidden');
                $('#activeContentFilterTags').addClass('hidden').html('');
            }

            if (search.length > 0) {
                $('#btnClearSearch').removeClass('hidden');
            } else {
                $('#btnClearSearch').addClass('hidden');
            }

            if (filtered.length === 0) {
                $('#contentTableBody').html(`
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500">
                            Tidak ditemukan data konten media yang cocok.
                        </td>
                    </tr>
                `);
                $('#contentMobileCardContainer').html(`
                    <div class="py-12 text-center text-slate-400 dark:text-slate-500 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl p-6">
                        <i class="fa-solid fa-photo-film text-2xl mb-2 block"></i>
                        <p class="text-xs font-semibold">Tidak ditemukan konten media</p>
                        <p class="text-[11px] text-slate-400 mt-1">Coba sesuaikan kata kunci pencarian atau ganti filter.</p>
                    </div>
                `);
                return;
            }

            let tableHtml = '';
            let cardHtml = '';

            filtered.forEach(cnt => {
                let thumbnailHtml = '';
                let mobileThumbHtml = '';

                if (cnt.type === 'image') {
                    thumbnailHtml = cnt.file_url 
                        ? `<img src="${cnt.file_url}" class="w-12 h-9 object-cover rounded-lg border border-slate-200 dark:border-slate-700 shrink-0">`
                        : `<div class="w-12 h-9 rounded-lg bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-image text-xs"></i></div>`;
                    mobileThumbHtml = cnt.file_url
                        ? `<img src="${cnt.file_url}" class="w-14 h-12 object-cover rounded-xl border border-slate-200 dark:border-slate-700 shrink-0">`
                        : `<div class="w-14 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 shrink-0"><i class="fa-solid fa-image text-sm"></i></div>`;
                } else if (cnt.type === 'video') {
                    thumbnailHtml = `<div class="w-12 h-9 rounded-lg bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0"><i class="fa-solid fa-play text-xs"></i></div>`;
                    mobileThumbHtml = `<div class="w-14 h-12 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0"><i class="fa-solid fa-play text-base"></i></div>`;
                } else if (cnt.type === 'chart') {
                    thumbnailHtml = `<div class="w-12 h-9 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0"><i class="fa-solid fa-chart-column text-xs"></i></div>`;
                    mobileThumbHtml = `<div class="w-14 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0"><i class="fa-solid fa-chart-column text-base"></i></div>`;
                }

                let typeBadge = '';
                if (cnt.type === 'image') {
                    typeBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">GAMBAR</span>`;
                } else if (cnt.type === 'video') {
                    const srcInfo = cnt.video_source === 'youtube' ? 'YOUTUBE' : 'FILE MP4';
                    typeBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">VIDEO (${srcInfo})</span>`;
                } else if (cnt.type === 'chart') {
                    typeBadge = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">CHART (${(cnt.chart_type || 'BAR').toUpperCase()})</span>`;
                }

                let categoryBadge = `<span class="text-slate-400 dark:text-slate-500 italic text-[11px]">Tanpa Kategori</span>`;
                if (cnt.category_name) {
                    const color = cnt.category_color || '#6366f1';
                    categoryBadge = `
                        <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[10px] font-semibold border"
                              style="background-color: ${color}15; color: ${color}; border-color: ${color}30;">
                            <span class="w-1.5 h-1.5 rounded-full" style="background-color: ${color};"></span>
                            <span>${cnt.category_name}</span>
                        </span>
                    `;
                }

                const statusBadge = cnt.is_active == 1
                    ? `<button type="button" class="btnToggleStatus inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all cursor-pointer ux-hover" data-id="${cnt.id}" title="Ubah status">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span><span>Aktif</span>
                       </button>`
                    : `<button type="button" class="btnToggleStatus inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-all cursor-pointer ux-hover" data-id="${cnt.id}" title="Ubah status">
                        <span>Nonaktif</span>
                       </button>`;

                const duration = cnt.type === 'video' && cnt.video_duration_seconds ? cnt.video_duration_seconds : cnt.display_duration_seconds;
                const durationPill = `<span class="font-mono text-slate-700 dark:text-slate-300 font-semibold">${duration}s</span>`;

                // --- 1. Desktop Table Row ---
                tableHtml += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-3">
                                ${thumbnailHtml}
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-200 line-clamp-1">${cnt.title}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500">${cnt.file_name || cnt.youtube_url || 'Data JSON'}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">${typeBadge}</td>
                        <td class="py-3.5 px-4">${categoryBadge}</td>
                        <td class="py-3.5 px-4">${durationPill}</td>
                        <td class="py-3.5 px-4">${statusBadge}</td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            ${cnt.type === 'image' ? `
                                <button type="button" class="btnEditContent p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-500/10 rounded-lg cursor-pointer ux-hover" data-id="${cnt.id}" title="Edit Gambar">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                            ` : ''}
                            ${cnt.type === 'video' ? `
                                <button type="button" class="btnEditVideo p-2 text-slate-400 hover:text-sky-600 dark:hover:text-sky-400 hover:bg-sky-500/10 rounded-lg cursor-pointer ux-hover" data-id="${cnt.id}" title="Edit Video">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                            ` : ''}
                            ${cnt.type === 'chart' ? `
                                <button type="button" class="btnEditChart p-2 text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-amber-500/10 rounded-lg cursor-pointer ux-hover" data-id="${cnt.id}" title="Edit Chart">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                            ` : ''}
                            <button type="button" class="btnDeleteContent p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg cursor-pointer ux-hover" data-id="${cnt.id}" data-title="${cnt.title}" title="Hapus Konten">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </td>
                    </tr>
                `;

                // --- 2. Mobile Responsive Card (Touch Targets >= 44px) ---
                cardHtml += `
                    <div class="mobileContentCard p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-xs space-y-3 transition-all active:scale-[0.99]" data-id="${cnt.id}">
                        <!-- Card Top: Thumbnail + Title & Subtitle + Status -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3 overflow-hidden flex-1">
                                ${mobileThumbHtml}
                                <div class="overflow-hidden">
                                    <h4 class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm line-clamp-1">${cnt.title}</h4>
                                    <p class="text-[10px] text-slate-400 truncate mt-0.5">${cnt.file_name || cnt.youtube_url || 'Data JSON'}</p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                ${statusBadge}
                            </div>
                        </div>

                        <!-- Card Mid: Badges & Duration -->
                        <div class="p-2.5 rounded-xl bg-slate-50/80 dark:bg-slate-950/60 border border-slate-200/60 dark:border-slate-800/60 flex items-center justify-between text-xs">
                            <div class="flex flex-wrap items-center gap-1.5">
                                ${typeBadge}
                                ${categoryBadge}
                            </div>
                            <div class="shrink-0 flex items-center space-x-1 text-slate-500 dark:text-slate-400 text-xs">
                                <i class="fa-regular fa-clock text-[10px]"></i>
                                ${durationPill}
                            </div>
                        </div>

                        <!-- Card Bottom: Touch Action Bar (Min 44x44px touch targets) -->
                        <div class="pt-2 border-t border-slate-100 dark:border-slate-800/80 flex items-center justify-between gap-2">
                            <div class="flex items-center space-x-2 flex-1">
                                <!-- Preview Quick Action -->
                                <button type="button" class="btnPreviewMobile min-h-[44px] flex-1 px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 rounded-xl text-xs font-semibold flex items-center justify-center space-x-1.5 active:scale-95 transition-all" data-id="${cnt.id}">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                    <span>Preview</span>
                                </button>

                                <!-- Edit Quick Action -->
                                <button type="button" class="btnEditMobile min-h-[44px] px-3.5 py-2 bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 rounded-xl text-xs font-semibold flex items-center justify-center active:scale-95 transition-all" data-id="${cnt.id}" data-type="${cnt.type}">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>

                                <!-- Delete Quick Action -->
                                <button type="button" class="btnDeleteContent min-h-[44px] px-3.5 py-2 bg-rose-500/10 text-rose-600 dark:text-rose-400 hover:bg-rose-500/20 rounded-xl text-xs font-semibold flex items-center justify-center active:scale-95 transition-all" data-id="${cnt.id}" data-title="${cnt.title}" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>

                            <!-- 3-Dots Action Sheet Trigger -->
                            <button type="button" class="btnOpenContentCardActionSheet min-w-[44px] min-h-[44px] flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 active:scale-90 transition-all" data-id="${cnt.id}" title="Opsi Konten">
                                <i class="fa-solid fa-ellipsis-vertical text-sm"></i>
                            </button>
                        </div>
                    </div>
                `;
            });

            $('#contentTableBody').html(tableHtml);
            $('#contentMobileCardContainer').html(cardHtml);
        }

        // Active Filter Chips for Content
        function renderActiveContentFilterTags(search, categoryId) {
            let tags = '';

            if (search) {
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                        <span>Cari: "${search}"</span>
                        <button type="button" class="btnClearSpecificContentFilter ml-1 hover:text-blue-800 cursor-pointer" data-type="search">✕</button>
                    </span>
                `;
            }

            if (currentTypeFilter) {
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">
                        <span>Tipe: ${currentTypeFilter.toUpperCase()}</span>
                        <button type="button" class="btnClearSpecificContentFilter ml-1 hover:text-sky-800 cursor-pointer" data-type="type">✕</button>
                    </span>
                `;
            }

            if (currentStatusFilter !== 'all') {
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        <span>Status: ${currentStatusFilter.toUpperCase()}</span>
                        <button type="button" class="btnClearSpecificContentFilter ml-1 hover:text-emerald-800 cursor-pointer" data-type="status">✕</button>
                    </span>
                `;
            }

            if (currentSort !== 'newest') {
                const sortLabels = {
                    oldest: 'Terlama',
                    title_asc: 'Judul A-Z',
                    title_desc: 'Judul Z-A',
                    duration_desc: 'Durasi Terpanjang'
                };
                tags += `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">
                        <span>Urut: ${sortLabels[currentSort] || currentSort}</span>
                        <button type="button" class="btnClearSpecificContentFilter ml-1 hover:text-indigo-800 cursor-pointer" data-type="sort">✕</button>
                    </span>
                `;
            }

            if (tags) {
                $('#activeContentFilterTags').removeClass('hidden').html(tags);
            } else {
                $('#activeContentFilterTags').addClass('hidden').html('');
            }
        }

        // Clear Specific Filter
        $(document).on('click', '.btnClearSpecificContentFilter', function () {
            const type = $(this).data('type');
            if (type === 'search') {
                $('#searchInput').val('');
            } else if (type === 'type') {
                currentTypeFilter = '';
                $('.tabFilterBtn').removeClass('active bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30')
                                 .addClass('text-slate-500 dark:text-slate-400');
                $('.tabFilterBtn[data-type=""]').addClass('active bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30');
            } else if (type === 'status') {
                currentStatusFilter = 'all';
                updateContentStatusFilterUI();
            } else if (type === 'sort') {
                currentSort = 'newest';
                $('#desktopContentSort').val('newest');
                $('input[name="mobileContentSort"][value="newest"]').prop('checked', true);
            }
            renderTable();
        });

        // Clear Search Button
        $('#btnClearSearch').on('click', function () {
            $('#searchInput').val('');
            renderTable();
        });

        // Desktop Status Filter Buttons
        $('.btnDesktopStatusFilter').on('click', function () {
            currentStatusFilter = $(this).data('status');
            updateContentStatusFilterUI();
            renderTable();
        });

        // Desktop Sort Select
        $('#desktopContentSort').on('change', function () {
            currentSort = $(this).val();
            $('input[name="mobileContentSort"][value="' + currentSort + '"]').prop('checked', true);
            renderTable();
        });

        function updateContentStatusFilterUI() {
            $('.btnDesktopStatusFilter').each(function () {
                const s = $(this).data('status');
                if (s === currentStatusFilter) {
                    $(this).removeClass('text-slate-500 dark:text-slate-400 font-medium').addClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 font-semibold shadow-xs');
                } else {
                    $(this).removeClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 font-semibold shadow-xs').addClass('text-slate-500 dark:text-slate-400 font-medium');
                }
            });

            $('.btnMobileContentStatusChip').each(function () {
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
        // Mobile Content Filter & Sort Drawer Handlers
        // =========================================================================
        function openContentFilterModal() {
            const overlay = document.getElementById('contentFilterSortModal');
            const panel   = document.getElementById('contentFilterSortPanel');
            if (!overlay || !panel) return;
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            overlay.style.opacity = '1';
            overlay.style.pointerEvents = 'auto';
            panel.classList.remove('translate-y-full');
            panel.classList.add('translate-y-0');
            panel.style.transform = 'translateY(0)';
        }

        function closeContentFilterModal() {
            const overlay = document.getElementById('contentFilterSortModal');
            const panel   = document.getElementById('contentFilterSortPanel');
            if (!overlay || !panel) return;
            panel.classList.remove('translate-y-0');
            panel.classList.add('translate-y-full');
            panel.style.transform = 'translateY(100%)';
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
        }

        $('#btnOpenContentFilterSort').on('click', openContentFilterModal);
        $('.btnCloseContentFilterModal').on('click', closeContentFilterModal);
        $('#contentFilterSortModal').on('click', function (e) {
            if (e.target === this) closeContentFilterModal();
        });

        // Mobile Type Chip Click
        $('.btnMobileTypeChip').on('click', function () {
            currentTypeFilter = $(this).data('type');
            $('.btnMobileTypeChip').removeClass('bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30')
                                   .addClass('bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700');
            $(this).removeClass('bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700')
                   .addClass('bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30');

            // Sync with Desktop Tab Filter
            $('.tabFilterBtn').removeClass('active bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30')
                             .addClass('text-slate-500 dark:text-slate-400');
            $(`.tabFilterBtn[data-type="${currentTypeFilter}"]`).addClass('active bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30');
        });

        // Mobile Status Chip Click
        $('.btnMobileContentStatusChip').on('click', function () {
            currentStatusFilter = $(this).data('status');
            updateContentStatusFilterUI();
        });

        // Apply Mobile Filter
        $('#btnApplyMobileContentFilter').on('click', function () {
            const selectedSort = $('input[name="mobileContentSort"]:checked').val() || 'newest';
            currentSort = selectedSort;
            $('#desktopContentSort').val(currentSort);

            const catVal = $('#mobileContentCategoryFilter').val();
            $('#categoryFilter').val(catVal);

            closeContentFilterModal();
            loadContents();
        });

        // Reset Mobile Filter
        $('#btnResetMobileContentFilter').on('click', function () {
            currentTypeFilter = '';
            currentStatusFilter = 'all';
            currentSort = 'newest';
            $('#searchInput').val('');
            $('#categoryFilter').val('');
            $('#mobileContentCategoryFilter').val('');
            $('#desktopContentSort').val('newest');
            $('input[name="mobileContentSort"][value="newest"]').prop('checked', true);

            $('.btnMobileTypeChip').removeClass('bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30')
                                   .addClass('bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700');
            $('.btnMobileTypeChip[data-type=""]').addClass('bg-blue-50/80 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 border-blue-500/30');
            updateContentStatusFilterUI();

            closeContentFilterModal();
            loadContents();
        });

        // =========================================================================
        // Mobile Content Card Action Sheet Drawer Handlers
        // =========================================================================
        function openContentActionSheet(cnt) {
            selectedContentForActionSheet = cnt;
            $('#sheetContentTitle').text(cnt.title);
            $('#sheetContentSubtitle').text(`${cnt.type.toUpperCase()} • ${cnt.category_name || 'Tanpa Kategori'}`);
            $('#sheetToggleStatusText').text(cnt.is_active == 1 ? 'Nonaktifkan Konten' : 'Aktifkan Konten');

            const overlay = document.getElementById('contentActionSheetModal');
            const panel   = document.getElementById('contentActionSheetPanel');
            if (!overlay || !panel) return;
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            overlay.style.opacity = '1';
            overlay.style.pointerEvents = 'auto';
            panel.classList.remove('translate-y-full');
            panel.classList.add('translate-y-0');
            panel.style.transform = 'translateY(0)';
        }

        function closeContentActionSheet() {
            const overlay = document.getElementById('contentActionSheetModal');
            const panel   = document.getElementById('contentActionSheetPanel');
            if (!overlay || !panel) return;
            panel.classList.remove('translate-y-0');
            panel.classList.add('translate-y-full');
            panel.style.transform = 'translateY(100%)';
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            overlay.style.opacity = '0';
            overlay.style.pointerEvents = 'none';
            selectedContentForActionSheet = null;
        }

        $(document).on('click', '.btnOpenContentCardActionSheet', function (e) {
            e.stopPropagation();
            const id = $(this).data('id');
            const cnt = contentsData.find(c => c.id == id);
            if (cnt) openContentActionSheet(cnt);
        });

        $('.btnCloseContentActionSheet').on('click', closeContentActionSheet);
        $('#contentActionSheetModal').on('click', function (e) {
            if (e.target === this) closeContentActionSheet();
        });

        // Action Sheet Actions
        $('#btnSheetPreviewContent').on('click', function () {
            if (!selectedContentForActionSheet) return;
            const cnt = selectedContentForActionSheet;
            closeContentActionSheet();
            triggerPreviewContent(cnt.id);
        });

        $('#btnSheetEditContent').on('click', function () {
            if (!selectedContentForActionSheet) return;
            const cnt = selectedContentForActionSheet;
            closeContentActionSheet();
            triggerEditContent(cnt.id, cnt.type);
        });

        $('#btnSheetToggleStatus').on('click', function () {
            if (!selectedContentForActionSheet) return;
            const cnt = selectedContentForActionSheet;
            closeContentActionSheet();
            triggerToggleStatus(cnt.id);
        });

        $('#btnSheetDeleteContent').on('click', function () {
            if (!selectedContentForActionSheet) return;
            const cnt = selectedContentForActionSheet;
            closeContentActionSheet();
            triggerDeleteContent(cnt.id, cnt.title);
        });

        // Mobile Quick Preview Trigger
        function triggerPreviewContent(id) {
            const cnt = contentsData.find(c => c.id == id);
            if (!cnt) return;

            if (cnt.type === 'image' && cnt.file_url) {
                Swal.fire({
                    title: cnt.title,
                    imageUrl: cnt.file_url,
                    imageAlt: cnt.title,
                    showConfirmButton: false,
                    showCloseButton: true,
                    background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
                });
            } else if (cnt.type === 'video') {
                if (cnt.video_source === 'youtube' && cnt.youtube_id) {
                    Swal.fire({
                        title: cnt.title,
                        html: `<div class="aspect-video w-full rounded-xl overflow-hidden"><iframe src="https://www.youtube-nocookie.com/embed/${cnt.youtube_id}?autoplay=1&mute=1" class="w-full h-full" frameborder="0" allowfullscreen></iframe></div>`,
                        showConfirmButton: false,
                        showCloseButton: true,
                        width: '800px',
                        background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
                    });
                } else if (cnt.file_url) {
                    Swal.fire({
                        title: cnt.title,
                        html: `<div class="aspect-video w-full rounded-xl overflow-hidden"><video src="${cnt.file_url}" controls autoplay muted class="w-full h-full object-contain bg-black"></video></div>`,
                        showConfirmButton: false,
                        showCloseButton: true,
                        width: '800px',
                        background: document.documentElement.classList.contains('dark') ? '#0f172a' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#f1f5f9' : '#1e293b'
                    });
                }
            } else if (cnt.type === 'chart') {
                // Open edit chart modal in view mode or trigger click on edit
                $(`.btnEditChart[data-id="${id}"]`).click();
            }
        }

        $(document).on('click', '.btnPreviewMobile', function () {
            const id = $(this).data('id');
            triggerPreviewContent(id);
        });

        // Mobile Quick Edit Router
        function triggerEditContent(id, type) {
            if (type === 'image') {
                $(`.btnEditContent[data-id="${id}"]`).click();
            } else if (type === 'video') {
                $(`.btnEditVideo[data-id="${id}"]`).click();
            } else if (type === 'chart') {
                $(`.btnEditChart[data-id="${id}"]`).click();
            }
        }

        $(document).on('click', '.btnEditMobile', function () {
            const id = $(this).data('id');
            const type = $(this).data('type');
            triggerEditContent(id, type);
        });

        function triggerToggleStatus(id) {
            $(`.btnToggleStatus[data-id="${id}"]`).click();
        }

        function triggerDeleteContent(id, title) {
            $(`.btnDeleteContent[data-id="${id}"]`).click();
        }

        // IMAGE CONTENT MANAGEMENT (Upload & Edit)
        $('#btnUploadImage').on('click', function () {
            $('#imageForm')[0].reset();
            $('#imageId').val('');
            $('#imageModalTitle').text('Upload Konten Gambar Baru');
            $('#imagePreviewContainer').addClass('hidden').find('img').attr('src', '');
            $('.text-rose-500').addClass('hidden').text('');
            $('#imageModal').removeClass('hidden');
        });

        $('#imageFile').on('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#imagePreviewContainer').removeClass('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        $('#imageForm').on('submit', function (e) {
            e.preventDefault();
            $('.text-rose-500').addClass('hidden').text('');

            const id = $('#imageId').val();
            const file = $('#imageFile')[0] ? $('#imageFile')[0].files[0] : null;

            if (!id && file && window.BackgroundUploader) {
                window.BackgroundUploader.enqueue({
                    file: file,
                    type: 'image',
                    title: $('#imageTitle').val(),
                    categoryId: $('#imageCategory').val(),
                    duration: $('#imageDuration').val(),
                    isActive: $('#imageIsActive').is(':checked') ? 1 : 0
                });
                $('#imageModal').addClass('hidden');
                showToast('info', 'Mengunggah gambar di latar belakang...');
                return;
            }

            const url = id ? `<?= base_url('admin/content/update-image') ?>/${id}` : '<?= base_url('admin/content/store-image') ?>';
            const formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#imageModal').addClass('hidden');
                        showToast('success', res.message);
                        loadContents();
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

        $(document).on('click', '.btnEditContent', function () {
            const id = $(this).data('id');
            $('.text-rose-500').addClass('hidden').text('');

            $.ajax({
                url: `<?= base_url('admin/content/get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        const cnt = res.data;
                        $('#imageId').val(cnt.id);
                        $('#imageTitle').val(cnt.title);
                        $('#imageCategory').val(cnt.category_id);
                        $('#imageDuration').val(cnt.display_duration_seconds);
                        $('#imageIsActive').prop('checked', cnt.is_active == 1);

                        if (cnt.file_url) {
                            $('#imagePreview').attr('src', cnt.file_url);
                            $('#imagePreviewContainer').removeClass('hidden');
                        } else {
                            $('#imagePreviewContainer').addClass('hidden');
                        }

                        $('#imageModalTitle').text('Edit Konten Gambar');
                        $('#imageModal').removeClass('hidden');
                    }
                }
            });
        });

        // VIDEO CONTENT MANAGEMENT (Upload & YouTube)
        $('#btnAddVideo').on('click', function () {
            $('#videoForm')[0].reset();
            $('#videoId').val('');
            $('#videoModalTitle').text('Tambah Konten Video Baru');
            $('.text-rose-500').addClass('hidden').text('');
            
            // Default to Upload tab
            $('#srcUploadBtn').click();
            $('#videoModal').removeClass('hidden');
        });

        $('#srcUploadBtn').on('click', function () {
            $('#srcUploadBtn').addClass('bg-blue-600 text-white').removeClass('text-slate-500 dark:text-slate-400');
            $('#srcYoutubeBtn').removeClass('bg-blue-600 text-white').addClass('text-slate-500 dark:text-slate-400');
            $('#videoSource').val('upload');
            $('#sectionUploadVideo').removeClass('hidden');
            $('#sectionYoutubeVideo').addClass('hidden');
        });

        $('#srcYoutubeBtn').on('click', function () {
            $('#srcYoutubeBtn').addClass('bg-blue-600 text-white').removeClass('text-slate-500 dark:text-slate-400');
            $('#srcUploadBtn').removeClass('bg-blue-600 text-white').addClass('text-slate-500 dark:text-slate-400');
            $('#videoSource').val('youtube');
            $('#sectionYoutubeVideo').removeClass('hidden');
            $('#sectionUploadVideo').addClass('hidden');
        });

        $('#videoForm').on('submit', function (e) {
            e.preventDefault();
            $('.text-rose-500').addClass('hidden').text('');

            const id = $('#videoId').val();
            const source = $('#videoSource').val();
            const file = $('#videoFile')[0] ? $('#videoFile')[0].files[0] : null;

            if (!id && source === 'upload' && file && window.BackgroundUploader) {
                window.BackgroundUploader.enqueue({
                    file: file,
                    type: 'video',
                    title: $('#videoTitle').val(),
                    categoryId: $('#videoCategory').val(),
                    duration: $('#videoDuration').val(),
                    isActive: $('#videoIsActive').is(':checked') ? 1 : 0
                });
                $('#videoModal').addClass('hidden');
                showToast('info', 'Mengunggah video di latar belakang...');
                return;
            }

            const url = id ? `<?= base_url('admin/content/update-video') ?>/${id}` : '<?= base_url('admin/content/store-video') ?>';
            const formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#videoModal').addClass('hidden');
                        showToast('success', res.message);
                        loadContents();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errs = xhr.responseJSON.errors;
                        for (const key in errs) {
                            $(`#err_v_${key}`).removeClass('hidden').text(errs[key]);
                            $(`#err_${key}`).removeClass('hidden').text(errs[key]);
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        showToast('error', xhr.responseJSON.message);
                    }
                }
            });
        });

        $(document).on('click', '.btnEditVideo', function () {
            const id = $(this).data('id');
            $('.text-rose-500').addClass('hidden').text('');

            $.ajax({
                url: `<?= base_url('admin/content/get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        const cnt = res.data;
                        $('#videoId').val(cnt.id);
                        $('#videoTitle').val(cnt.title);
                        $('#videoCategory').val(cnt.category_id);
                        $('#videoDuration').val(cnt.video_duration_seconds || cnt.display_duration_seconds);
                        $('#videoIsActive').prop('checked', cnt.is_active == 1);

                        if (cnt.video_source === 'youtube') {
                            $('#srcYoutubeBtn').click();
                            $('#youtubeUrl').val(cnt.youtube_url || '');
                        } else {
                            $('#srcUploadBtn').click();
                        }

                        $('#videoModalTitle').text('Edit Konten Video');
                        $('#videoModal').removeClass('hidden');
                    }
                }
            });
        });

        // CHART CONTENT MANAGEMENT (Live Preview)
        function updateLiveChartPreview() {
            const chartType = $('#chartTypeInput').val() || 'bar';
            const datasetLabel = $('#datasetLabel').val() || 'Dataset';
            const datasetColor = $('#datasetColor').val() || '#3b82f6';

            const labels = [];
            const dataValues = [];

            $('.chartRow').each(function () {
                const l = $(this).find('.chartLabelInput').val().trim();
                const v = parseFloat($(this).find('.chartValueInput').val());
                if (l !== '') {
                    labels.push(l);
                    dataValues.push(isNaN(v) ? 0 : v);
                }
            });

            const ctx = document.getElementById('previewChartCanvas').getContext('2d');

            if (previewChartInstance) {
                previewChartInstance.destroy();
            }

            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#cbd5e1' : '#475569';
            const gridColor = isDarkMode ? '#334155' : '#e2e8f0';

            const chartConfig = {
                type: chartType,
                data: {
                    labels: labels.length ? labels : ['Label 1', 'Label 2'],
                    datasets: [{
                        label: datasetLabel,
                        data: dataValues.length ? dataValues : [10, 20],
                        backgroundColor: chartType === 'pie' 
                            ? ['#3b82f6', '#10b981', '#f59e0b', '#f43f5e', '#8b5cf6', '#06b6d4']
                            : (chartType === 'line' ? datasetColor + '33' : datasetColor),
                        borderColor: datasetColor,
                        borderWidth: 2,
                        fill: chartType === 'line',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: textColor, font: { size: 10 } } }
                    },
                    scales: chartType !== 'pie' ? {
                        x: { ticks: { color: textColor }, grid: { color: gridColor } },
                        y: { ticks: { color: textColor }, grid: { color: gridColor } }
                    } : {}
                }
            };

            previewChartInstance = new Chart(ctx, chartConfig);
        }

        $(document).on('input change', '#datasetLabel, #datasetColor, #datasetColorPicker, #chartTypeInput, .chartLabelInput, .chartValueInput', function () {
            updateLiveChartPreview();
        });

        $('#datasetColorPicker').on('input', function () {
            $('#datasetColor').val($(this).val());
            updateLiveChartPreview();
        });

        $('#datasetColor').on('input', function () {
            const hex = $(this).val();
            if (/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/.test(hex)) {
                $('#datasetColorPicker').val(hex);
            }
            updateLiveChartPreview();
        });

        $('.btnChartType').on('click', function () {
            $('.btnChartType').removeClass('bg-amber-600 text-white').addClass('text-slate-500 dark:text-slate-400');
            $(this).addClass('bg-amber-600 text-white').removeClass('text-slate-500 dark:text-slate-400');
            $('#chartTypeInput').val($(this).data('type'));
            updateLiveChartPreview();
        });

        $('#btnAddChartRow').on('click', function () {
            const newRow = `
                <div class="chartRow flex items-center space-x-2">
                    <input type="text" name="labels[]" placeholder="Label Baru" class="chartLabelInput flex-1 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                    <input type="number" step="any" name="values[]" value="0" placeholder="Nilai" class="chartValueInput w-24 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                    <button type="button" class="btnRemoveRow text-slate-400 hover:text-rose-500 p-1.5 cursor-pointer"><i class="fa-solid fa-trash-can text-xs"></i></button>
                </div>
            `;
            $('#chartRowsContainer').append(newRow);
            updateLiveChartPreview();
        });

        $(document).on('click', '.btnRemoveRow', function () {
            if ($('.chartRow').length > 1) {
                $(this).closest('.chartRow').remove();
                updateLiveChartPreview();
            }
        });

        $('#btnAddChart').on('click', function () {
            $('#chartForm')[0].reset();
            $('#chartContentId').val('');
            $('.btnChartType[data-type="bar"]').click();
            $('#chartModalTitle').text('Buat Konten Chart Data Baru');
            $('.text-rose-500').addClass('hidden').text('');
            $('#chartModal').removeClass('hidden');
            setTimeout(updateLiveChartPreview, 150);
        });

        $('#chartForm').on('submit', function (e) {
            e.preventDefault();
            $('.text-rose-500').addClass('hidden').text('');

            const id = $('#chartContentId').val();
            const url = id ? `<?= base_url('admin/content/update-chart') ?>/${id}` : '<?= base_url('admin/content/store-chart') ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#chartModal').addClass('hidden');
                        showToast('success', res.message);
                        loadContents();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        const errs = xhr.responseJSON.errors;
                        for (const key in errs) {
                            $(`#err_c_${key}`).removeClass('hidden').text(errs[key]);
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        showToast('error', xhr.responseJSON.message);
                    }
                }
            });
        });

        $(document).on('click', '.btnEditChart', function () {
            const id = $(this).data('id');
            $('.text-rose-500').addClass('hidden').text('');

            $.ajax({
                url: `<?= base_url('admin/content/get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        const cnt = res.data;
                        $('#chartContentId').val(cnt.id);
                        $('#chartTitle').val(cnt.title);
                        $('#chartCategory').val(cnt.category_id);
                        $('#chartDuration').val(cnt.display_duration_seconds);
                        $('#chartIsActive').prop('checked', cnt.is_active == 1);

                        if (cnt.chart_type) {
                            $(`.btnChartType[data-type="${cnt.chart_type}"]`).click();
                        }

                        if (cnt.chart_labels && cnt.chart_datasets && cnt.chart_datasets[0]) {
                            const ds = cnt.chart_datasets[0];
                            $('#datasetLabel').val(ds.label || '');
                            const hex = ds.color || '#3b82f6';
                            $('#datasetColor').val(hex);
                            $('#datasetColorPicker').val(hex);

                            let rowsHtml = '';
                            for (let i = 0; i < cnt.chart_labels.length; i++) {
                                const lbl = cnt.chart_labels[i];
                                const val = ds.data[i] !== undefined ? ds.data[i] : 0;
                                rowsHtml += `
                                    <div class="chartRow flex items-center space-x-2">
                                        <input type="text" name="labels[]" value="${lbl}" placeholder="Label" class="chartLabelInput flex-1 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                                        <input type="number" step="any" name="values[]" value="${val}" placeholder="Nilai" class="chartValueInput w-24 px-3 py-1.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-100">
                                        <button type="button" class="btnRemoveRow text-slate-400 hover:text-rose-500 p-1.5 cursor-pointer"><i class="fa-solid fa-trash-can text-xs"></i></button>
                                    </div>
                                `;
                            }
                            $('#chartRowsContainer').html(rowsHtml);
                        }

                        $('#chartModalTitle').text('Edit Konten Chart Data');
                        $('#chartModal').removeClass('hidden');
                        setTimeout(updateLiveChartPreview, 150);
                    }
                }
            });
        });

        // SHARED EVENT LISTENERS (Filters, Delete, Toggle)
        $('.tabFilterBtn').on('click', function () {
            $('.tabFilterBtn').removeClass('bg-blue-600/20 text-blue-600 dark:text-blue-400 border border-blue-500/30').addClass('text-slate-500 dark:text-slate-400');
            $(this).addClass('bg-blue-600/20 text-blue-600 dark:text-blue-400 border border-blue-500/30').removeClass('text-slate-500 dark:text-slate-400');
            currentTypeFilter = $(this).data('type');
            loadContents();
        });

        $('#searchInput, #categoryFilter').on('input change', function () {
            loadContents();
        });

        $('.btnCloseModal').on('click', function () {
            $('#imageModal, #videoModal, #chartModal').addClass('hidden');
        });

        // Close Modal on Backdrop Click
        $('#imageModal, #videoModal, #chartModal').on('click', function (e) {
            if (e.target === this) {
                $(this).addClass('hidden');
            }
        });

        $(document).on('click', '.btnToggleStatus', function () {
            const id = $(this).data('id');
            $.ajax({
                url: `<?= base_url('admin/content/toggle-status') ?>/${id}`,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        showToast('success', res.message || 'Status konten diperbarui');
                        loadContents();
                    }
                }
            });
        });

        $(document).on('click', '.btnDeleteContent', function () {
            const id = $(this).data('id');
            const title = $(this).data('title');

            Swal.fire({
                title: 'Hapus Konten?',
                text: `Apakah Anda yakin ingin menghapus "${title}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('admin/content/delete') ?>/${id}`,
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                showToast('success', res.message);
                                loadContents();
                            }
                        }
                    });
                }
            });
        });

        // HELPER: Format file size in Bytes to KB/MB
        function formatBytes(bytes, decimals = 2) {
            if (!bytes || bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        // Prevent click bubbling on file inputs to avoid recursion
        $('#imageFile, #videoFile, #mainFileInput').on('click', function (e) {
            e.stopPropagation();
        });

        // --- SINGLE FILE DROPZONE: IMAGE MODAL ---
        $('#imageDropZone').on('click', function (e) {
            e.preventDefault();
            $('#imageFile').trigger('click');
        });

        $('#imageDropZone').on('dragover dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('border-blue-500 bg-blue-50/50 dark:bg-blue-950/40 scale-[1.01]');
        });

        $('#imageDropZone').on('dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('border-blue-500 bg-blue-50/50 dark:bg-blue-950/40 scale-[1.01]');
        });

        $('#imageDropZone').on('drop', function (e) {
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                const dt = new DataTransfer();
                dt.items.add(file);
                $('#imageFile')[0].files = dt.files;
                $('#imageFile').trigger('change');
            }
        });

        $('#imageFile').on('change', function () {
            const file = this.files[0];
            if (file) {
                const allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                if (!allowedMimes.includes(file.type)) {
                    showToast('error', 'Format file harus berupa JPG, PNG, atau WEBP.');
                    this.value = '';
                    $('#imageSelectedFileInfo').addClass('hidden').text('');
                    $('#imagePreviewContainer').addClass('hidden').find('img').attr('src', '');
                    return;
                }
                if (file.size > 10 * 1024 * 1024) {
                    showToast('error', 'Ukuran file gambar melebihi batas 10MB.');
                    this.value = '';
                    $('#imageSelectedFileInfo').addClass('hidden').text('');
                    $('#imagePreviewContainer').addClass('hidden').find('img').attr('src', '');
                    return;
                }

                $('#imageSelectedFileInfo').removeClass('hidden').text(`${file.name} (${formatBytes(file.size)})`);
                const reader = new FileReader();
                reader.onload = function (e) {
                    $('#imagePreview').attr('src', e.target.result);
                    $('#imagePreviewContainer').removeClass('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        $('#btnRemoveImagePreview').on('click', function (e) {
            e.stopPropagation();
            $('#imageFile').val('');
            $('#imageSelectedFileInfo').addClass('hidden').text('');
            $('#imagePreviewContainer').addClass('hidden').find('img').attr('src', '');
        });

        // --- SINGLE FILE DROPZONE: VIDEO MODAL ---
        $('#videoDropZone').on('click', function (e) {
            e.preventDefault();
            $('#videoFile').trigger('click');
        });

        $('#videoDropZone').on('dragover dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('border-sky-500 bg-sky-50/50 dark:bg-sky-950/40 scale-[1.01]');
        });

        $('#videoDropZone').on('dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('border-sky-500 bg-sky-50/50 dark:bg-sky-950/40 scale-[1.01]');
        });

        $('#videoDropZone').on('drop', function (e) {
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                const dt = new DataTransfer();
                dt.items.add(file);
                $('#videoFile')[0].files = dt.files;
                $('#videoFile').trigger('change');
            }
        });

        $('#videoFile').on('change', function () {
            const file = this.files[0];
            if (file) {
                const allowedMimes = ['video/mp4', 'video/webm', 'video/quicktime'];
                if (!allowedMimes.includes(file.type) && !file.type.startsWith('video/')) {
                    showToast('error', 'Format file harus berupa MP4 atau WEBM.');
                    this.value = '';
                    $('#videoSelectedFileInfo').addClass('hidden').text('');
                    return;
                }
                if (file.size > 100 * 1024 * 1024) {
                    showToast('error', 'Ukuran file video melebihi batas 100MB.');
                    this.value = '';
                    $('#videoSelectedFileInfo').addClass('hidden').text('');
                    return;
                }

                $('#videoSelectedFileInfo').removeClass('hidden').text(`${file.name} (${formatBytes(file.size)})`);
                $('#videoDropZoneFileInfo').text(`File Terpilih: ${file.name} • ${formatBytes(file.size)}`);
            }
        });

        // --- QUICK MAIN PAGE DROPZONE & BATCH UPLOAD SYSTEM ---
        let batchFiles = [];

        $('#mainDropZone').on('click', function (e) {
            e.preventDefault();
            $('#mainFileInput').trigger('click');
        });

        $('#mainDropZone').on('dragover dragenter', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('border-blue-500 bg-blue-50/70 dark:bg-blue-950/40 scale-[1.01]');
        });

        $('#mainDropZone').on('dragleave drop', function (e) {
            e.preventDefault();
            e.stopPropagation();
            $(this).removeClass('border-blue-500 bg-blue-50/70 dark:bg-blue-950/40 scale-[1.01]');
        });

        $('#mainDropZone').on('drop', function (e) {
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                handleDroppedBatchFiles(files);
            }
        });

        $('#mainFileInput').on('change', function () {
            if (this.files.length > 0) {
                handleDroppedBatchFiles(this.files);
                this.value = '';
            }
        });

        // Dragover anywhere on window
        $(window).on('dragover', function (e) {
            e.preventDefault();
        });

        function handleDroppedBatchFiles(fileList) {
            const validFiles = [];
            let rejectedCount = 0;

            Array.from(fileList).forEach(file => {
                const isImage = file.type.startsWith('image/');
                const isVideo = file.type.startsWith('video/');

                if (isImage) {
                    if (file.size <= 10 * 1024 * 1024) {
                        validFiles.push(file);
                    } else {
                        rejectedCount++;
                    }
                } else if (isVideo) {
                    if (file.size <= 100 * 1024 * 1024) {
                        validFiles.push(file);
                    } else {
                        rejectedCount++;
                    }
                } else {
                    rejectedCount++;
                }
            });

            if (rejectedCount > 0) {
                showToast('warning', `${rejectedCount} file diabaikan (format tidak didukung atau melebihi batas ukuran 10MB Gambar / 100MB Video)`);
            }

            if (validFiles.length === 0) return;

            batchFiles = validFiles;
            renderBatchModal();
            $('#batchUploadModal').removeClass('hidden');
        }

        $('.btnCloseBatchModal').on('click', function () {
            $('#batchUploadModal').addClass('hidden');
            batchFiles = [];
        });

        // Close Batch Modal on Backdrop Click
        $('#batchUploadModal').on('click', function (e) {
            if (e.target === this) {
                $('#batchUploadModal').addClass('hidden');
                batchFiles = [];
            }
        });

        function renderBatchModal() {
            if (batchFiles.length === 0) {
                $('#batchUploadModal').addClass('hidden');
                return;
            }

            let totalSize = 0;
            batchFiles.forEach(f => totalSize += f.size);

            $('#batchSummaryText').text(`${batchFiles.length} Berkas Media Terpilih`);
            $('#batchTotalSizeBadge').text(`Total Ukuran: ${formatBytes(totalSize)}`);

            const defaultCategoryId = $('#categoryFilter').val() || '';

            let html = '';
            batchFiles.forEach((file, index) => {
                const isImage = file.type.startsWith('image/');
                const cleanTitle = file.name.replace(/\.[^/.]+$/, "");
                const formattedSize = formatBytes(file.size);

                const fileBadge = isImage 
                    ? `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">GAMBAR</span>`
                    : `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20">VIDEO MP4</span>`;

                html += `
                    <div class="batchItemRow p-3.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl space-y-3 relative group" data-index="${index}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div class="batchPreviewThumb w-14 h-12 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-400 overflow-hidden shrink-0">
                                    ${isImage ? `<i class="fa-solid fa-image text-lg"></i>` : `<i class="fa-solid fa-film text-sky-500 text-lg"></i>`}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        ${fileBadge}
                                        <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 font-semibold">${formattedSize}</span>
                                    </div>
                                    <input type="text" name="batch_title_${index}" value="${cleanTitle}" placeholder="Judul Konten" required
                                        class="batchTitleInput w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500">
                                </div>
                            </div>
                            <button type="button" class="btnRemoveBatchItem p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg transition-colors cursor-pointer" data-index="${index}" title="Hapus Berkas Ini">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 border-t border-slate-200/60 dark:border-slate-800/60">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Kategori Konten</label>
                                <select name="batch_category_${index}" class="batchCategorySelect w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500">
                                    <option value="">-- Pilih Kategori --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" ${defaultCategoryId == <?= $cat['id'] ?> ? 'selected' : ''}><?= esc($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-400 mb-1">Durasi Tayang (Detik)</label>
                                <div class="relative">
                                    <input type="number" name="batch_duration_${index}" value="${isImage ? 10 : 30}" min="3" max="300" required
                                        class="batchDurationInput w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:border-blue-500">
                                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-[10px] text-slate-400 font-semibold">detik</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#batchItemsContainer').html(html);

            // Generate image previews for batch item rows
            batchFiles.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $(`.batchItemRow[data-index="${index}"] .batchPreviewThumb`).html(`
                            <img src="${e.target.result}" class="w-full h-full object-cover rounded-lg">
                        `);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Apply bulk settings across all batch items
        $('#btnApplyBulk').on('click', function () {
            const bulkCat = $('#bulkCategorySelect').val();
            const bulkDur = $('#bulkDurationInput').val();

            if (bulkCat) {
                $('.batchCategorySelect').val(bulkCat);
            }
            if (bulkDur && bulkDur > 0) {
                $('.batchDurationInput').val(bulkDur);
            }

            showToast('success', 'Pengaturan serentak berhasil diterapkan ke seluruh file.');
        });

        // Remove item from batch
        $(document).on('click', '.btnRemoveBatchItem', function () {
            const index = $(this).data('index');
            batchFiles.splice(index, 1);
            renderBatchModal();
        });

        // Submit Batch Form via BackgroundUploader
        $('#batchForm').on('submit', function (e) {
            e.preventDefault();

            if (batchFiles.length === 0) {
                showToast('error', 'Tidak ada berkas yang dipilih.');
                return;
            }

            let missingCategory = false;
            $('.batchCategorySelect').each(function () {
                if (!$(this).val()) {
                    missingCategory = true;
                }
            });

            if (missingCategory) {
                showToast('warning', 'Semua berkas wajib memilih Kategori Konten.');
                return;
            }

            if (window.BackgroundUploader) {
                const itemsToEnqueue = [];
                batchFiles.forEach((file, i) => {
                    itemsToEnqueue.push({
                        file: file,
                        type: file.type.startsWith('video/') ? 'video' : 'image',
                        title: $(`input[name="batch_title_${i}"]`).val(),
                        categoryId: $(`select[name="batch_category_${i}"]`).val(),
                        duration: $(`input[name="batch_duration_${i}"]`).val(),
                        isActive: 1
                    });
                });

                window.BackgroundUploader.enqueue(itemsToEnqueue);
                $('#batchUploadModal').addClass('hidden');
                batchFiles = [];
                showToast('info', `Proses unggah ${itemsToEnqueue.length} berkas media berjalan di latar belakang.`);
            }
        });

        // Listen for background upload completion event to reload content table
        window.addEventListener('backgroundUploadCompleted', function () {
            loadContents();
        });

        // Initial Load
        loadContents();
    });
</script>
<?= $this->endSection() ?>
