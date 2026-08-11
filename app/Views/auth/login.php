<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login Admin' ?> — Display TV</title>
    
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

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col items-center justify-center p-4 sm:p-6 relative overflow-x-hidden select-none antialiased transition-colors duration-300">

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
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-gradient-to-tr from-blue-600 to-sky-500 shadow-xl shadow-blue-500/25 mb-3 sm:mb-4 transform hover:scale-105 active:scale-95 transition-all duration-300">
                <i class="fa-solid fa-tv text-xl sm:text-2xl text-white"></i>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Display TV Portal</h1>
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
                    class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-sky-600 hover:from-blue-500 hover:to-sky-500 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-600/25 transition-all duration-200 flex items-center justify-center space-x-2 active:scale-[0.98] ux-hover">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>

        <!-- Footer Info -->
        <p class="text-center text-slate-500 text-xs mt-6">
            &copy; <?= date('Y') ?> TV Slideshow Display App. All rights reserved.
        </p>
    </div>

    <script>
        // Theme Toggle Logic
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
