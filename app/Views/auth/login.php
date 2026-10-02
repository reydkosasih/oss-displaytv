<!DOCTYPE html>
<html lang="id" class="dark h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $title ?? 'Login Admin' ?> — Display TV</title>
    
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
    <!-- Global Theme Toggle Controller (Circle Effect) -->
    <script src="<?= asset_url('js/theme-toggle.js') ?>"></script>

    <style>
        html, body {
            height: 100%;
            min-height: 100dvh;
            background-color: #f8fafc;
        }
        html.dark, html.dark body {
            background-color: #020617;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-dvh bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col items-center justify-center p-4 sm:p-6 relative overflow-x-hidden select-none antialiased transition-colors duration-300" style="padding-top: max(1rem, env(safe-area-inset-top, 0px)); padding-bottom: max(1rem, env(safe-area-inset-bottom, 0px));">

    <!-- Ambient Glowing Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-1/4 w-80 sm:w-96 h-80 sm:h-96 bg-blue-600/20 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 sm:w-96 h-80 sm:h-96 bg-sky-600/15 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    </div>

    <div class="w-full max-w-md relative z-10 animate-page-enter py-4">
        <!-- Back to Display Network Link & Theme Switcher -->
        <div class="flex items-center justify-between mb-6">
            <a href="<?= base_url() ?>" class="inline-flex items-center space-x-2 text-xs font-medium text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors group">
                <i class="fa-solid fa-arrow-left text-[11px] group-hover:-translate-x-1 transition-transform"></i>
                <span>Kembali ke Display Network</span>
            </a>

            <!-- Theme Toggle Button -->
            <button type="button" class="btnThemeToggle p-2 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-amber-300 rounded-xl hover:bg-slate-200/80 dark:hover:bg-slate-800/80 border border-slate-200 dark:border-slate-800 transition-all ux-hover" title="Toggle Light/Dark Theme">
                <i class="themeIconSun fa-solid fa-sun text-sm text-amber-500"></i>
                <i class="themeIconMoon fa-solid fa-moon text-sm text-slate-600 dark:text-slate-400" style="display:none"></i>
            </button>
        </div>

        <!-- Logo & Header -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center p-3 rounded-2xl bg-white/90 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 shadow-lg shadow-slate-900/5 dark:shadow-slate-950/50 mb-3 sm:mb-4 transform hover:scale-105 active:scale-95 transition-all duration-300">
                <img src="<?= base_url('assets/logo/logo.png') ?>" alt="Logo OSS" class="h-10 sm:h-12 w-auto max-w-35 object-contain">
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Portal Admin</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Sistem Manajemen Display & Content Slideshow</p>
        </div>

        <!-- Glassmorphism Card -->
        <div class="bg-white/80 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200 dark:border-slate-800/80 rounded-2xl p-5 sm:p-8 shadow-2xl shadow-slate-950/20 dark:shadow-slate-950/80">
            
            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-300 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 dark:text-rose-400 text-base shrink-0"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                <div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-600 dark:text-amber-300 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 dark:text-amber-400 text-base shrink-0"></i>
                    <span><?= session()->getFlashdata('warning') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
                <div class="mb-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-300 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 text-base shrink-0"></i>
                    <span><?= session()->getFlashdata('info') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-300 text-xs flex items-center space-x-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 dark:text-emerald-400 text-base shrink-0"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form action="<?= base_url('login') ?>" method="POST" class="space-y-4 sm:space-y-5">
                <?= csrf_field() ?>

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Alamat Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-envelope text-xs sm:text-sm"></i>
                        </div>
                        <input type="email" id="email" name="email" value="<?= old('email') ?>" required autofocus
                            placeholder="nama@domain.com"
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 sm:mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="fa-solid fa-lock text-xs sm:text-sm"></i>
                        </div>
                        <input type="password" id="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-10 py-2.5 bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 rounded-xl text-xs sm:text-sm text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all">
                        
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 ux-hover">
                            <i class="fa-solid fa-eye text-xs sm:text-sm" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                    class="w-full py-3 px-4 bg-linear-to-r from-blue-600 to-sky-600 hover:from-blue-500 hover:to-sky-500 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-600/25 transition-all duration-200 flex items-center justify-center space-x-2 active:scale-[0.98] ux-hover">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Footer Info -->
        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; <?= date('Y') ?> Rey Dwi Kosasih. All rights reserved.
        </p>
    </div>

    <script>


        // Password visibility toggle
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
