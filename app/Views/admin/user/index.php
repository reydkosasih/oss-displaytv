<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & Action Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight">Manajemen User System</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kelola data pengguna, departemen, peran akses (Superadmin/Admin), dan hak akses kategori konten.</p>
    </div>
    <div>
        <button type="button" id="btnCreateUser" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>Tambah User Baru</span>
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
        <div class="relative w-full sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-magnifying-glass text-xs"></i>
            </div>
            <input type="text" id="searchInput" placeholder="Cari nama, email, atau departemen..." 
                class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
        </div>

        <div class="flex items-center space-x-3 w-full sm:w-auto">
            <select id="roleFilter" class="bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-700 dark:text-slate-300 px-3 py-2 focus:outline-none focus:border-blue-500 transition-all">
                <option value="">Semua Role</option>
                <option value="superadmin">Superadmin</option>
                <option value="admin">Admin</option>
            </select>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="usersTable">
            <thead>
                <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-4">Pengguna</th>
                    <th class="py-3.5 px-4">Departemen</th>
                    <th class="py-3.5 px-4">Role</th>
                    <th class="py-3.5 px-4">Akses Kategori</th>
                    <th class="py-3.5 px-4">Status</th>
                    <th class="py-3.5 px-4">Login Terakhir</th>
                    <th class="py-3.5 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs" id="userTableBody">
                <!-- Loading State -->
                <tr>
                    <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-spinner fa-spin text-lg mb-2 block"></i>
                        <span>Memuat data pengguna...</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- Modal / Bottom Sheet Drawer Form User (Tambah / Edit) -->
<div id="userModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200 p-0 sm:p-4">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-2xl sm:rounded-2xl w-full max-w-lg p-6 shadow-2xl relative transform transition-all duration-300 max-h-[85vh] sm:max-h-[90vh] flex flex-col animate-slide-up sm:animate-none">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 mb-5 shrink-0">
            <h3 class="text-base font-bold text-slate-800 dark:text-white" id="modalTitle">Tambah User Baru</h3>
            <button type="button" class="btnCloseModal text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Modal Form Content (Scrollable) -->
        <form id="userForm" class="flex-1 overflow-y-auto pr-1">
            <input type="hidden" id="userId" name="id">

            <div class="space-y-4">
                <!-- Nama Input -->
                <div>
                    <label for="userName" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="userName" name="name" required placeholder="Masukkan nama pengguna"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_name"></p>
                </div>

                <!-- Email Input -->
                <div>
                    <label for="userEmail" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email</label>
                    <input type="email" id="userEmail" name="email" required placeholder="nama@domain.com"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_email"></p>
                </div>

                <!-- Departemen Input -->
                <div>
                    <label for="userDepartment" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Departemen <span class="text-slate-400 dark:text-slate-500 font-normal text-[10px]">(Opsional)</span>
                    </label>
                    <input type="text" id="userDepartment" name="department" placeholder="Contoh: IT, HR, Finance, Marketing"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_department"></p>
                </div>

                <!-- Role Input -->
                <div>
                    <label for="userRole" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Role Access</label>
                    <select id="userRole" name="role" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                        <option value="admin">Admin</option>
                        <option value="superadmin">Superadmin</option>
                    </select>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_role"></p>
                </div>

                <!-- Kategori Akses Checkboxes (Muncul jika Role Admin) -->
                <div id="categoryAccessContainer" class="p-4 bg-slate-50 dark:bg-slate-950/50 border border-slate-200 dark:border-slate-800/80 rounded-xl space-y-2">
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-200">Akses Kategori Konten</label>
                        <span class="text-[10px] text-blue-600 dark:text-blue-400 font-semibold">Khusus Admin</span>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-3">Pilih kategori konten yang diizinkan untuk dikelola oleh Admin ini:</p>
                    
                    <?php if (!empty($categories)): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php foreach ($categories as $cat): ?>
                                <label class="flex items-center space-x-2.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-lg p-2.5 cursor-pointer hover:border-slate-300 dark:hover:border-slate-700 transition-colors">
                                    <input type="checkbox" name="category_ids[]" value="<?= $cat['id'] ?>" class="userCategoryCb w-4 h-4 rounded bg-slate-100 dark:bg-slate-950 border-slate-300 dark:border-slate-800 text-blue-600 focus:ring-blue-500">
                                    <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= esc($cat['color'] ?: '#6366f1') ?>;"></span>
                                    <span class="text-xs text-slate-700 dark:text-slate-200 font-medium truncate"><?= esc($cat['name']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-xs text-slate-500 italic">Belum ada data kategori tersimpan.</p>
                    <?php endif; ?>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_category_ids"></p>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="userPassword" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                        Password <span id="passwordHelp" class="text-slate-400 dark:text-slate-500 font-normal text-[10px]">(Opsional jika tidak diganti)</span>
                    </label>
                    <input type="password" id="userPassword" name="password" placeholder="••••••••"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_password"></p>
                </div>

                <!-- Status Select Input -->
                <div>
                    <label for="userIsActive" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Status Akun</label>
                    <select id="userIsActive" name="is_active" required class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:outline-none focus:border-blue-500 transition-all">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                    <p class="text-[10px] text-rose-500 mt-1 hidden" id="err_is_active"></p>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end space-x-3 pt-5 mt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                <button type="button" class="btnCloseModal px-4 py-2 rounded-xl border border-slate-300 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-800 ux-hover">
                    Batal
                </button>
                <button type="submit" id="btnSaveUser" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-xs font-semibold shadow-lg shadow-blue-600/20 transition-all flex items-center space-x-2 ux-hover">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Simpan Data</span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let usersData = [];
        const currentUserId = <?= (int) session()->get('user_id') ?>;

        // Toggle tampilan checkbox Kategori berdasarkan Role
        function toggleCategoryAccessView() {
            const role = $('#userRole').val();
            if (role === 'admin') {
                $('#categoryAccessContainer').removeClass('hidden');
            } else {
                $('#categoryAccessContainer').addClass('hidden');
                $('.userCategoryCb').prop('checked', false);
            }
        }

        $('#userRole').on('change', function () {
            toggleCategoryAccessView();
        });

        // Fetch User Data
        function loadUsers() {
            $.ajax({
                url: '<?= base_url('admin/users/list') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        usersData = res.data;
                        renderTable();
                    }
                },
                error: function () {
                    $('#userTableBody').html(`
                        <tr>
                            <td colspan="7" class="py-8 text-center text-rose-500">
                                Gagal memuat data pengguna.
                            </td>
                        </tr>
                    `);
                }
            });
        }

        // Render Table Rows
        function renderTable() {
            const search = $('#searchInput').val().toLowerCase();
            const roleFilter = $('#roleFilter').val();

            const filtered = usersData.filter(user => {
                const matchSearch = user.name.toLowerCase().includes(search) || 
                                    user.email.toLowerCase().includes(search) ||
                                    (user.department && user.department.toLowerCase().includes(search));
                const matchRole = roleFilter === '' || user.role === roleFilter;
                return matchSearch && matchRole;
            });

            if (filtered.length === 0) {
                $('#userTableBody').html(`
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ditemukan data pengguna.
                        </td>
                    </tr>
                `);
                return;
            }

            let html = '';
            filtered.forEach(user => {
                const isSelf = parseInt(user.id) === currentUserId;
                const department = user.department ? user.department : '<span class="text-slate-400 dark:text-slate-500">-</span>';

                const roleBadge = user.role === 'superadmin' 
                    ? `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">SUPERADMIN</span>`
                    : `<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">ADMIN</span>`;
                
                let categoriesHtml = '';
                if (user.role === 'superadmin') {
                    categoriesHtml = `<span class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold italic">Semua Kategori</span>`;
                } else if (user.categories && user.categories.length > 0) {
                    categoriesHtml = '<div class="flex flex-wrap gap-1">';
                    user.categories.forEach(cat => {
                        categoriesHtml += `<span class="px-2 py-0.5 rounded-full text-[10px] font-medium border" style="background-color: ${cat.color}15; color: ${cat.color}; border-color: ${cat.color}30;">${cat.name}</span>`;
                    });
                    categoriesHtml += '</div>';
                } else {
                    categoriesHtml = `<span class="text-[11px] text-rose-500 italic">Tanpa Akses</span>`;
                }

                const statusBadge = user.is_active == 1
                    ? `<button type="button" class="btnToggleStatus inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all ux-hover" data-id="${user.id}" title="Klik untuk mengubah status">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"></span><span>Aktif</span>
                       </button>`
                    : `<button type="button" class="btnToggleStatus inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20 hover:bg-rose-500/20 transition-all ux-hover" data-id="${user.id}" title="Klik untuk mengubah status">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 dark:bg-rose-400"></span><span>Nonaktif</span>
                       </button>`;

                const lastLogin = user.last_login_at ? user.last_login_at : '-';

                html += `
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="py-3.5 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center font-bold text-xs text-slate-700 dark:text-slate-300 shrink-0">
                                    ${user.name.substring(0, 2).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-slate-200">${user.name} ${isSelf ? '<span class="text-[10px] text-blue-600 dark:text-blue-400 font-normal">(Anda)</span>' : ''}</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">${user.email}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 text-xs">${department}</td>
                        <td class="py-3.5 px-4">${roleBadge}</td>
                        <td class="py-3.5 px-4">${categoriesHtml}</td>
                        <td class="py-3.5 px-4">${statusBadge}</td>
                        <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 text-[11px]">${lastLogin}</td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            <button type="button" class="btnEditUser p-2 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-500/10 rounded-lg ux-hover" data-id="${user.id}" title="Edit User">
                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                            </button>
                            ${!isSelf ? `
                                <button type="button" class="btnDeleteUser p-2 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg ux-hover" data-id="${user.id}" data-name="${user.name}" title="Hapus User">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            ` : ''}
                        </td>
                    </tr>
                `;
            });

            $('#userTableBody').html(html);
        }

        // Search & Filter Listeners
        $('#searchInput, #roleFilter').on('input change', function () {
            renderTable();
        });

        // Open Modal Create
        $('#btnCreateUser').on('click', function () {
            $('#userForm')[0].reset();
            $('#userId').val('');
            $('#userDepartment').val('');
            $('#userRole').val('admin');
            $('#userIsActive').val('1');
            $('.userCategoryCb').prop('checked', false);
            toggleCategoryAccessView();

            $('#modalTitle').text('Tambah User Baru');
            $('#passwordHelp').text('(Wajib diisi)');
            $('#userPassword').prop('required', true);
            $('.text-rose-500').addClass('hidden').text('');
            $('#userModal').removeClass('hidden');
        });

        // Close Modal
        $('.btnCloseModal').on('click', function () {
            $('#userModal').addClass('hidden');
        });

        // Close Modal on Backdrop Click
        $('#userModal').on('click', function (e) {
            if (e.target === this) {
                $('#userModal').addClass('hidden');
            }
        });

        // Open Modal Edit
        $(document).on('click', '.btnEditUser', function () {
            const id = $(this).data('id');
            $('.text-rose-500').addClass('hidden').text('');

            $.ajax({
                url: `<?= base_url('admin/users/get') ?>/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        const u = res.data;
                        $('#userId').val(u.id);
                        $('#userName').val(u.name);
                        $('#userEmail').val(u.email);
                        $('#userDepartment').val(u.department || '');
                        $('#userRole').val(u.role);
                        $('#userPassword').val('').prop('required', false);
                        $('#passwordHelp').text('(Opsional jika tidak mengganti)');
                        $('#userIsActive').val(u.is_active == 1 ? '1' : '0');
                        
                        $('.userCategoryCb').prop('checked', false);
                        if (u.category_ids && u.category_ids.length > 0) {
                            u.category_ids.forEach(catId => {
                                $(`.userCategoryCb[value="${catId}"]`).prop('checked', true);
                            });
                        }

                        toggleCategoryAccessView();

                        $('#modalTitle').text('Edit Data User');
                        $('#userModal').removeClass('hidden');
                    }
                }
            });
        });

        // Submit Form (Create / Update)
        $('#userForm').on('submit', function (e) {
            e.preventDefault();
            $('.text-rose-500').addClass('hidden').text('');

            const id = $('#userId').val();
            const isEdit = id !== '';
            const url = isEdit ? `<?= base_url('admin/users/update') ?>/${id}` : '<?= base_url('admin/users/store') ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        $('#userModal').addClass('hidden');
                        showToast('success', res.message);
                        loadUsers();
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

        // Toggle Status
        $(document).on('click', '.btnToggleStatus', function () {
            const id = $(this).data('id');
            if (parseInt(id) === currentUserId) return;

            $.ajax({
                url: `<?= base_url('admin/users/toggle-status') ?>/${id}`,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        showToast('success', res.message || 'Status pengguna diperbarui');
                        loadUsers();
                    }
                },
                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        showToast('error', xhr.responseJSON.message);
                    }
                }
            });
        });

        // Delete User
        $(document).on('click', '.btnDeleteUser', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');

            Swal.fire({
                title: 'Hapus User?',
                text: `Apakah Anda yakin ingin menghapus akun "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `<?= base_url('admin/users/delete') ?>/${id}`,
                        type: 'POST',
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                showToast('success', res.message);
                                loadUsers();
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
        loadUsers();
    });
</script>
<?= $this->endSection() ?>

