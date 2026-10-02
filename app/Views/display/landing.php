<!DOCTYPE html>
<html lang="id" class="dark h-full bg-slate-50 dark:bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $title ?? 'Portal TV Display' ?></title>
    
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
    <!-- jQuery -->
    <script src="<?= asset_url('vendor/jquery/jquery.min.js') ?>"></script>
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
<body class="h-full min-h-dvh bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-blue-500 selection:text-white flex flex-col justify-between relative overflow-x-hidden transition-colors duration-300">

    <!-- Ambient Glow Backgrounds -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-200 h-87.5 bg-linear-to-b from-blue-600/20 via-sky-600/10 to-transparent rounded-full blur-3xl"></div>
    </div>

    <!-- Header Navigation -->
    <header class="relative z-10 border-b border-slate-200 dark:border-slate-800/80 bg-white/80 dark:bg-slate-900/40 backdrop-blur-xl transition-colors duration-300 box-content" style="padding-top: env(safe-area-inset-top, 0px);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 rounded-2xl bg-white/90 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center p-2 shadow-lg shadow-slate-900/5 dark:shadow-slate-950/50 shrink-0">
                    <img src="<?= base_url('assets/logo/logo.png') ?>" alt="Logo OSS" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-extrabold text-base sm:text-lg text-slate-900 dark:text-white tracking-tight leading-none">OSS - TV Display Network</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-1 sm:line-clamp-none">Pilih TV Display untuk Memulai Slideshow Penayangan</p>
                </div>
            </div>

            <div class="flex items-center space-x-2.5 shrink-0">
                <!-- Theme Toggle Button -->
                <button type="button" class="btnThemeToggle p-2.5 text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-amber-300 rounded-xl hover:bg-slate-200/80 dark:hover:bg-slate-800/80 border border-slate-200 dark:border-slate-800 transition-all ux-hover" title="Toggle Light/Dark Theme">
                    <i class="themeIconSun fa-solid fa-sun text-sm text-amber-500"></i>
                    <i class="themeIconMoon fa-solid fa-moon text-sm text-slate-600 dark:text-slate-400" style="display:none"></i>
                </button>

                <a href="<?= base_url('login') ?>" class="px-3.5 py-2 sm:px-4 sm:py-2 bg-white dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/80 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold transition-all flex items-center space-x-2 shadow-sm ux-hover">
                    <i class="fa-solid fa-lock text-[11px] text-blue-500 dark:text-blue-400"></i>
                    <span>Portal Admin</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content Body -->
    <main class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10 flex-1 w-full animate-page-enter">

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="mb-8 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-300 text-xs flex items-center space-x-3 max-w-2xl mx-auto">
                <i class="fa-solid fa-circle-exclamation text-rose-500 dark:text-rose-400 text-lg shrink-0"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Search & Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Daftar Display TV</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Klik pada kartu TV untuk memasukkan PIN 6-digit dan membuka tampilan fullscreen.</p>
            </div>

            <div class="relative w-full sm:w-80">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </div>
                <input type="text" id="searchTvInput" placeholder="Cari TV berdasarkan nama atau lokasi..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-slate-900/80 border border-slate-200 dark:border-slate-800 rounded-xl text-xs text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all shadow-inner">
            </div>
        </div>

        <!-- TV Cards Grid -->
        <?php if (empty($tvs)): ?>
            <div class="text-center py-20 bg-slate-100/60 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-800/80 rounded-3xl p-8 max-w-xl mx-auto">
                <i class="fa-solid fa-tv text-4xl text-slate-400 dark:text-slate-600 mb-4 block"></i>
                <h3 class="text-base font-bold text-slate-700 dark:text-slate-300">Belum Ada Display TV Aktif</h3>
                <p class="text-xs text-slate-500 mt-1">Administrator dapat menambahkan perangkat TV melalui portal admin.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="tvCardGrid">
                <?php foreach ($tvs as $tv): ?>
                    <div class="tvCard group bg-white/80 dark:bg-slate-900/70 backdrop-blur-xl border border-slate-200/90 dark:border-slate-800/90 hover:border-blue-500/50 rounded-3xl overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 active:scale-[0.99] flex flex-col justify-between"
                         data-name="<?= strtolower(esc((string) ($tv['name'] ?? ''))) ?>" 
                         data-location="<?= strtolower(esc((string) ($tv['location'] ?? ''))) ?>">
                        
                        <div>
                            <!-- Card Header Image / Thumbnail -->
                            <div class="h-44 bg-slate-100 dark:bg-slate-950 relative overflow-hidden flex items-center justify-center border-b border-slate-200 dark:border-slate-800/60">
                                <?php if ($tv['thumbnail_url']): ?>
                                    <img src="<?= $tv['thumbnail_url'] ?>" alt="<?= esc($tv['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-linear-to-t from-slate-900/80 dark:from-slate-900 via-transparent to-black/30"></div>
                                <?php else: ?>
                                    <div class="absolute inset-0 bg-linear-to-br from-blue-100/50 dark:from-blue-950/40 via-slate-100 dark:via-slate-950 to-slate-200 dark:to-slate-950 flex items-center justify-center">
                                        <i class="fa-solid fa-tv text-5xl text-slate-300 dark:text-slate-800 group-hover:text-blue-500/30 transition-colors duration-500"></i>
                                    </div>
                                <?php endif; ?>

                                <!-- Status Badge -->
                                <div class="absolute top-4 left-4">
                                    <?php if ($tv['is_online']): ?>
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-700 dark:text-emerald-300 border border-emerald-500/30 backdrop-blur-md">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-ping"></span>
                                            <span>ONLINE</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[10px] font-semibold bg-slate-200/80 dark:bg-slate-900/80 text-slate-700 dark:text-slate-400 border border-slate-300/80 dark:border-slate-700/80 backdrop-blur-md">
                                            <span class="w-2 h-2 rounded-full bg-slate-400 dark:bg-slate-500"></span>
                                            <span>STANDBY</span>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors tracking-tight"><?= esc($tv['name']) ?></h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center">
                                    <i class="fa-solid fa-location-dot text-blue-500 dark:text-blue-400 mr-1.5 text-xs"></i>
                                    <span><?= esc($tv['location'] ?: 'Lokasi Tidak Ditentukan') ?></span>
                                </p>

                                <!-- Categories Badges -->
                                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800/60 flex flex-wrap gap-1.5">
                                    <?php if (!empty($tv['categories'])): ?>
                                        <?php foreach ($tv['categories'] as $cat): ?>
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-semibold border"
                                                  style="background-color: <?= $cat['color'] ?>15; color: <?= $cat['color'] ?>; border-color: <?= $cat['color'] ?>30;">
                                                <?= esc($cat['name']) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">Belum ada kategori</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Action -->
                        <div class="p-6 pt-0">
                            <button type="button" class="btnOpenPinModal w-full py-3 px-4 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center space-x-2 active:scale-[0.98] ux-hover"
                                    data-id="<?= $tv['id'] ?>" 
                                    data-name="<?= esc($tv['name']) ?>"
                                    data-location="<?= esc($tv['location'] ?: '-') ?>">
                                <i class="fa-solid fa-key text-xs"></i>
                                <span>Buka Slideshow (Input PIN)</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200 dark:border-slate-800/60 py-6 text-center text-xs text-slate-500">
        <p>&copy; <?= date('Y') ?> TV Slideshow Display Network. All rights reserved.</p>
    </footer>

    <!-- PIN Modal / Bottom Sheet Drawer (6-Digit Auto Advance Input) -->
    <div id="pinModal" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-slate-950/60 dark:bg-slate-950/85 backdrop-blur-md hidden p-0 sm:p-4 transition-opacity duration-300">
        <div class="bg-white dark:bg-slate-900 border-t sm:border border-slate-200 dark:border-slate-800 rounded-t-3xl sm:rounded-3xl w-full max-w-md p-5 sm:p-8 shadow-2xl relative transform transition-all duration-300 text-center animate-slide-up sm:animate-none max-h-[90vh] flex flex-col">
            
            <!-- Mobile Bottom Sheet Handle Bar -->
            <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700/80 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

            <button type="button" id="btnClosePinModal" class="absolute top-4 right-4 sm:top-6 sm:right-6 text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>

            <!-- TV Icon & Info -->
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 mx-auto mb-3 sm:mb-4 shrink-0">
                <i class="fa-solid fa-key text-xl sm:text-2xl"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-extrabold text-slate-900 dark:text-white" id="modalTvName">Masukkan PIN 6-Digit</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1" id="modalTvLocation">Lokasi Display TV</p>

            <!-- Error Feedback -->
            <div id="pinErrorMsg" class="mt-4 p-3 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-semibold hidden flex items-center justify-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-sm"></i>
                <span id="pinErrorText">PIN salah</span>
            </div>

            <!-- PIN Form -->
            <form id="pinForm" class="mt-5 sm:mt-6 flex-1 overflow-y-auto overflow-x-hidden p-1">
                <input type="hidden" id="modalTvId">

                <!-- 6 PIN Input Boxes -->
                <div class="flex items-center justify-center space-x-1.5 sm:space-x-2.5 mb-6" id="pinBoxContainer">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="pinBox w-9 sm:w-12 h-12 sm:h-14 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl text-center text-lg sm:text-xl font-mono font-bold text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all hover:border-blue-400 dark:hover:border-blue-500">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="pinBox w-9 sm:w-12 h-12 sm:h-14 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl text-center text-lg sm:text-xl font-mono font-bold text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all hover:border-blue-400 dark:hover:border-blue-500">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="pinBox w-9 sm:w-12 h-12 sm:h-14 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl text-center text-lg sm:text-xl font-mono font-bold text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all hover:border-blue-400 dark:hover:border-blue-500">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="pinBox w-9 sm:w-12 h-12 sm:h-14 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl text-center text-lg sm:text-xl font-mono font-bold text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all hover:border-blue-400 dark:hover:border-blue-500">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="pinBox w-9 sm:w-12 h-12 sm:h-14 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl text-center text-lg sm:text-xl font-mono font-bold text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all hover:border-blue-400 dark:hover:border-blue-500">
                    <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" class="pinBox w-9 sm:w-12 h-12 sm:h-14 bg-slate-100 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl sm:rounded-2xl text-center text-lg sm:text-xl font-mono font-bold text-blue-600 dark:text-blue-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all hover:border-blue-400 dark:hover:border-blue-500">
                </div>

                <button type="submit" id="btnSubmitPin" class="w-full py-3.5 px-4 bg-linear-to-r from-blue-600 to-sky-600 hover:from-blue-500 hover:to-sky-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 transition-all duration-200 flex items-center justify-center space-x-2 active:scale-[0.98]">
                    <span>Verifikasi PIN & Buka Display</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {



            // Search TV Card Filter
            $('#searchTvInput').on('input', function () {
                const search = $(this).val().toLowerCase();
                $('.tvCard').each(function () {
                    const name = $(this).data('name');
                    const loc = $(this).data('location');
                    if (name.includes(search) || loc.includes(search)) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            });

            // Open PIN Modal
            $('.btnOpenPinModal').on('click', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const loc = $(this).data('location');

                $('#modalTvId').val(id);
                $('#modalTvName').text(name);
                $('#modalTvLocation').text(loc);
                $('#pinErrorMsg').addClass('hidden');
                $('.pinBox').val('');

                $('#pinModal').removeClass('hidden');
                setTimeout(() => $('.pinBox').first().focus(), 100);
            });

            // Close PIN Modal
            $('#btnClosePinModal').on('click', function () {
                $('#pinModal').addClass('hidden');
            });

            // Close PIN Modal on Backdrop Click
            $('#pinModal').on('click', function (e) {
                if (e.target === this) {
                    $('#pinModal').addClass('hidden');
                }
            });

            // PIN Box Auto-Advance & Backspace & Paste
            const pinBoxes = $('.pinBox');

            pinBoxes.on('input', function (e) {
                const value = $(this).val();
                if (!/^[0-9]$/.test(value)) {
                    $(this).val('');
                    return;
                }
                const index = pinBoxes.index(this);
                if (index < 5 && value) {
                    pinBoxes.eq(index + 1).focus();
                }
            });

            pinBoxes.on('keydown', function (e) {
                const index = pinBoxes.index(this);
                if (e.key === 'Backspace') {
                    if ($(this).val() === '' && index > 0) {
                        pinBoxes.eq(index - 1).focus();
                    }
                }
            });

            // Paste PIN Support
            pinBoxes.first().on('paste', function (e) {
                const pastedData = (e.originalEvent.clipboardData || window.clipboardData).getData('text').trim();
                if (/^\d{6}$/.test(pastedData)) {
                    e.preventDefault();
                    for (let i = 0; i < 6; i++) {
                        pinBoxes.eq(i).val(pastedData[i]);
                    }
                    pinBoxes.last().focus();
                }
            });

            // Submit PIN Verification
            $('#pinForm').on('submit', function (e) {
                e.preventDefault();
                $('#pinErrorMsg').addClass('hidden');

                let pin = '';
                pinBoxes.each(function () {
                    pin += $(this).val();
                });

                if (pin.length !== 6) {
                    $('#pinErrorText').text('Silakan isi seluruh 6-digit PIN.');
                    $('#pinErrorMsg').removeClass('hidden');
                    return;
                }

                const tvId = $('#modalTvId').val();

                $.ajax({
                    url: '<?= base_url('display/verify-pin') ?>',
                    type: 'POST',
                    data: {
                        tv_id: tvId,
                        pin: pin
                    },
                    dataType: 'json',
                    success: function (res) {
                        if (res.status === 'success') {
                            // Save PIN token in LocalStorage for TV device persistence
                            localStorage.setItem('tv_verified_' + tvId, JSON.stringify({
                                pin: pin,
                                verified_at: Date.now()
                            }));

                            window.location.href = res.redirect_url;
                        }
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            $('#pinErrorText').text(xhr.responseJSON.message);
                        } else {
                            $('#pinErrorText').text('Terjadi kesalahan verifikasi PIN.');
                        }
                        $('#pinErrorMsg').removeClass('hidden');
                        pinBoxes.val('');
                        pinBoxes.first().focus();
                    }
                });
            });
        });
    </script>
</body>
</html>
