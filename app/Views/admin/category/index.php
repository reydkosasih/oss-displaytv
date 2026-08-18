<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Manajemen Kategori Konten</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola kategori untuk mengelompokkan gambar, video, dan chart pada TV Display.</p>
    </div>
    <div>
        <button type="button" id="btnCreateCategory" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Tambah Kategori Baru</span>
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
            <input type="text" id="searchInput" placeholder="Cari nama atau slug kategori..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="categoryTable">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-4">Nama Kategori</th>
                    <th class="py-3.5 px-4">Slug URL</th>
                    <th class="py-3.5 px-4">Warna Badge</th>
                    <th class="py-3.5 px-4">Total Konten</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs" id="categoryTableBody">
                <!-- Loading State -->
                <tr>
                    <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-spinner fa-spin text-lg mb-2 block"></i>
                        <span>Memuat data kategori...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Modal / Bottom Sheet Drawer Form Kategori (Tambah / Edit) -->
<div id="categoryModal" class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-lg p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-5 shrink-0">
            <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalTitle">Tambah Kategori Baru</h3>
            <button type="button" class="btnCloseModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Form (Scrollable Content) -->
        <form id="categoryForm" class="flex-1 overflow-y-auto pr-1">
            <input type="hidden" id="categoryId" name="id">

            <div class="space-y-4">
                <!-- Nama Input -->
                <div>
                    <label for="categoryName" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Kategori</label>
                    <input type="text" id="categoryName" name="name" required placeholder="Contoh: Informasi Publik, Promo Produk"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_name"></p>
                </div>

                <!-- Color Selection & Picker -->
                <div>
                    <label for="categoryColor" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Warna Badge Visual</label>
                    
                    <!-- Preset Color Swatches -->
                    <div class="flex items-center space-x-2 mb-3">
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#6366f1" style="background-color: #6366f1;" title="Indigo"></button>
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#8b5cf6" style="background-color: #8b5cf6;" title="Violet"></button>
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#3b82f6" style="background-color: #3b82f6;" title="Blue"></button>
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#06b6d4" style="background-color: #06b6d4;" title="Cyan"></button>
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#10b981" style="background-color: #10b981;" title="Emerald"></button>
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#f59e0b" style="background-color: #f59e0b;" title="Amber"></button>
                        <button type="button" class="btnPresetColor w-7 h-7 rounded-lg border-2 border-transparent transition-transform hover:scale-110 shadow" data-color="#f43f5e" style="background-color: #f43f5e;" title="Rose"></button>
                    </div>

                    <!-- Custom Hex Picker -->
                    <div class="flex items-center space-x-3">
                        <input type="color" id="categoryColorPicker" value="#6366f1" class="w-9 h-9 p-0 bg-transparent border-0 cursor-pointer rounded-lg">
                        <input type="text" id="categoryColor" name="color" value="#6366f1" placeholder="#6366f1"
                            class="flex-1 px-3.5 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-mono text-slate-800 dark:text-slate-100 uppercase focus:outline-none focus:border-blue-500 transition-all">
                    </div>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_color"></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-6 mt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 ux-hover">
                    Batal
                </button>
                <button type="submit" id="btnSaveCategory" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Kategori</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let categoriesData = [];

        // Fetch Category Data
        function loadCategories() {
            $.ajax({
                url: '<?= base_url('admin/category/list') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        categoriesData = res.data;
                        renderTable();
                    }
                },
                error: function () {
                    $('#categoryTableBody').html(`
                        <tr>
                            <td colspan="5" class="py-8 text-center text-rose-500">
                                Gagal memuat data kategori.
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Render Table Rows
        function renderTable() {
            const search = $('#searchInput').val().toLowerCase();

            const filtered = categoriesData.filter(cat => {
                return cat.name.toLowerCase().includes(search) || cat.slug.toLowerCase().includes(search);
            });

            if (filtered.length === 0) {
                $('#categoryTableBody').html(`
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ditemukan data kategori.
                        </td>
                    </tr>
                `);
                return;
            }

            let html = '';
            filtered.forEach(cat => {
                const color = cat.color || '#6366f1';
                
                const badge = `
                    <span class="inline-flex items-center space-x-2 px-2.5 py-1 rounded-full text-[11px] font-semibold border"
                          style="background-color: ${color}15; color: ${color}; border-color: ${color}30;">
                        <span class="w-2 h-2 rounded-full" style="background-color: ${color};"></span>
                        <span class="font-mono">${color.toUpperCase()}</span>
                    </span>
                `;

                html += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">
                            ${cat.name}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500 dark:text-slate-400">
                            ${cat.slug}
                        </td>
                        <td class="py-3.5 px-4">${badge}</td>
                        <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-medium">
                            <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                <i class="fa-solid fa-photo-film text-[10px] text-slate-400"></i>
                                <span>${cat.contents_count} Media</span>
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            <button type="button" class="btnEditCategory p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-500/10 rounded-lg ux-hover" data-id="${cat.id}" title="Edit Kategori">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            <button type="button" class="btnDeleteCategory p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg ux-hover" data-id="${cat.id}" data-name="${cat.name}" title="Hapus Kategori">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });

            $('#categoryTableBody').html(html);
        }

        // Search Input Listener
        $('#searchInput').on('input', function () {
            renderTable();
        });

        // Color Swatches Sync
        $('.btnPresetColor').on('click', function () {
            const hex = $(this).data('color');
            $('#categoryColor').val(hex);
            $('#categoryColorPicker').val(hex);
        });

        $('#categoryColorPicker').on('input', function () {
            $('#categoryColor').val($(this).val());
        });

        $('#categoryColor').on('input', function () {
            const hex = $(this).val();
            if (/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/.test(hex)) {
                $('#categoryColorPicker').val(hex);
            }
        });

        // Open Modal Create
        $('#btnCreateCategory').on('click', function () {
            $('#categoryForm')[0].reset();
            $('#categoryId').val('');
            $('#categoryColor').val('#6366f1');
            $('#categoryColorPicker').val('#6366f1');
            $('#modalTitle').text('Tambah Kategori Baru');
            $('.text-rose-500').addClass('hidden').text('');
            $('#categoryModal').removeClass('hidden');
        });

        // Close Modal
        $('.btnCloseModal').on('click', function () {
            $('#categoryModal').addClass('hidden');
        });

        // Close Modal on Backdrop Click
        $('#categoryModal').on('click', function (e) {
            if (e.target === this) {
                $('#categoryModal').addClass('hidden');
            }
        });

        // Open Modal Edit
        $(document).on('click', '.btnEditCategory', function () {
            const id = $(this).data('id');
            $('.text-rose-500').addClass('hidden').text('');

            $.ajax({
                url: `<?= base_url('admin/category/get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        const c = res.data;
                        $('#categoryId').val(c.id);
                        $('#categoryName').val(c.name);
                        const hex = c.color || '#6366f1';
                        $('#categoryColor').val(hex);
                        $('#categoryColorPicker').val(hex);
                        $('#modalTitle').text('Edit Kategori');
                        $('#categoryModal').removeClass('hidden');
                    }
                }
            });
        });

        // Submit Form (Create / Update)
        $('#categoryForm').on('submit', function (e) {
            e.preventDefault();
            $('.text-rose-500').addClass('hidden').text('');

            const id = $('#categoryId').val();
            const isEdit = id !== '';
            const url = isEdit ? `<?= base_url('admin/category/update') ?>/${id}` : '<?= base_url('admin/category/store') ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#categoryModal').addClass('hidden');
                        showToast('success', res.message);
                        loadCategories();
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

        // Delete Category
        $(document).on('click', '.btnDeleteCategory', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Hapus Kategori?',
                text: `Apakah Anda yakin ingin menghapus kategori "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('admin/category/delete') ?>/${id}`,
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                showToast('success', res.message);
                                loadCategories();
                            }
                        },
                        error: function (xhr) {
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                showToast('error', xhr.responseJSON.message);
                            }
                        }
                    });
                }
            });
        });

        // Initial Load
        loadCategories();
    });
</script>
<?= $this->endSection() ?>

