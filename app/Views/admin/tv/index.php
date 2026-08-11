<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Manajemen Display TV</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Daftarkan perangkat TV, atur lokasi, assign kombinasi kategori, dan kelola PIN keamanan.</p>
    </div>
    <div>
        <button type="button" id="btnCreateTv" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah TV Display Baru</span>
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
    <!-- Search Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" id="searchInput" placeholder="Cari nama TV, lokasi, atau PIN..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="tvTable">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-4">Display TV</th>
                    <th class="py-3.5 px-4">PIN Akses</th>
                    <th class="py-3.5 px-4">Kategori Ter-assign</th>
                    <th class="py-3.5 px-4">Status Device</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs" id="tvTableBody">
                <!-- Loading State -->
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-spinner fa-spin text-lg mb-2 block"></i>
                        <span>Memuat data Display TV...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal / Bottom Sheet Drawer Form TV (Tambah / Edit) -->
<div id="tvModal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200">
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
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_name"></p>
                </div>

                <!-- Lokasi Input -->
                <div>
                    <label for="tvLocation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Lokasi Perangkat</label>
                    <input type="text" id="tvLocation" name="location" placeholder="Contoh: Gedung Rektorat Lt. 1"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
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
                <button type="submit" id="btnSaveTv" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Display TV</span>
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

        // Fetch TV Data
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
                }
            });
        }

        // Render Table Rows
        function renderTable() {
            const search = $('#searchInput').val().toLowerCase();

            const filtered = tvsData.filter(tv => {
                const nameMatch = tv.name.toLowerCase().includes(search);
                const locMatch = (tv.location || '').toLowerCase().includes(search);
                const pinMatch = tv.pin.includes(search);
                return nameMatch || locMatch || pinMatch;
            });

            if (filtered.length === 0) {
                $('#tvTableBody').html(`
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ditemukan data Display TV.
                        </td>
                    </tr>
                `);
                return;
            }

            let html = '';
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

                // Online/Offline Status
                const onlineStatus = tv.is_online
                    ? `<span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20" title="TV sedang aktif menerima stream SSE">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-ping"></span><span>Online</span>
                       </span>`
                    : (tv.is_active == 1 
                        ? `<span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span><span>Standby</span>
                           </span>`
                        : `<span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                            <span>Nonaktif</span>
                           </span>`);

                const thumbHtml = tv.thumbnail_url 
                    ? `<img src="${tv.thumbnail_url}" class="w-10 h-8 object-cover rounded-lg border border-slate-300 dark:border-slate-700 shrink-0">`
                    : `<div class="w-10 h-8 rounded-lg bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-500 shrink-0"><i class="fa-solid fa-tv text-xs"></i></div>`;

                html += `
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
                                <button type="button" class="btnCopyTvPin text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 p-1.5 ux-hover" data-pin="${tv.pin}" title="Copy PIN ke Clipboard">
                                    <i class="fa-regular fa-copy text-xs"></i>
                                </button>
                                <button type="button" class="btnRegeneratePin text-slate-400 hover:text-amber-500 p-1.5 ux-hover" data-id="${tv.id}" data-name="${tv.name}" title="Regenerate PIN Baru">
                                    <i class="fa-solid fa-rotate text-xs"></i>
                                </button>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">${catBadges}</td>
                        <td class="py-3.5 px-4">${onlineStatus}</td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            <button type="button" class="btnEditTv p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-500/10 rounded-lg ux-hover" data-id="${tv.id}" title="Edit TV">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button type="button" class="btnDeleteTv p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg ux-hover" data-id="${tv.id}" data-name="${tv.name}" title="Hapus TV">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#tvTableBody').html(html);
        }

        // Search Input Listener
        $('#searchInput').on('input', function () {
            renderTable();
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

        // Open Modal Edit
        $(document).on('click', '.btnEditTv', function () {
            const id = $(this).data('id');
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

        // Regenerate PIN
        $(document).on('click', '.btnRegeneratePin', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

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
        });

        // Delete TV
        $(document).on('click', '.btnDeleteTv', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

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
        });

        // Initial Load
        loadTvs();
    });
</script>
<?= $this->endSection() ?>

