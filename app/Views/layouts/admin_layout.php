<!DOCTYPE html>
<html lang="id" class="dark h-full bg-slate-100 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $title ?? 'Admin Dashboard' ?> — Display TV</title>
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">

    <!-- Theme Color & Mobile Status Bar (Android & iOS) -->
    <meta name="theme-color" media="(prefers-color-scheme: light)" content="#ffffff">
    <meta name="theme-color" media="(prefers-color-scheme: dark)" content="#020617">
    <meta name="theme-color" id="metaThemeColor" content="#020617">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="OSS Display TV">

    <!-- Icons & Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/icons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/icons/icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= base_url('assets/icons/icon-512x512.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/logo/logo.png') ?>">

    <!-- Theme Initialization Script (Prevents FOUC & Syncs Status Bar Theme-Color) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const isDark = savedTheme !== 'light';
            if (!isDark) {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
            const targetColor = isDark ? '#020617' : '#ffffff';
            document.querySelectorAll('meta[name="theme-color"]').forEach(meta => {
                meta.setAttribute('content', targetColor);
            });
        })();
    </script>
    
    <!-- Self-hosted Inter Font: Preload critical woff2 subset (eliminates CDN roundtrip) -->
    <link rel="preload" href="<?= base_url('assets/fonts/inter-latin.woff2') ?>" as="font" type="font/woff2" crossorigin>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="<?= asset_url('vendor/fontawesome/css/all.min.css') ?>">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="<?= asset_url('vendor/select2/css/select2.min.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/select2-tailwind.css') ?>">

    <!-- Compiled Tailwind CSS v4 (Critical CSS — stays in head) -->
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">

    <style>
        html, body {
            height: 100%;
            background-color: #f1f5f9;
        }
        html.dark, html.dark body {
            background-color: #020617;
        }
        body { font-family: 'Inter', sans-serif; }

        /* Mobile sidebar overlay */
        #sidebarOverlay {
            transition: opacity 0.25s ease;
        }
        #mobileSidebar {
            transition: transform 0.25s ease;
        }
        @media (max-width: 1023px) {
            .main-content-scroll {
                padding-bottom: calc(7rem + env(safe-area-inset-bottom, 0px)) !important;
            }
        }
    </style>
</head>
<body class="h-full bg-slate-100 text-slate-800 dark:bg-slate-950 dark:text-slate-100 antialiased selection:bg-blue-500 selection:text-white transition-colors duration-200 overflow-hidden">
    <div class="h-screen h-dvh flex overflow-hidden">

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 z-40 bg-slate-950/80 backdrop-blur-sm opacity-0 pointer-events-none lg:hidden"></div>

        <!-- Sidebar Navigation (Desktop: static | Mobile: slide-over drawer) -->
        <aside id="mobileSidebar" class="fixed lg:static inset-y-0 left-0 z-50 w-64 h-full bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col justify-between shrink-0 -translate-x-full lg:translate-x-0 transition-all duration-200 overflow-hidden">
            <div class="flex-1 flex flex-col min-h-0 overflow-y-auto">
                <!-- Brand Header -->
                <div class="h-16 flex items-center justify-between px-6 border-b border-slate-200 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 backdrop-blur shrink-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-white/90 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center p-1.5 shadow-sm">
                            <img src="<?= base_url('assets/logo/logo.png') ?>" alt="Logo OSS" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h1 class="font-bold text-slate-800 dark:text-slate-100 leading-tight">OSS TV</h1>
                            <p class="text-[10px] tracking-wider text-blue-600 dark:text-blue-400 font-semibold uppercase">Management System</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Links -->
                <?php $activeSegment = service('request')->getUri()->getSegment(2); ?>
                <nav class="p-4 space-y-1 flex-1">
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

                    <div class="pt-4 pb-1">
                        <p class="px-3.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Aktivitas & Log</p>
                    </div>

                    <a href="<?= base_url('admin/audit-logs') ?>" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 border border-transparent text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white ux-hover <?= $activeSegment === 'audit-logs' ? 'bg-blue-600/20 text-blue-600 dark:text-blue-400 border-blue-500/30 font-semibold' : '' ?>">
                        <i class="fa-solid fa-scroll w-5 text-center text-slate-400 <?= $activeSegment === 'audit-logs' ? 'text-blue-600 dark:text-blue-400' : '' ?>"></i>
                        <span>Audit Logs</span>
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
            <div class="p-4 border-t border-slate-200 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/40 shrink-0">
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
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden bg-slate-100 dark:bg-slate-950 transition-colors duration-200">
            <!-- Topbar -->
            <header class="h-16 box-content bg-white/80 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-800/80 backdrop-blur flex items-center justify-between px-4 sm:px-6 md:px-8 z-10 shrink-0" style="padding-top: env(safe-area-inset-top, 0px);">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <!-- Hamburger Button (Mobile Only) -->
                    <button type="button" id="btnToggleSidebar" class="lg:hidden p-2 rounded-lg text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-slate-800 ux-hover" title="Toggle Sidebar">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <h2 class="text-base md:text-lg font-bold text-slate-800 dark:text-slate-100 tracking-tight"><?= $title ?? 'Dashboard' ?></h2>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <!-- Notification Bell with Active Indicator -->
                    <a href="<?= base_url('admin/audit-logs') ?>" class="relative p-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-800 ux-hover inline-flex items-center justify-center" title="Log Aktivitas & Notifikasi">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 animate-pulse"></span>
                    </a>

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
            <main class="main-content-scroll flex-1 overflow-y-auto p-4 sm:p-6 md:p-8 pb-28 lg:pb-8 min-h-0 animate-page-enter">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- =========================================================================
         Mobile Floating Bottom Navigation Bar (Visible only on < lg screens)
         Glassmorphism floating island with elevated center FAB & Safe Area Inset
         ========================================================================= -->
    <nav class="fixed left-4 right-4 z-40 max-w-md mx-auto bg-white/85 dark:bg-slate-900/85 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800/80 shadow-2xl rounded-2xl px-2 py-1.5 lg:hidden transition-all duration-200" style="bottom: calc(1rem + env(safe-area-inset-bottom, 0px));" aria-label="Mobile Navigation">
        <div class="grid grid-cols-5 items-center">
            <!-- 1. Dashboard / Beranda -->
            <a href="<?= base_url('admin/dashboard') ?>" 
               class="flex flex-col items-center justify-center min-h-[48px] py-1 rounded-xl transition-all duration-150 active:scale-90 <?= $activeSegment === 'dashboard' ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 font-medium' ?>">
                <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                    <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                    <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                    <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                </svg>
                <span class="text-[10px] tracking-tight">Beranda</span>
                <?php if ($activeSegment === 'dashboard'): ?>
                    <span class="w-1 h-1 rounded-full bg-blue-600 dark:bg-blue-400 mt-0.5"></span>
                <?php endif; ?>
            </a>

            <!-- 2. Konten Media -->
            <a href="<?= base_url('admin/content') ?>" 
               class="flex flex-col items-center justify-center min-h-[48px] py-1 rounded-xl transition-all duration-150 active:scale-90 <?= $activeSegment === 'content' ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 font-medium' ?>">
                <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"></rect>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                </svg>
                <span class="text-[10px] tracking-tight">Konten</span>
                <?php if ($activeSegment === 'content'): ?>
                    <span class="w-1 h-1 rounded-full bg-blue-600 dark:bg-blue-400 mt-0.5"></span>
                <?php endif; ?>
            </a>

            <!-- 3. Center Elevated FAB (+ Tambah / Aksi Cepat) -->
            <div class="flex items-center justify-center">
                <button type="button" 
                        id="btnMobileActionSheetToggle" 
                        class="relative -top-3 w-12 h-12 rounded-full bg-linear-to-tr from-blue-600 via-indigo-600 to-violet-500 text-white shadow-lg shadow-blue-500/30 flex items-center justify-center border-4 border-slate-100 dark:border-slate-950 active:scale-90 hover:scale-105 transition-all duration-150 focus:outline-hidden cursor-pointer"
                        aria-label="Menu Aksi Cepat" 
                        title="Aksi Cepat">
                    <svg id="fabPlusIcon" class="w-6 h-6 transition-transform duration-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5v14"></path>
                    </svg>
                </button>
            </div>

            <!-- 4. Urutan Playlist -->
            <a href="<?= base_url('admin/playlist') ?>" 
               class="flex flex-col items-center justify-center min-h-[48px] py-1 rounded-xl transition-all duration-150 active:scale-90 <?= $activeSegment === 'playlist' ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 font-medium' ?>">
                <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="10" x2="21" y1="6" y2="6"></line>
                    <line x1="10" x2="21" y1="12" y2="12"></line>
                    <line x1="10" x2="21" y1="18" y2="18"></line>
                    <path d="M4 6h1v4"></path>
                    <path d="M4 10h2"></path>
                    <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                </svg>
                <span class="text-[10px] tracking-tight">Playlist</span>
                <?php if ($activeSegment === 'playlist'): ?>
                    <span class="w-1 h-1 rounded-full bg-blue-600 dark:bg-blue-400 mt-0.5"></span>
                <?php endif; ?>
            </a>

            <!-- 5. Display TV -->
            <a href="<?= base_url('admin/tv') ?>" 
               class="flex flex-col items-center justify-center min-h-[48px] py-1 rounded-xl transition-all duration-150 active:scale-90 <?= $activeSegment === 'tv' ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 font-medium' ?>">
                <svg class="w-5 h-5 mb-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="20" height="15" x="2" y="7" rx="2" ry="2"></rect>
                    <polyline points="17 2 12 7 7 2"></polyline>
                </svg>
                <span class="text-[10px] tracking-tight">Display TV</span>
                <?php if ($activeSegment === 'tv'): ?>
                    <span class="w-1 h-1 rounded-full bg-blue-600 dark:bg-blue-400 mt-0.5"></span>
                <?php endif; ?>
            </a>
        </div>
    </nav>

    <!-- =========================================================================
         Mobile Quick Action Sheet Drawer (Interactive Sheet triggered by FAB)
         ========================================================================= -->
    <div id="mobileActionSheetOverlay" class="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-250 lg:hidden flex flex-col justify-end">
        <div class="w-full max-w-lg mx-auto p-4">
            <div id="mobileActionSheetPanel" class="w-full bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl p-5 shadow-2xl translate-y-full transition-transform duration-300 ease-out">
                <!-- Drag Handle -->
                <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4"></div>
                
                <!-- Action Sheet Header -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-slate-900 dark:text-white">Aksi Cepat Sistem</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pintasan navigasi dan manajemen Display TV</p>
                    </div>
                    <button type="button" id="btnCloseActionSheet" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors" title="Tutup">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Action Grid -->
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <!-- Upload Konten Baru -->
                    <a href="<?= base_url('admin/content') ?>" class="flex flex-col p-3.5 rounded-2xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50 hover:bg-blue-100/50 dark:hover:bg-blue-900/40 transition-all group active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center mb-2.5 shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white">Upload Konten</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Media & Chart baru</span>
                    </a>

                    <!-- Atur Urutan Playlist -->
                    <a href="<?= base_url('admin/playlist') ?>" class="flex flex-col p-3.5 rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-900/50 hover:bg-indigo-100/50 dark:hover:bg-indigo-900/40 transition-all group active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center mb-2.5 shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="10" x2="21" y1="6" y2="6"></line>
                                <line x1="10" x2="21" y1="12" y2="12"></line>
                                <line x1="10" x2="21" y1="18" y2="18"></line>
                                <path d="M4 6h1v4"></path>
                                <path d="M4 10h2"></path>
                                <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white">Atur Playlist</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Susun urutan tayang</span>
                    </a>

                    <!-- Kelola Display TV -->
                    <a href="<?= base_url('admin/tv') ?>" class="flex flex-col p-3.5 rounded-2xl bg-sky-50/60 dark:bg-sky-950/30 border border-sky-100 dark:border-sky-900/50 hover:bg-sky-100/50 dark:hover:bg-sky-900/40 transition-all group active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center mb-2.5 shadow-md shadow-sky-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="15" x="2" y="7" rx="2" ry="2"></rect>
                                <polyline points="17 2 12 7 7 2"></polyline>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white">Kelola TV</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Display & PIN akses</span>
                    </a>

                    <!-- Buka TV Display Publik -->
                    <a href="<?= base_url('/') ?>" target="_blank" class="flex flex-col p-3.5 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 hover:bg-emerald-100/50 dark:hover:bg-emerald-900/40 transition-all group active:scale-95">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center mb-2.5 shadow-md shadow-emerald-500/20 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="2" y1="12" x2="22" y2="12"></line>
                                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-900 dark:text-white">TV Publik</span>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">Landing page display</span>
                    </a>
                </div>

                <!-- Secondary Link: Audit Logs & System Status -->
                <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <a href="<?= base_url('admin/audit-logs') ?>" class="flex items-center space-x-1.5 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                        <i class="fa-solid fa-scroll text-xs"></i>
                        <span>Audit Log Akses</span>
                    </a>
                    <span class="flex items-center space-x-1.5 font-medium text-emerald-600 dark:text-emerald-400">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Sistem Siap</span>
                    </span>
                </div>
            </div>
        </div>
    </div>



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
            <div id="uploadWidgetProgressBar" class="bg-linear-to-r from-blue-500 to-sky-400 h-full transition-all duration-200" style="width: 0%"></div>
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

    <!-- Background Uploader init script (runs after vendor JS loaded above) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.BackgroundUploader) {
                window.BackgroundUploader.init({
                    baseUrl: '<?= base_url() ?>/'
                });
            }
        });
    </script>

    <!-- Admin Session Timeout init script (runs after vendor JS loaded above) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.AdminSessionTimeout) {
                window.AdminSessionTimeout.init({
                    idleDuration: 15 * 60 * 1000,
                    warningDuration: 60 * 1000,
                    keepAliveUrl: '<?= base_url('admin/keep-alive') ?>',
                    logoutUrl: '<?= base_url('auth/logout?reason=timeout') ?>',
                    manualLogoutUrl: '<?= base_url('auth/logout') ?>'
                });
            }
        });
    </script>

    <!-- =========================================================================
         Vendor JS — Di-load SYNC di akhir body (setelah semua HTML di-render).
         HARUS di sini, SEBELUM renderSection('scripts') agar $ dan Swal tersedia
         untuk semua inline scripts di page-level views.
         Script sync di akhir body TIDAK memblokir FCP karena HTML sudah di-render.
         ========================================================================= -->
    <script src="<?= asset_url('vendor/sweetalert2/sweetalert2.all.min.js') ?>"></script>
    <script src="<?= asset_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= asset_url('vendor/select2/js/select2.min.js') ?>"></script>
    <script src="<?= asset_url('vendor/sortablejs/Sortable.min.js') ?>"></script>
    <script src="<?= asset_url('vendor/chartjs/chart.umd.js') ?>"></script>
    <script src="<?= asset_url('js/theme-toggle.js') ?>"></script>
    <script src="<?= asset_url('js/background-uploader.js') ?>"></script>
    <script src="<?= asset_url('js/admin-session-timeout.js') ?>"></script>

    <!-- Global Modals Portal Section -->
    <?= $this->renderSection('modals') ?>

    <?= $this->renderSection('scripts') ?>


    <!-- Global Helpers Script (AppToast, sidebar, action sheet) -->
    <!-- Posisi: SETELAH vendor scripts — Swal dan $ sudah tersedia di sini -->
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

        // Mobile Quick Action Sheet Controller
        const btnToggleActionSheet = document.getElementById('btnMobileActionSheetToggle');
        const btnCloseActionSheet  = document.getElementById('btnCloseActionSheet');
        const actionSheetOverlay   = document.getElementById('mobileActionSheetOverlay');
        const actionSheetPanel     = document.getElementById('mobileActionSheetPanel');
        const fabPlusIcon          = document.getElementById('fabPlusIcon');

        function openActionSheet() {
            if (!actionSheetOverlay || !actionSheetPanel) return;
            actionSheetOverlay.classList.remove('opacity-0', 'pointer-events-none');
            actionSheetOverlay.classList.add('opacity-100', 'pointer-events-auto');
            actionSheetPanel.classList.remove('translate-y-full');
            actionSheetPanel.classList.add('translate-y-0');
            if (fabPlusIcon) {
                fabPlusIcon.style.transform = 'rotate(45deg)';
            }
        }

        function closeActionSheet() {
            if (!actionSheetOverlay || !actionSheetPanel) return;
            actionSheetPanel.classList.remove('translate-y-0');
            actionSheetPanel.classList.add('translate-y-full');
            actionSheetOverlay.classList.remove('opacity-100', 'pointer-events-auto');
            actionSheetOverlay.classList.add('opacity-0', 'pointer-events-none');
            if (fabPlusIcon) {
                fabPlusIcon.style.transform = 'rotate(0deg)';
            }
        }

        if (btnToggleActionSheet) {
            btnToggleActionSheet.addEventListener('click', function (e) {
                e.stopPropagation();
                if (actionSheetOverlay && actionSheetOverlay.classList.contains('opacity-100')) {
                    closeActionSheet();
                } else {
                    openActionSheet();
                }
            });
        }

        if (btnCloseActionSheet) {
            btnCloseActionSheet.addEventListener('click', closeActionSheet);
        }

        if (actionSheetOverlay) {
            actionSheetOverlay.addEventListener('click', function (e) {
                if (e.target === actionSheetOverlay) {
                    closeActionSheet();
                }
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && actionSheetOverlay && actionSheetOverlay.classList.contains('opacity-100')) {
                closeActionSheet();
            }
        });
    </script>
</body>
</html>
