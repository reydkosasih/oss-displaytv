<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Flash Message Notifications -->
<?php if (session()->getFlashdata('success')): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showToast('success', '<?= esc(session()->getFlashdata('success')) ?>');
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showToast('error', '<?= esc(session()->getFlashdata('error')) ?>');
        });
    </script>
<?php endif; ?>

<!-- Welcome Banner -->
<div class="mb-8 p-6 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-sky-800 dark:from-blue-900/50 dark:via-slate-900 dark:to-slate-900 border border-blue-500/20 text-white relative overflow-hidden shadow-lg">
    <div class="absolute right-0 top-0 translate-x-4 -translate-y-4 w-64 h-64 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3 mb-2">
                <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-white/20 dark:bg-blue-500/20 text-white dark:text-blue-400 border border-white/30 dark:border-blue-500/30">
                    <?= session()->get('role') ?>
                </span>
                <!-- <span class="text-xs text-blue-100 dark:text-slate-400">Selamat Datang Kembali</span> -->
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-white tracking-tight">Halo, <?= session()->get('name') ?>!</h2>
            <p class="text-xs text-blue-100 dark:text-slate-400 mt-1 max-w-xl">
                Kelola konten slideshow, daftar TV display, dan susunan playlist dengan mudah melalui portal ini.
            </p>
        </div>

        <div class="flex items-center space-x-3 shrink-0">
            <a href="<?= base_url('admin/playlist') ?>" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl text-xs font-semibold border border-white/20 dark:border-slate-700 transition-all flex items-center space-x-2 ux-hover">
                <i class="fa-solid fa-list-ol text-[10px]"></i>
                <span>Kelola Playlist</span>
            </a>
            <a href="<?= base_url('admin/content') ?>" class="px-4 py-2.5 bg-white text-blue-700 hover:bg-blue-50 dark:bg-blue-600 dark:hover:bg-blue-500 dark:text-white rounded-xl text-xs font-semibold shadow-lg transition-all flex items-center space-x-2 ux-hover">
                <i class="fa-solid fa-plus text-[10px]"></i>
                <span>Tambah Konten</span>
            </a>
        </div>
    </div>
</div>

<!-- Stats Grid Row 1 -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
    <!-- Stat: Total TV Display -->
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs ux-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-tv text-base"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= $stats['total_tvs'] ?></h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Total Display TV</p>
        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium inline-flex items-center space-x-1 mt-1">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
            <span><?= $stats['active_tvs'] ?> Aktif</span>
        </span>
    </div>

    <!-- Stat: TV Online Now -->
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 hover:border-emerald-500/30 shadow-xs group ux-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                <i class="fa-solid fa-signal text-base"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= $stats['online_tvs'] ?></h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">TV Online Sekarang</p>
        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium inline-flex items-center space-x-1 mt-1">
            <?php if ($stats['online_tvs'] > 0): ?>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-ping"></span>
                <span>Aktif Streaming</span>
            <?php else: ?>
                <span class="text-slate-400 dark:text-slate-500">Tidak ada koneksi aktif</span>
            <?php endif; ?>
        </span>
    </div>

    <!-- Stat: Total Kategori -->
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs ux-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400">
                <i class="fa-solid fa-layer-group text-base"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= $stats['total_categories'] ?></h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Total Kategori</p>
    </div>

    <!-- Stat: Total Konten -->
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs ux-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 flex items-center justify-center text-sky-600 dark:text-sky-400">
                <i class="fa-solid fa-photo-film text-base"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= $stats['total_contents'] ?></h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Media Konten</p>
        <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 block">Gambar, Video, Chart</span>
    </div>

    <!-- Stat: System Users -->
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 hover:border-slate-300 dark:hover:border-slate-700 shadow-xs ux-card">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                <i class="fa-solid fa-users text-base"></i>
            </div>
        </div>
        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white"><?= $stats['total_users'] ?></h3>
        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">Pengguna Sistem</p>
        <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 block">Superadmin & Admin</span>
    </div>
</div>

<!-- Section: TV Status Monitoring + Access Logs -->
<div class="grid grid-cols-1 xl:grid-cols-5 gap-8 mb-8">

    <!-- TV Status Real-Time List (3 col) -->
    <div class="xl:col-span-3 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Monitoring Status Display TV</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Status online diperbarui tiap 30 detik via SSE heartbeat.</p>
            </div>
            <a href="<?= base_url('admin/tv') ?>" class="text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline transition-colors">
                Kelola TV <i class="fa-solid fa-chevron-right text-[10px] ml-1"></i>
            </a>
        </div>

        <?php if (empty($recentTvs)): ?>
            <div class="text-center py-12 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                <i class="fa-solid fa-tv text-3xl text-slate-400 dark:text-slate-600 mb-3 block"></i>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada TV yang terdaftar.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Nama TV</th>
                            <th class="py-3 px-4">Lokasi</th>
                            <th class="py-3 px-4">PIN</th>
                            <th class="py-3 px-4">Online</th>
                            <th class="py-3 px-4">Akses Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs">
                        <?php foreach ($recentTvs as $tv): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200"><?= esc($tv['name']) ?></td>
                                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400"><?= esc($tv['location'] ?? '-') ?></td>
                                <td class="py-3.5 px-4 font-mono font-bold text-blue-600 dark:text-blue-400 tracking-wider">
                                    <div class="flex items-center space-x-2">
                                        <span class="bg-blue-500/10 border border-blue-500/20 px-2 py-0.5 rounded-lg text-xs">
                                            <?= esc($tv['pin']) ?>
                                        </span>
                                        <button type="button" class="btnCopyDashboardPin p-1 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors ux-hover" data-pin="<?= esc($tv['pin']) ?>" title="Copy PIN ke Clipboard">
                                            <i class="fa-regular fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <?php if ($tv['is_online']): ?>
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-300 border border-emerald-500/30">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-ping"></span>
                                            <span>ONLINE</span>
                                        </span>
                                    <?php elseif ($tv['is_active']): ?>
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span>
                                            <span>Standby</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-[10px] font-semibold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                            <span>Nonaktif</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 text-[10px]">
                                    <?= $tv['last_seen_at'] ? date('d M, H:i', strtotime($tv['last_seen_at'])) : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Content Distribution by Category (2 col) -->
    <div class="xl:col-span-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
        <div class="mb-6">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">Distribusi Konten</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Jumlah konten per kategori</p>
        </div>

        <?php if (empty($contentByCategory)): ?>
            <div class="text-center py-12 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                <i class="fa-solid fa-chart-bar text-3xl text-slate-400 dark:text-slate-600 mb-3 block"></i>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada konten.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php
                $maxTotal = max(array_column($contentByCategory, 'total'));
                foreach ($contentByCategory as $cat):
                    $pct = $maxTotal > 0 ? round(($cat['total'] / $maxTotal) * 100) : 0;
                ?>
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: <?= $cat['color'] ?>"></span>
                                <span class="text-xs font-medium text-slate-700 dark:text-slate-300 truncate max-w-[140px]"><?= esc($cat['name']) ?></span>
                            </div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white ml-2"><?= $cat['total'] ?></span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-700" style="width: <?= $pct ?>%; background-color: <?= $cat['color'] ?>"></div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Type Distribution Pills -->
                <?php if (!empty($contentTypes)): ?>
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 mt-4 flex flex-wrap gap-2">
                        <?php foreach ($contentTypes as $ct): ?>
                            <?php
                            $typeColors = [
                                'image' => ['bg' => 'bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-500/20'],
                                'video' => ['bg' => 'bg-sky-500/10', 'text' => 'text-sky-600 dark:text-sky-400', 'border' => 'border-sky-500/20'],
                                'chart' => ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'border-amber-500/20'],
                            ];
                            $tc = $typeColors[$ct['type']] ?? ['bg' => 'bg-slate-100 dark:bg-slate-800', 'text' => 'text-slate-600 dark:text-slate-400', 'border' => 'border-slate-200 dark:border-slate-700'];
                            ?>
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold border <?= $tc['bg'] . ' ' . $tc['text'] . ' ' . $tc['border'] ?>">
                                <i class="fa-solid <?= $ct['type'] === 'image' ? 'fa-image' : ($ct['type'] === 'video' ? 'fa-video' : 'fa-chart-column') ?> mr-1"></i>
                                <?= ucfirst($ct['type']) ?>: <?= $ct['total'] ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Section: PIN Access Logs + Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- PIN Access Logs -->
    <div class="lg:col-span-2 bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white">Riwayat Akses PIN TV</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">10 verifikasi PIN terakhir dari perangkat display</p>
            </div>
            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                <i class="fa-solid fa-key mr-1"></i>Log Akses
            </span>
        </div>

        <?php if (empty($recentAccessLogs)): ?>
            <div class="text-center py-10 border border-dashed border-slate-200 dark:border-slate-800 rounded-xl">
                <i class="fa-solid fa-key text-2xl text-slate-400 dark:text-slate-600 mb-2 block"></i>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada riwayat verifikasi PIN.</p>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-4">Display TV</th>
                            <th class="py-3 px-4">IP Address</th>
                            <th class="py-3 px-4">Waktu Akses</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800/50 text-xs">
                        <?php foreach ($recentAccessLogs as $log): ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                                <td class="py-3 px-4">
                                    <span class="font-semibold text-slate-800 dark:text-slate-200"><?= esc($log['tv_name']) ?></span>
                                    <span class="text-slate-400 dark:text-slate-500 block text-[10px]"><?= esc($log['tv_location'] ?? '-') ?></span>
                                </td>
                                <td class="py-3 px-4 font-mono text-blue-600 dark:text-blue-400"><?= esc($log['ip_address']) ?></td>
                                <td class="py-3 px-4 text-slate-500 dark:text-slate-400">
                                    <?= date('d M Y, H:i:s', strtotime($log['accessed_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Shortcuts Card -->
    <div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-2xl p-6 flex flex-col justify-between shadow-sm">
        <div>
            <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">Aksi Cepat</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Pintasan navigasi untuk tugas manajemen</p>

            <div class="space-y-3">
                <a href="<?= base_url('admin/content') ?>" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/50 transition-all text-xs text-slate-700 dark:text-slate-200 group ux-hover">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <span class="font-medium">Tambah Konten Baru</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"></i>
                </a>

                <a href="<?= base_url('admin/playlist') ?>" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/50 transition-all text-xs text-slate-700 dark:text-slate-200 group ux-hover">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <i class="fa-solid fa-list-ol"></i>
                        </div>
                        <span class="font-medium">Atur Urutan Playlist</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors"></i>
                </a>

                <a href="<?= base_url('admin/category') ?>" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/50 transition-all text-xs text-slate-700 dark:text-slate-200 group ux-hover">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                            <i class="fa-solid fa-folder-plus"></i>
                        </div>
                        <span class="font-medium">Kelola Kategori</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors"></i>
                </a>

                <a href="<?= base_url('/') ?>" target="_blank" class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/50 transition-all text-xs text-slate-700 dark:text-slate-200 group ux-hover">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </div>
                        <span class="font-medium">Landing Page TV Publik</span>
                    </div>
                    <i class="fa-solid fa-chevron-right text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors"></i>
                </a>
            </div>
        </div>

        <div class="mt-8 pt-4 border-t border-slate-200 dark:border-slate-800 text-[11px] text-slate-400 dark:text-slate-500 flex items-center justify-between">
            <span>CodeIgniter 4.7 & Tailwind v4</span>
            <span class="text-emerald-600 dark:text-emerald-400 flex items-center space-x-1">
                <span class="w-2 h-2 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                <span>System Ready</span>
            </span>
        </div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        $(document).on('click', '.btnCopyDashboardPin', function () {
            const pin = $(this).data('pin');
            copyPinToClipboard(pin, this);
        });
    });
</script>
<?= $this->endSection() ?>

