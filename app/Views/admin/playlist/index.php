<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & TV Selector Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Kelola Playlist per Display TV</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Seret dan lepas (drag-and-drop) untuk mengatur urutan slide penayangan khusus untuk setiap TV.</p>
    </div>

    <!-- TV Selector Dropdown & PIN Copy Badge -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 sm:p-1.5 rounded-2xl shadow-xs w-full sm:w-auto">
        <div class="flex items-center space-x-2.5 flex-1 w-full sm:w-auto">
            <label for="tvSelector" class="text-xs font-semibold text-slate-600 dark:text-slate-400 pl-1 sm:pl-3 shrink-0 flex items-center">
                <i class="fa-solid fa-tv mr-1.5 text-blue-600 dark:text-blue-400"></i>Pilih TV:
            </label>
            <select id="tvSelector" class="bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-white px-3.5 py-2 focus:outline-none focus:border-blue-500 transition-all font-medium w-full flex-1 sm:w-auto">
                <?php if (empty($tvs)): ?>
                    <option value="">-- Belum ada TV --</option>
                <?php else: ?>
                    <?php foreach ($tvs as $tv): ?>
                        <option value="<?= $tv['id'] ?>" data-pin="<?= esc($tv['pin']) ?>" <?= ($selectedTvId == $tv['id']) ? 'selected' : '' ?>>
                            <?= esc($tv['name']) ?> (PIN: <?= esc($tv['pin']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <!-- PIN Badge & Copy Button (Mobile: full width below, Desktop: side-by-side) -->
        <div id="selectedTvPinBadge" class="flex items-center justify-between sm:justify-center space-x-2 bg-blue-500/10 border border-blue-500/20 px-3.5 py-2 sm:py-1.5 rounded-xl text-xs font-mono font-bold text-blue-600 dark:text-blue-400 w-full sm:w-auto shrink-0 hidden">
            <span class="flex items-center space-x-1.5">
                <span class="text-[11px] font-sans font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">PIN:</span>
                <span id="selectedTvPinText" class="text-xs sm:text-sm font-mono tracking-widest text-blue-600 dark:text-blue-400">------</span>
            </span>
            <button type="button" id="btnCopySelectedPin" class="px-2 py-1 bg-blue-500/10 hover:bg-blue-500/20 rounded-lg text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center space-x-1 ux-hover" title="Copy PIN ke Clipboard">
                <i class="fa-regular fa-copy text-xs"></i>
                <span class="text-[10px] font-sans font-semibold sm:hidden">Salin</span>
            </button>
        </div>
    </div>
</div>

<!-- Info Summary & Instructions -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8">
    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 sm:space-x-4 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-film"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400">Total Slide Penayangan</p>
            <h4 class="text-lg font-bold text-slate-800 dark:text-white" id="totalSlidesText">0 Slide</h4>
        </div>
    </div>

    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 sm:space-x-4 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-stopwatch"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400">Estimasi Total Rotasi</p>
            <h4 class="text-lg font-bold text-slate-800 dark:text-white" id="totalRotationTimeText">0 detik</h4>
        </div>
    </div>

    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 sm:space-x-4 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-arrows-up-down-left-right"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400">Status Auto-Save</p>
            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center space-x-1.5 mt-0.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                <span>Aktif (Drag & Drop Auto-Save)</span>
            </span>
        </div>
    </div>
</div>

<!-- Drag-and-Drop Playlist Container -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-4 sm:p-6 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-4 mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center">
            <i class="fa-solid fa-list-ol text-blue-600 dark:text-blue-400 mr-2"></i>Urutan Penayangan Slideshow
        </h3>
        <span class="text-xs text-slate-400 dark:text-slate-500 italic">Gunakan ikon pegangan <i class="fa-solid fa-grip-vertical text-slate-400 dark:text-slate-500 mx-1"></i> untuk menggeser posisi slide.</span>
    </div>

    <!-- Reorderable List -->
    <div id="playlistSortable" class="space-y-3">
        <!-- Loading State -->
        <div class="py-12 text-center text-slate-400 dark:text-slate-500">
            <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 block"></i>
            <span>Memuat playlist TV...</span>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let sortableInstance = null;

        function formatTime(seconds) {
            const sec = parseInt(seconds) || 0;
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            if (m > 0) return `${m}m ${s}s`;
            return `${s}s`;
        }

        // Load Playlist for TV
        function loadPlaylist(tvId) {
            if (!tvId) {
                $('#playlistSortable').html(`
                    <div class="text-center py-12 text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-tv text-3xl mb-2 block"></i>
                        <p>Silakan pilih atau tambahkan TV terlebih dahulu.</p>
                    </div>
                `);
                return;
            }

            $.ajax({
                url: `<?= base_url('admin/playlist/get') ?>/${tvId}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        renderPlaylist(res.data);
                    }
                },
                error: function () {
                    $('#playlistSortable').html(`
                        <div class="text-center py-12 text-rose-500">
                            Gagal memuat playlist TV.
                        </div>
                    `);
                }
            });
        }

        // Render Playlist Cards
        function renderPlaylist(items) {
            if (!items || items.length === 0) {
                $('#totalSlidesText').text('0 Slide');
                $('#totalRotationTimeText').text('0 detik');
                $('#playlistSortable').html(`
                    <div class="text-center py-12 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                        <i class="fa-solid fa-film text-3xl text-slate-400 dark:text-slate-600 mb-3 block"></i>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada konten pada kategori TV ini.</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Assign kategori ke TV ini atau buat konten baru di menu Kelola Konten.</p>
                    </div>
                `);
                return;
            }

            let totalSec = 0;
            let html = '';

            items.forEach((item, index) => {
                const dur = parseInt(item.display_duration_seconds || item.video_duration_seconds) || 10;
                totalSec += dur;

                const catColor = item.category_color || '#6366f1';
                const catBadge = `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border"
                          style="background-color: ${catColor}15; color: ${catColor}; border-color: ${catColor}30;">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: ${catColor};"></span>
                        <span>${item.category_name}</span>
                    </span>
                `;

                let typeIcon = '';
                let thumbnailHtml = '';

                if (item.type === 'image') {
                    typeIcon = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20"><i class="fa-solid fa-image mr-1"></i>Gambar</span>`;
                    thumbnailHtml = item.file_url 
                        ? `<img src="${item.file_url}" class="w-12 h-9 sm:w-14 sm:h-10 object-cover rounded-xl border border-slate-200 dark:border-slate-700 shrink-0">`
                        : `<div class="w-12 h-9 sm:w-14 sm:h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-400 dark:text-slate-500 shrink-0"><i class="fa-solid fa-image"></i></div>`;
                } else if (item.type === 'video') {
                    const isYt = item.video_source === 'youtube';
                    typeIcon = isYt 
                        ? `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20"><i class="fa-brands fa-youtube mr-1"></i>YouTube</span>`
                        : `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20"><i class="fa-solid fa-file-video mr-1"></i>Video</span>`;
                    thumbnailHtml = `<div class="w-12 h-9 sm:w-14 sm:h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-sky-600 dark:text-sky-400 shrink-0"><i class="fa-solid fa-circle-play text-base sm:text-lg"></i></div>`;
                } else {
                    typeIcon = `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20"><i class="fa-solid fa-chart-column mr-1"></i>Chart</span>`;
                    thumbnailHtml = `<div class="w-12 h-9 sm:w-14 sm:h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-amber-600 dark:text-amber-400 shrink-0"><i class="fa-solid fa-chart-line text-base sm:text-lg"></i></div>`;
                }

                html += `
                    <div class="playlistItemCard flex flex-col sm:flex-row sm:items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700/80 transition-all shadow-xs gap-2.5 sm:gap-4" data-id="${item.id}">
                        <div class="flex items-center space-x-2.5 sm:space-x-3.5 min-w-0 flex-1">
                            <!-- Drag Handle -->
                            <button type="button" class="dragHandle cursor-grab active:cursor-grabbing p-1.5 sm:p-2 text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shrink-0">
                                <i class="fa-solid fa-grip-vertical text-base"></i>
                            </button>

                            <!-- Index Number -->
                            <span class="slideIndexPill w-7 h-7 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 font-mono font-extrabold text-xs flex items-center justify-center shrink-0">
                                #${index + 1}
                            </span>

                            <!-- Thumbnail -->
                            ${thumbnailHtml}

                            <!-- Title & Info -->
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold text-slate-800 dark:text-slate-100 text-xs sm:text-sm truncate" title="${item.title}">${item.title}</h4>
                                <div class="hidden sm:flex items-center space-x-2 mt-1">
                                    ${typeIcon}
                                    ${catBadge}
                                </div>
                            </div>
                        </div>

                        <!-- Right Info: Badges (Mobile) & Duration -->
                        <div class="flex items-center justify-between sm:justify-end space-x-2 shrink-0 pt-2 sm:pt-0 border-t border-slate-100 dark:border-slate-800/60 sm:border-t-0">
                            <div class="flex sm:hidden items-center space-x-1.5 flex-wrap gap-y-1">
                                ${typeIcon}
                                ${catBadge}
                            </div>
                            <span class="text-xs font-mono font-semibold text-blue-600 dark:text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl flex items-center space-x-1.5 shrink-0 ml-auto sm:ml-0">
                                <i class="fa-regular fa-clock text-[11px]"></i>
                                <span>${formatTime(dur)}</span>
                            </span>
                        </div>
                    </div>
                `;
            });

            $('#totalSlidesText').text(`${items.length} Slide`);
            $('#totalRotationTimeText').text(formatTime(totalSec));
            $('#playlistSortable').html(html);

            // Init SortableJS
            initSortable();
        }

        // Initialize SortableJS
        function initSortable() {
            const container = document.getElementById('playlistSortable');
            if (!container) return;

            if (sortableInstance) {
                sortableInstance.destroy();
            }

            sortableInstance = new Sortable(container, {
                handle: '.dragHandle',
                animation: 200,
                ghostClass: 'opacity-40',
                chosenClass: 'bg-slate-100/90',
                onEnd: function () {
                    updateIndexPills();
                    saveOrder();
                }
            });
        }

        // Update Index Pills (#1, #2, ...)
        function updateIndexPills() {
            $('.playlistItemCard').each(function (idx) {
                $(this).find('.slideIndexPill').text(`#${idx + 1}`);
            });
        }

        // Save Order via AJAX
        function saveOrder() {
            const tvId = $('#tvSelector').val();
            const contentIds = [];

            $('.playlistItemCard').each(function () {
                contentIds.push($(this).data('id'));
            });

            $.ajax({
                url: '<?= base_url('admin/playlist/reorder') ?>',
                type: 'POST',
                data: {
                    tv_id: tvId,
                    content_ids: contentIds
                },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        showToast('success', res.message || 'Urutan playlist tersimpan');
                    }
                }
            });
        }

        // Update Selected PIN Badge
        function updateSelectedPinBadge() {
            const selectedOpt = $('#tvSelector option:selected');
            const pin = selectedOpt.data('pin');
            if (pin) {
                $('#selectedTvPinText').text(pin);
                $('#selectedTvPinBadge').removeClass('hidden');
            } else {
                $('#selectedTvPinBadge').addClass('hidden');
            }
        }

        // TV Selector Change
        $('#tvSelector').on('change', function () {
            const tvId = $(this).val();
            updateSelectedPinBadge();
            loadPlaylist(tvId);
        });

        // Copy Selected PIN Click
        $('#btnCopySelectedPin').on('click', function () {
            const pin = $('#selectedTvPinText').text();
            if (pin && pin !== '------') {
                copyPinToClipboard(pin, this);
            }
        });

        // Initial Load
        const initialTvId = $('#tvSelector').val();
        updateSelectedPinBadge();
        loadPlaylist(initialTvId);
    });
</script>
<?= $this->endSection() ?>

