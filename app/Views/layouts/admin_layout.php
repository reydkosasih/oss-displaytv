<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> — Display TV</title>

    <!-- Theme Initialization Script (Prevents FOUC) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="<?= asset_url('vendor/fontawesome/css/all.min.css') ?>">
    
    <!-- Compiled Tailwind CSS v4 -->
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">

    <!-- SweetAlert2 -->
    <script src="<?= asset_url('vendor/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <!-- jQuery -->
    <script src="<?= asset_url('vendor/jquery/jquery.min.js') ?>"></script>
    <!-- SortableJS -->
    <script src="<?= asset_url('vendor/sortablejs/Sortable.min.js') ?>"></script>
    <!-- Chart.js -->
    <script src="<?= asset_url('vendor/chartjs/chart.umd.js') ?>"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Mobile sidebar overlay */
        #sidebarOverlay {
            transition: opacity 0.25s ease;
        }
        #mobileSidebar {
            transition: transform 0.25s ease;
        }
    </style>
</head>
<body class="h-full bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100 antialiased selection:bg-blue-500 selection:text-white transition-colors duration-200">
    <div class="min-h-full flex">

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm opacity-0 pointer-events-none lg:hidden"></div>

        <!-- Sidebar Navigation (Desktop: static | Mobile: slide-over drawer) -->
        <aside id="mobileSidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between shrink-0 -translate-x-full lg:translate-x-0 transition-all duration-200">
            <div>
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-sky-500 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <i class="fa-solid fa-tv text-white text-sm"></i>
                        </div>
                        <div>
                            <h1 class="font-bold text-slate-800 dark:text-slate-100 leading-tight">Display TV</h1>
                            <p class="text-[10px] tracking-wider text-blue-600 dark:text-blue-400 font-semibold uppercase">Management System</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <?php $activeSegment = service('request')->getUri()->getSegment(2); ?>
                <nav class="p-4 space-y-1">
                    <a href="<?= base_url('admin/dashboard') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'dashboard' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-slate-400 <?= $activeSegment === 'dashboard' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Dashboard</span>
                    </a>

                    <div class="pt-4 pb-1">
                        <p class="px-3.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Konten & Playlist</p>
                    </div>

                    <a href="<?= base_url('admin/content') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'content' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-photo-film w-5 text-center text-slate-400 <?= $activeSegment === 'content' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Kelola Konten</span>
                    </a>

                    <a href="<?= base_url('admin/category') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'category' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-layer-group w-5 text-center text-slate-400 <?= $activeSegment === 'category' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Kategori</span>
                    </a>

                    <a href="<?= base_url('admin/playlist') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'playlist' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-list-ol w-5 text-center text-slate-400 <?= $activeSegment === 'playlist' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Urutan Playlist</span>
                    </a>

                    <div class="pt-4 pb-1">
                        <p class="px-3.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Perangkat TV</p>
                    </div>

                    <a href="<?= base_url('admin/tv') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'tv' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-tv w-5 text-center text-slate-400 <?= $activeSegment === 'tv' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Kelola Display TV</span>
                    </a>

                    <?php if (session()->get('role') === 'superadmin'): ?>
                    <div class="pt-4 pb-1">
                        <p class="px-3.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Pengaturan System</p>
                    </div>

                    <a href="<?= base_url('admin/users') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'users' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-users-gear w-5 text-center text-slate-400 <?= $activeSegment === 'users' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Manajemen User</span>
                    </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Bottom User Card -->
            <div class="p-4 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-full bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center justify-center text-slate-700 dark:text-slate-300 shrink-0 font-semibold text-xs">
                            <?= strtoupper(substr(session()->get('name') ?? 'Admin', 0, 2)) ?>
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate"><?= session()->get('name') ?? 'Admin User' ?></p>
                            <span class="inline-block px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 capitalize">
                                <?= session()->get('role') ?? 'admin' ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-1">
                        <button type="button" class="btnThemeToggle p-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-amber-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 ux-hover" title="Toggle Light/Dark Theme">
                            <i class="themeIconSun fa-solid fa-sun text-sm text-amber-500"></i>
                            <i class="themeIconMoon fa-solid fa-moon text-sm" style="display:none"></i>
                        </button>
                        <a href="<?= base_url('auth/logout') ?>" class="p-2 text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-500/10 rounded-lg ux-hover" title="Logout">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-100 dark:bg-slate-950 transition-colors duration-200">
            <!-- Topbar -->
            <header class="h-16 bg-white/80 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-800/80 backdrop-blur flex items-center justify-between px-5 md:px-8 z-10 shrink-0">
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Button (Mobile Only) -->
                    <button type="button" id="btnToggleSidebar" class="lg:hidden p-2 rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-800 ux-hover" title="Toggle Sidebar">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <h2 class="text-base md:text-lg font-bold text-slate-800 dark:text-slate-100 tracking-tight"><?= $title ?? 'Dashboard' ?></h2>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Theme Toggle Button Topbar -->
                    <button type="button" class="btnThemeToggle p-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-amber-300 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 ux-hover" title="Toggle Light/Dark Theme">
                        <i class="themeIconSun fa-solid fa-sun text-sm text-amber-500"></i>
                        <i class="themeIconMoon fa-solid fa-moon text-sm" style="display:none"></i>
                    </button>

                    <a href="<?= base_url('/') ?>" target="_blank" class="flex items-center space-x-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 bg-white dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 px-3 py-1.5 rounded-lg shadow-xs ux-hover">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                        <span class="hidden sm:inline">Lihat Landing TV</span>
                    </a>
                </div>
            </header>

            <!-- Main Scrollable Body -->
            <main class="flex-1 overflow-y-auto p-5 md:p-8 animate-page-enter">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Global Helpers Script (Sidebar Toggle, Theme Toggle, SweetAlert Toast) -->
    <script>
        // SweetAlert2 Toast Helper
        const AppToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        function showToast(icon, title) {
            AppToast.fire({
                icon: icon,
                title: title
            });
        }

        function copyPinToClipboard(pin, buttonEl) {
            if (!pin) return;

            function onSuccess() {
                showToast('success', `PIN ${pin} disalin ke clipboard`);
                if (buttonEl) {
                    const icon = buttonEl.querySelector('i') || buttonEl;
                    const originalClass = icon.className;
                    icon.className = 'fa-solid fa-check text-emerald-500 text-xs';
                    setTimeout(() => {
                        icon.className = originalClass;
                    }, 1500);
                }
            }

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(pin).then(onSuccess).catch(() => fallbackCopyPin(pin, onSuccess));
            } else {
                fallbackCopyPin(pin, onSuccess);
            }
        }

        function fallbackCopyPin(text, cb) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.opacity = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                if (cb) cb();
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
        }

        // Sidebar Toggle
        const btnToggle = document.getElementById('btnToggleSidebar');
        const sidebar   = document.getElementById('mobileSidebar');
        const overlay   = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0', 'pointer-events-none');
        }

        if (btnToggle) {
            btnToggle.addEventListener('click', function () {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });
        }

        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        // Theme Toggle (Dark / Light persistence)
        function updateThemeIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('.themeIconSun').forEach(i => {
                i.style.display = isDark ? 'inline-block' : 'none';
            });
            document.querySelectorAll('.themeIconMoon').forEach(i => {
                i.style.display = isDark ? 'none' : 'inline-block';
            });
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcons);

        function toggleTheme() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            updateThemeIcons();
        }

        document.querySelectorAll('.btnThemeToggle').forEach(btn => {
            btn.addEventListener('click', toggleTheme);
        });
    </script>

    <!-- Global Background Upload Progress Widget -->
    <div id="globalUploadWidget" class="fixed bottom-5 right-5 z-50 w-80 sm:w-96 bg-slate-900 border border-slate-800 text-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 hidden">
        <!-- Header -->
        <div class="px-4 py-3 bg-slate-950/80 border-b border-slate-800 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-7 h-7 rounded-lg bg-blue-600/20 text-blue-400 border border-blue-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-100" id="uploadWidgetTitle">Background Uploader</h4>
                    <div class="text-[10px] text-slate-400" id="uploadWidgetStatusBadge">0%</div>
                </div>
            </div>
            <div class="flex items-center space-x-1">
                <button type="button" id="btnToggleUploadWidget" class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition-colors cursor-pointer" title="Minimize / Expand">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <button type="button" id="btnCloseUploadWidget" class="p-1.5 text-slate-400 hover:text-rose-400 rounded-lg hover:bg-slate-800 transition-colors cursor-pointer" title="Tutup">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Overall Progress Bar Line -->
        <div class="w-full bg-slate-800 h-1 overflow-hidden">
            <div id="uploadWidgetProgressBar" class="bg-gradient-to-r from-blue-500 to-sky-400 h-full transition-all duration-200" style="width: 0%"></div>
        </div>

        <!-- Collapsible Content -->
        <div id="uploadWidgetContent" class="p-3 space-y-3 max-h-60 overflow-y-auto">
            <div id="uploadWidgetItemsContainer" class="space-y-2">
                <!-- Queue item rows dynamically rendered by background-uploader.js -->
            </div>
            <div class="pt-2 border-t border-slate-800/80 flex items-center justify-between text-[11px]">
                <button type="button" id="btnCancelUploadQueue" class="text-rose-400 hover:text-rose-300 font-semibold cursor-pointer">
                    <i class="fa-solid fa-ban mr-1"></i>Batal Upload
                </button>
                <span class="text-slate-500 text-[10px]">Otomatis memuat data</span>
            </div>
        </div>
    </div>

    <!-- Background Uploader Script Engine -->
    <script src="<?= asset_url('js/background-uploader.js') ?>"></script>
    <script>
        $(document).ready(function() {
            if (window.BackgroundUploader) {
                window.BackgroundUploader.init({
                    baseUrl: '<?= base_url() ?>/'
                });
            }
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>

