<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Header & TV Selector Bar -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-xl font-bold text-slate-800 dark:text-white tracking-tight flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm">
                <i class="fa-solid fa-calendar-days"></i>
            </span>
            <span>Kelola Playlist & Jadwal Display TV</span>
        </h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Atur urutan penayangan (drag & drop), konfigurasi tanggal & hari aktif (recurrence), serta pantau kalender visual harian per TV.</p>
    </div>

    <!-- TV Selector Dropdown & PIN Copy Badge -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 sm:p-1.5 rounded-2xl shadow-xs w-full sm:w-auto">
        <div class="flex items-center space-x-2 flex-1 w-full sm:w-80 md:w-96">
            <label for="tvSelector" class="text-xs font-semibold text-slate-600 dark:text-slate-400 pl-1 sm:pl-2 shrink-0 flex items-center">
                <i class="fa-solid fa-tv mr-1.5 text-blue-600 dark:text-blue-400"></i>Pilih TV:
            </label>
            <div class="flex-1 min-w-0 relative">
                <select id="tvSelector" class="w-full">
                    <?php if (empty($tvs)): ?>
                        <option value="">-- Belum ada TV --</option>
                    <?php else: ?>
                        <?php foreach ($tvs as $tv): ?>
                            <option value="<?= $tv['id'] ?>" 
                                    data-name="<?= esc($tv['name']) ?>" 
                                    data-location="<?= esc(!empty($tv['location']) ? $tv['location'] : 'Lokasi belum diatur') ?>" 
                                    data-pin="<?= esc($tv['pin']) ?>" 
                                    <?= ($selectedTvId == $tv['id']) ? 'selected' : '' ?>>
                                <?= esc($tv['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- PIN Badge & Copy Button -->
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

<!-- Info Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-film"></i>
        </div>
        <div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Total Slide Terdaftar</p>
            <h4 class="text-lg font-bold text-slate-800 dark:text-white" id="totalSlidesText">0 Slide</h4>
        </div>
    </div>

    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Slide Aktif Sekarang</p>
            <h4 class="text-lg font-bold text-emerald-600 dark:text-emerald-400" id="activeSlidesText">0 Slide</h4>
        </div>
    </div>

    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-stopwatch"></i>
        </div>
        <div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Rotasi Aktif Saat Ini</p>
            <h4 class="text-lg font-bold text-slate-800 dark:text-white" id="totalRotationTimeText">0 detik</h4>
        </div>
    </div>

    <div class="p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800 flex items-center space-x-3.5 shadow-xs ux-card">
        <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base shrink-0">
            <i class="fa-solid fa-arrows-rotate"></i>
        </div>
        <div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Real-time SSE Sync</p>
            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 flex items-center space-x-1.5 mt-0.5">
                <span class="w-2 h-2 rounded-full bg-indigo-500 dark:bg-indigo-400 animate-pulse"></span>
                <span>Otomatis Sinkron</span>
            </span>
        </div>
    </div>
</div>

<!-- Main Dual-View Card & Tabs -->
<div class="bg-white dark:bg-slate-900/60 border border-slate-200 dark:border-slate-800/80 rounded-3xl p-4 sm:p-6 shadow-sm">

    <!-- View Switcher Tabs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-slate-200 dark:border-slate-800">
        <div class="flex items-center space-x-2 bg-slate-100 dark:bg-slate-950 p-1.5 rounded-2xl w-full sm:w-auto">
            <button type="button" id="tabBtnList" class="tab-btn flex-1 sm:flex-none flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs">
                <i class="fa-solid fa-list-ol"></i>
                <span>Urutan & Jadwal Slide</span>
            </button>
            <button type="button" id="tabBtnCalendar" class="tab-btn flex-1 sm:flex-none flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold transition-all text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white">
                <i class="fa-regular fa-calendar-days"></i>
                <span>Kalender Penayangan</span>
            </button>
        </div>

        <div class="flex items-center space-x-3 text-xs text-slate-400 dark:text-slate-500">
            <span id="tabHelpText" class="hidden sm:inline italic">
                Gunakan ikon pegangan <i class="fa-solid fa-grip-vertical mx-1"></i> untuk drag & drop urutan slide.
            </span>
        </div>
    </div>

    <!-- TAB 1: Urutan Playlist & Pengaturan Jadwal -->
    <div id="tabContentList" class="mt-6">
        <!-- Reorderable List Container -->
        <div id="playlistSortable" class="space-y-3">
            <!-- Loading State -->
            <div class="py-12 text-center text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 block text-blue-500"></i>
                <span>Memuat daftar playlist TV...</span>
            </div>
        </div>
    </div>

    <!-- TAB 2: Kalender Penayangan Interaktif -->
    <div id="tabContentCalendar" class="mt-6 hidden">
        <!-- Calendar Header Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 bg-slate-50 dark:bg-slate-950/60 p-3.5 sm:p-4 rounded-2xl border border-slate-200 dark:border-slate-800">
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-1.5">
                    <button type="button" id="btnPrevMonth" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500/40 flex items-center justify-center text-xs transition-all shadow-2xs">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button type="button" id="btnNextMonth" class="w-9 h-9 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-500/40 flex items-center justify-center text-xs transition-all shadow-2xs">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
                <h3 id="currentMonthYearLabel" class="text-base sm:text-lg font-bold text-slate-800 dark:text-white tracking-tight">
                    Agustus 2026
                </h3>
            </div>

            <div class="flex items-center space-x-2">
                <button type="button" id="btnTodayMonth" class="px-3.5 py-2 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 hover:bg-blue-500/20 text-xs font-bold transition-all border border-blue-500/20 flex items-center space-x-1.5">
                    <i class="fa-regular fa-calendar-check"></i>
                    <span>Hari Ini</span>
                </button>
                <button type="button" id="btnRefreshCalendar" class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 text-xs transition-all" title="Refresh Kalender">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
            </div>
        </div>

        <!-- Calendar Day Grid Headers (Mon-Sun) -->
        <div class="grid grid-cols-7 gap-1.5 sm:gap-2 mb-2 text-center text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
            <div class="py-1">Sen</div>
            <div class="py-1">Sel</div>
            <div class="py-1">Rab</div>
            <div class="py-1">Kam</div>
            <div class="py-1">Jum</div>
            <div class="py-1 text-rose-500 dark:text-rose-400">Sab</div>
            <div class="py-1 text-rose-500 dark:text-rose-400">Min</div>
        </div>

        <!-- Calendar Days Grid -->
        <div id="calendarGrid" class="grid grid-cols-7 gap-1.5 sm:gap-2">
            <!-- Rendered by JS -->
            <div class="col-span-7 py-16 text-center text-slate-400 dark:text-slate-500">
                <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 block text-blue-500"></i>
                <span>Memuat kalender penayangan...</span>
            </div>
        </div>
    </div>

</div>

<style>
/* Day Checkbox Custom Pill Styling */
.day-input:checked + .day-box {
    background-color: #2563eb !important;
    color: #ffffff !important;
    border-color: #3b82f6 !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}
.calendar-day-cell {
    min-height: 90px;
}
@media (min-width: 640px) {
    .calendar-day-cell {
        min-height: 110px;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<!-- MODAL 1: Konfigurasi Jadwal & Recurrence Slide -->
<div id="scheduleModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-3xl sm:rounded-3xl max-w-xl w-full p-5 sm:p-6 shadow-2xl flex flex-col max-h-[85vh] sm:max-h-[90vh] relative transform transition-all duration-300 animate-slide-up sm:animate-none" id="scheduleModalContent">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white tracking-tight">Atur Jadwal & Recurrence Slide</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate max-w-60 sm:max-w-md" id="modalSlideTitle">Judul Slide</p>
                </div>
            </div>
            <button type="button" id="btnCloseScheduleModal" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Modal Form Body (Scrollable) -->
        <form id="scheduleForm" class="flex-1 overflow-y-auto pr-1 mt-4 flex flex-col justify-between">
            <input type="hidden" id="modalTvId" name="tv_id" value="">
            <input type="hidden" id="modalContentId" name="content_id" value="">

            <div class="space-y-4">
                <!-- Toggle: Always vs Scheduled -->
                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800/80 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-800 dark:text-white block">Mode Penjadwalan</span>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Aktifkan untuk membatasi penayangan pada tanggal, hari, atau jam tertentu.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggleIsScheduled" name="is_scheduled" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-300 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <!-- Schedule Options Container (Visible when is_scheduled is ON) -->
                <div id="scheduleFieldsContainer" class="space-y-4 hidden">
                    
                    <!-- Section 1: Rentang Tanggal Kalender (Date Range) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center">
                                <i class="fa-regular fa-calendar text-blue-500 mr-1.5"></i>Rentang Tanggal Penayangan
                            </label>
                            <button type="button" id="btnClearDates" class="text-[11px] font-semibold text-slate-400 hover:text-rose-500 transition-colors">
                                Hapus Rentang Tanggal
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block mb-1">Tanggal Mulai (Opsional)</label>
                                <input type="date" id="inputStartDate" name="start_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 transition-all font-mono">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block mb-1">Tanggal Selesai (Opsional)</label>
                                <input type="date" id="inputEndDate" name="end_date" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 transition-all font-mono">
                            </div>
                        </div>
                        <!-- Quick Date Presets -->
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <button type="button" class="btn-date-preset px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-[10px] font-semibold transition-colors" data-days="7">
                                +7 Hari
                            </button>
                            <button type="button" class="btn-date-preset px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-[10px] font-semibold transition-colors" data-days="14">
                                +14 Hari
                            </button>
                            <button type="button" class="btn-date-preset px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-[10px] font-semibold transition-colors" data-days="30">
                                +30 Hari
                            </button>
                            <button type="button" class="btn-date-preset px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-[10px] font-semibold transition-colors" data-preset="this_month">
                                Sepanjang Bulan Ini
                            </button>
                        </div>
                    </div>

                    <!-- Section 2: Hari Berulang / Recurrence (Days of Week) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center">
                                <i class="fa-solid fa-repeat text-blue-500 mr-1.5"></i>Hari Aktif Mingguan (Recurrence)
                            </label>
                            <div class="flex items-center space-x-1">
                                <button type="button" id="btnPresetAllDays" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 hover:underline px-1">Semua</button>
                                <span class="text-slate-300 dark:text-slate-700">•</span>
                                <button type="button" id="btnPresetWorkdays" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 hover:underline px-1">Hari Kerja</button>
                                <span class="text-slate-300 dark:text-slate-700">•</span>
                                <button type="button" id="btnPresetWeekend" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 hover:underline px-1">Weekend</button>
                            </div>
                        </div>

                        <!-- 7-Day Toggle Buttons -->
                        <div class="grid grid-cols-7 gap-1.5" id="daysCheckboxContainer">
                            <?php
                            $daysList = [
                                ['key' => 'mon', 'name' => 'Sen', 'full' => 'Senin'],
                                ['key' => 'tue', 'name' => 'Sel', 'full' => 'Selasa'],
                                ['key' => 'wed', 'name' => 'Rab', 'full' => 'Rabu'],
                                ['key' => 'thu', 'name' => 'Kam', 'full' => 'Kamis'],
                                ['key' => 'fri', 'name' => 'Jum', 'full' => 'Jumat'],
                                ['key' => 'sat', 'name' => 'Sab', 'full' => 'Sabtu'],
                                ['key' => 'sun', 'name' => 'Min', 'full' => 'Minggu'],
                            ];
                            foreach ($daysList as $d):
                            ?>
                                <label class="day-checkbox-label cursor-pointer select-none">
                                    <input type="checkbox" name="days_of_week[]" value="<?= $d['key'] ?>" class="sr-only day-input" checked>
                                    <div class="day-box p-2 rounded-xl text-center border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-700 dark:text-slate-300 transition-all font-bold text-xs">
                                        <span><?= $d['name'] ?></span>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Section 3: Jam Operasional Harian (Time Range) -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center">
                                <i class="fa-regular fa-clock text-blue-500 mr-1.5"></i>Jam Tayang Harian
                            </label>
                            <div class="flex items-center space-x-1">
                                <button type="button" id="btnPreset24h" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 hover:underline px-1">24 Jam</button>
                                <span class="text-slate-300 dark:text-slate-700">•</span>
                                <button type="button" id="btnPresetOfficeHours" class="text-[10px] font-semibold text-blue-600 dark:text-blue-400 hover:underline px-1">08:00 - 17:00</button>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block mb-1">Jam Mulai</label>
                                <input type="time" id="inputStartTime" name="start_time" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 transition-all font-mono">
                            </div>
                            <div>
                                <label class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block mb-1">Jam Selesai</label>
                                <input type="time" id="inputEndTime" name="end_time" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-800 dark:text-white focus:outline-none focus:border-blue-500 transition-all font-mono">
                            </div>
                        </div>
                    </div>

                    <!-- Live Summary Box -->
                    <div class="p-3.5 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/50">
                        <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1 flex items-center">
                            <i class="fa-solid fa-circle-info mr-1.5"></i>Ringkasan Jadwal:
                        </span>
                        <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium" id="liveScheduleSummary">
                            Slide ini selalu ditayangkan setiap saat.
                        </p>
                    </div>

                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-between pt-4 mt-5 border-t border-slate-200 dark:border-slate-800 gap-2 shrink-0">
                <button type="button" id="btnResetToAlways" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">
                    Reset ke Selalu Tayang
                </button>
                <div class="flex items-center space-x-2">
                    <button type="button" id="btnCancelScheduleModal" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSaveSchedule" class="px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold transition-all shadow-md flex items-center space-x-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Simpan Jadwal</span>
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<!-- MODAL 2: Detail Penayangan Tanggal Kalender (Daily Preview Timeline) -->
<div id="dayDetailModal" class="fixed inset-0 z-60 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-950/80 backdrop-blur-sm hidden transition-opacity duration-200">
    <div class="bg-white dark:bg-slate-900 border-t border-x sm:border border-slate-200 dark:border-slate-800 rounded-t-3xl sm:rounded-3xl max-w-lg w-full p-5 sm:p-6 shadow-2xl flex flex-col max-h-[85vh] sm:max-h-[90vh] relative transform transition-all duration-300 animate-slide-up sm:animate-none" id="dayDetailModalContent">
        
        <!-- Mobile Bottom Sheet Handle Bar -->
        <div class="w-12 h-1.5 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mb-4 sm:hidden shrink-0"></div>

        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-800 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shrink-0">
                    <i class="fa-solid fa-calendar-day"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white tracking-tight" id="dayDetailTitle">Detail Penayangan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400" id="dayDetailSubtitle">18 Agustus 2026</p>
                </div>
            </div>
            <button type="button" id="btnCloseDayDetailModal" class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-white flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto pr-1 mt-3 space-y-4">
            <!-- Summary Stats on this date -->
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-center">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block">Total Slide Tayang</span>
                    <span class="text-base font-bold text-slate-800 dark:text-white" id="dayDetailCount">0 Slide</span>
                </div>
                <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-center">
                    <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium block">Total Rotasi Hari Ini</span>
                    <span class="text-base font-bold text-blue-600 dark:text-blue-400 font-mono" id="dayDetailRotation">0s</span>
                </div>
            </div>

            <!-- List of items playing on this date -->
            <div class="space-y-2.5" id="dayDetailItemList">
                <!-- Rendered by JS -->
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800 flex justify-end shrink-0">
            <button type="button" id="btnCloseDayDetailBtn" class="px-4 py-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                Tutup
            </button>
        </div>

    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        let sortableInstance = null;
        let currentPlaylistData = [];
        let currentCalendarData = null;
        let currentYear = new Date().getFullYear();
        let currentMonth = new Date().getMonth() + 1; // 1-12

        const monthNamesIndo = [
            '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        function formatTime(seconds) {
            const sec = parseInt(seconds) || 0;
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            if (m > 0) return `${m}m ${s}s`;
            return `${s}s`;
        }

        // ─── TAB NAVIGATION ───────────────────────────────────────────────────
        $('#tabBtnList').on('click', function () {
            $(this).addClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs')
                   .removeClass('text-slate-500 dark:text-slate-400');
            $('#tabBtnCalendar').removeClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs')
                                .addClass('text-slate-500 dark:text-slate-400');
            $('#tabContentList').removeClass('hidden');
            $('#tabContentCalendar').addClass('hidden');
            $('#tabHelpText').removeClass('hidden');
        });

        $('#tabBtnCalendar').on('click', function () {
            $(this).addClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs')
                   .removeClass('text-slate-500 dark:text-slate-400');
            $('#tabBtnList').removeClass('bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs')
                            .addClass('text-slate-500 dark:text-slate-400');
            $('#tabContentList').addClass('hidden');
            $('#tabContentCalendar').removeClass('hidden');
            $('#tabHelpText').addClass('hidden');
            
            loadCalendarData();
        });

        // ─── LOAD PLAYLIST ────────────────────────────────────────────────────
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

            $('#playlistSortable').html(`
                <div class="py-12 text-center text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 block text-blue-500"></i>
                    <span>Memuat daftar playlist TV...</span>
                </div>
            `);

            $.ajax({
                url: `<?= base_url('admin/playlist/get') ?>/${tvId}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        currentPlaylistData = res.data || [];
                        renderPlaylist(currentPlaylistData);
                        if (!$('#tabContentCalendar').hasClass('hidden')) {
                            loadCalendarData();
                        }
                    }
                },
                error: function () {
                    $('#playlistSortable').html(`
                        <div class="text-center py-12 text-rose-500">
                            Gagal memuat playlist TV. Silakan coba lagi.
                        </div>
                    `);
                }
            });
        }

        // ─── RENDER PLAYLIST ITEMS ─────────────────────────────────────────────
        function renderPlaylist(items) {
            if (!items || items.length === 0) {
                $('#totalSlidesText').text('0 Slide');
                $('#activeSlidesText').text('0 Slide');
                $('#totalRotationTimeText').text('0 detik');
                $('#playlistSortable').html(`
                    <div class="text-center py-12 border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                        <i class="fa-solid fa-film text-3xl text-slate-400 dark:text-slate-600 mb-3 block"></i>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada konten pada kategori TV ini.</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Hubungkan kategori ke TV atau buat konten baru di menu Kelola Konten.</p>
                    </div>
                `);
                return;
            }

            let totalActiveSec = 0;
            let activeCount = 0;
            let html = '';

            items.forEach((item, index) => {
                const dur = parseInt(item.display_duration_seconds || item.video_duration_seconds) || 10;
                if (item.is_active_now) {
                    totalActiveSec += dur;
                    activeCount++;
                }

                const catColor = item.category_color || '#6366f1';
                const catBadge = `
                    <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border"
                          style="background-color: ${catColor}15; color: ${catColor}; border-color: ${catColor}30;">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: ${catColor};"></span>
                        <span>${item.category_name}</span>
                    </span>
                `;

                // Content Type Badge & Thumbnail
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

                // Schedule Status Badge Styling
                let statusBadge = '';
                if (item.schedule_status === 'always') {
                    statusBadge = `<span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20"><i class="fa-solid fa-infinity mr-1"></i>Selalu Tayang</span>`;
                } else if (item.schedule_status === 'active') {
                    statusBadge = `<span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span>Aktif Tayang</span>`;
                } else if (item.schedule_status === 'upcoming') {
                    statusBadge = `<span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-sky-500/10 text-sky-600 dark:text-sky-400 border border-sky-500/20"><i class="fa-regular fa-clock mr-1"></i>Akan Datang</span>`;
                } else if (item.schedule_status === 'expired') {
                    statusBadge = `<span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20"><i class="fa-solid fa-circle-xmark mr-1"></i>Sudah Berakhir</span>`;
                } else {
                    statusBadge = `<span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[10px] font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20"><i class="fa-regular fa-moon mr-1"></i>Di Luar Jam/Hari</span>`;
                }

                const isScheduled = parseInt(item.is_scheduled) === 1;

                html += `
                    <div class="playlistItemCard flex flex-col lg:flex-row lg:items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-white dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700/80 transition-all shadow-xs gap-3" data-id="${item.id}" data-item='${JSON.stringify(item).replace(/'/g, "&#39;")}'>
                        
                        <!-- Left: Drag, Index, Thumbnail, Title & Badges -->
                        <div class="flex items-center space-x-2.5 sm:space-x-3.5 min-w-0 flex-1">
                            <!-- Drag Handle -->
                            <button type="button" class="dragHandle cursor-grab active:cursor-grabbing p-1.5 text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors shrink-0" title="Geser untuk ubah urutan">
                                <i class="fa-solid fa-grip-vertical text-base"></i>
                            </button>

                            <!-- Index Pill -->
                            <span class="slideIndexPill w-7 h-7 rounded-lg bg-blue-500/10 border border-blue-500/20 text-blue-600 dark:text-blue-400 font-mono font-extrabold text-xs flex items-center justify-center shrink-0">
                                #${index + 1}
                            </span>

                            <!-- Thumbnail -->
                            ${thumbnailHtml}

                            <!-- Title & Info -->
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                    <h4 class="font-semibold text-slate-800 dark:text-slate-100 text-xs sm:text-sm truncate max-w-xs sm:max-w-md" title="${item.title}">${item.title}</h4>
                                    ${statusBadge}
                                </div>
                                
                                <div class="flex items-center space-x-2 mt-1.5 flex-wrap gap-y-1">
                                    ${typeIcon}
                                    ${catBadge}
                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center">
                                        <i class="fa-regular fa-calendar-check text-blue-500/70 mr-1"></i>
                                        <span class="truncate max-w-50 sm:max-w-xs" title="${item.schedule_text}">${item.schedule_text}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Duration & Actions -->
                        <div class="flex items-center justify-between lg:justify-end space-x-2 shrink-0 pt-2 lg:pt-0 border-t border-slate-100 dark:border-slate-800/60 lg:border-t-0">
                            <!-- Duration Badge -->
                            <span class="text-xs font-mono font-semibold text-blue-600 dark:text-blue-400 bg-blue-500/10 border border-blue-500/20 px-2.5 py-1.5 rounded-xl flex items-center space-x-1.5 shrink-0" title="Durasi Tayang">
                                <i class="fa-regular fa-clock text-[11px]"></i>
                                <span>${formatTime(dur)}</span>
                            </span>

                            <!-- Schedule Config Button -->
                            <button type="button" class="btnOpenScheduleModal px-3 py-1.5 rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 ${isScheduled ? 'bg-blue-600 hover:bg-blue-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-blue-900/30 text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400'}">
                                <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                <span>${isScheduled ? 'Ubah Jadwal' : 'Atur Jadwal'}</span>
                            </button>
                        </div>
                    </div>
                `;
            });

            $('#totalSlidesText').text(`${items.length} Slide`);
            $('#activeSlidesText').text(`${activeCount} Slide`);
            $('#totalRotationTimeText').text(formatTime(totalActiveSec));
            $('#playlistSortable').html(html);

            initSortable();
        }

        // ─── SORTABLE REORDER ─────────────────────────────────────────────────
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

        function updateIndexPills() {
            $('.playlistItemCard').each(function (idx) {
                $(this).find('.slideIndexPill').text(`#${idx + 1}`);
            });
        }

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

        // ─── PIN BADGE & SELECT2 TV SELECTOR ───────────────────────────────────
        function escapeHtml(text) {
            if (!text) return '';
            return $('<div>').text(text).html();
        }

        function formatTvOption(option) {
            if (!option.id) {
                return option.text;
            }
            const $element = $(option.element);
            const name = escapeHtml($element.data('name') || option.text);
            const location = escapeHtml($element.data('location') || 'Lokasi belum diatur');
            const pin = escapeHtml($element.data('pin'));

            const pinBadge = pin ? `<span class="select2-option-pin text-[10px] font-mono font-bold px-1.5 py-0.5 rounded shrink-0 transition-colors">PIN: ${pin}</span>` : '';

            return $(`
                <div class="flex flex-col py-0.5">
                    <div class="flex items-center justify-between gap-2">
                        <span class="select2-option-title font-semibold text-xs truncate transition-colors">${name}</span>
                        ${pinBadge}
                    </div>
                    <div class="select2-option-subtitle flex items-center text-[11px] mt-0.5 transition-colors">
                        <i class="fa-solid fa-location-dot mr-1.5 text-rose-500 text-[10px] shrink-0"></i>
                        <span class="truncate">${location}</span>
                    </div>
                </div>
            `);
        }

        function formatTvSelection(option) {
            if (!option.id) {
                return option.text;
            }
            const $element = $(option.element);
            const name = escapeHtml($element.data('name') || option.text);
            const location = escapeHtml($element.data('location'));

            if (location && location !== 'Lokasi belum diatur') {
                return $(`
                    <span class="inline-flex items-center space-x-1.5 max-w-full text-xs overflow-hidden">
                        <span class="font-semibold text-slate-800 dark:text-slate-100 truncate">${name}</span>
                        <span class="text-slate-400 dark:text-slate-500 text-[11px] shrink-0">•</span>
                        <span class="text-slate-500 dark:text-slate-400 text-[11px] truncate shrink-0 max-w-[140px] flex items-center">
                            <i class="fa-solid fa-location-dot text-[10px] mr-1 text-rose-500/80 shrink-0"></i>
                            <span class="truncate">${location}</span>
                        </span>
                    </span>
                `);
            }
            return $(`<span class="font-semibold text-slate-800 dark:text-slate-100 text-xs truncate">${name}</span>`);
        }

        function matchTvCustom(params, data) {
            if (!params.term || params.term.toString().trim() === '') {
                return data;
            }
            if (typeof data.text === 'undefined') {
                return null;
            }

            const term = params.term.toString().trim().toLowerCase();
            const $element = $(data.element);
            const name = ($element.data('name') || data.text || '').toString().toLowerCase();
            const location = ($element.data('location') || '').toString().toLowerCase();
            const pin = ($element.data('pin') || '').toString().toLowerCase();

            if (name.indexOf(term) > -1 || location.indexOf(term) > -1 || pin.indexOf(term) > -1) {
                return data;
            }

            return null;
        }

        // Initialize Select2 on #tvSelector
        $('#tvSelector').select2({
            templateResult: formatTvOption,
            templateSelection: formatTvSelection,
            matcher: matchTvCustom,
            width: '100%',
            dropdownParent: $('#tvSelector').parent(),
            language: {
                noResults: function () {
                    return '<span class="text-xs text-slate-400">Tidak ada TV yang cocok</span>';
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        });

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

        $('#tvSelector').on('change', function () {
            const tvId = $(this).val();
            updateSelectedPinBadge();
            loadPlaylist(tvId);
        });

        $('#btnCopySelectedPin').on('click', function () {
            const pin = $('#selectedTvPinText').text();
            if (pin && pin !== '------') {
                copyPinToClipboard(pin, this);
            }
        });

        // ─── SCHEDULE MODAL MANAGEMENT ────────────────────────────────────────
        function openScheduleModal(item) {
            $('#modalTvId').val($('#tvSelector').val());
            $('#modalContentId').val(item.id);
            $('#modalSlideTitle').text(`${item.title} (${item.category_name})`);

            const isScheduled = parseInt(item.is_scheduled) === 1;
            $('#toggleIsScheduled').prop('checked', isScheduled);

            if (isScheduled) {
                $('#scheduleFieldsContainer').removeClass('hidden');
            } else {
                $('#scheduleFieldsContainer').addClass('hidden');
            }

            $('#inputStartDate').val(item.start_date || '');
            $('#inputEndDate').val(item.end_date || '');
            $('#inputStartTime').val(item.start_time ? item.start_time.substring(0, 5) : '');
            $('#inputEndTime').val(item.end_time ? item.end_time.substring(0, 5) : '');

            // Days checkboxes
            const days = item.days_of_week_arr || (item.days_of_week ? JSON.parse(item.days_of_week) : ['mon','tue','wed','thu','fri','sat','sun']);
            $('.day-input').each(function () {
                const val = $(this).val();
                $(this).prop('checked', days.includes(val));
            });

            updateLiveSummary();

            // Show Modal
            $('#scheduleModal').removeClass('hidden');
        }

        function closeScheduleModal() {
            $('#scheduleModal').addClass('hidden');
        }

        // Open modal on button click
        $(document).on('click', '.btnOpenScheduleModal', function () {
            const card = $(this).closest('.playlistItemCard');
            const item = card.data('item');
            if (item) {
                openScheduleModal(item);
            }
        });

        $('#btnCloseScheduleModal, #btnCancelScheduleModal').on('click', closeScheduleModal);

        // Close Modal on Backdrop Click
        $('#scheduleModal').on('click', function (e) {
            if (e.target === this) {
                closeScheduleModal();
            }
        });

        // Toggle Schedule Switch
        $('#toggleIsScheduled').on('change', function () {
            if ($(this).is(':checked')) {
                $('#scheduleFieldsContainer').removeClass('hidden');
            } else {
                $('#scheduleFieldsContainer').addClass('hidden');
            }
            updateLiveSummary();
        });

        // ─── SCHEDULE PRESET ACTIONS ──────────────────────────────────────────
        // Days presets
        $('#btnPresetAllDays').on('click', function () {
            $('.day-input').prop('checked', true);
            updateLiveSummary();
        });
        $('#btnPresetWorkdays').on('click', function () {
            $('.day-input').each(function () {
                const v = $(this).val();
                $(this).prop('checked', ['mon','tue','wed','thu','fri'].includes(v));
            });
            updateLiveSummary();
        });
        $('#btnPresetWeekend').on('click', function () {
            $('.day-input').each(function () {
                const v = $(this).val();
                $(this).prop('checked', ['sat','sun'].includes(v));
            });
            updateLiveSummary();
        });

        // Time presets
        $('#btnPreset24h').on('click', function () {
            $('#inputStartTime').val('');
            $('#inputEndTime').val('');
            updateLiveSummary();
        });
        $('#btnPresetOfficeHours').on('click', function () {
            $('#inputStartTime').val('08:00');
            $('#inputEndTime').val('17:00');
            updateLiveSummary();
        });

        // Date presets
        $('.btn-date-preset').on('click', function () {
            const today = new Date();
            const pad = n => String(n).padStart(2, '0');
            const fmtDate = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;

            const preset = $(this).data('preset');
            const days = parseInt($(this).data('days'));

            if (preset === 'this_month') {
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                $('#inputStartDate').val(fmtDate(firstDay));
                $('#inputEndDate').val(fmtDate(lastDay));
            } else if (days) {
                const end = new Date();
                end.setDate(today.getDate() + days);
                $('#inputStartDate').val(fmtDate(today));
                $('#inputEndDate').val(fmtDate(end));
            }
            updateLiveSummary();
        });

        $('#btnClearDates').on('click', function () {
            $('#inputStartDate').val('');
            $('#inputEndDate').val('');
            updateLiveSummary();
        });

        // Live Summary Calculation
        function updateLiveSummary() {
            const isSched = $('#toggleIsScheduled').is(':checked');
            if (!isSched) {
                $('#liveScheduleSummary').html('Slide ini disetel <strong>Selalu Tayang</strong> (tanpa batasan tanggal, hari, maupun jam).');
                return;
            }

            const startDate = $('#inputStartDate').val();
            const endDate = $('#inputEndDate').val();
            const startTime = $('#inputStartTime').val();
            const endTime = $('#inputEndTime').val();

            const checkedDays = [];
            $('.day-input:checked').each(function () {
                checkedDays.push($(this).val());
            });

            const dayNamesMap = {
                'mon': 'Senin', 'tue': 'Selasa', 'wed': 'Rabu',
                'thu': 'Kamis', 'fri': 'Jumat', 'sat': 'Sabtu', 'sun': 'Minggu'
            };

            let parts = [];

            // Dates
            if (startDate && endDate) {
                parts.push(`dari tanggal <strong>${startDate}</strong> sampai <strong>${endDate}</strong>`);
            } else if (startDate) {
                parts.push(`mulai tanggal <strong>${startDate}</strong>`);
            } else if (endDate) {
                parts.push(`sampai tanggal <strong>${endDate}</strong>`);
            }

            // Days
            if (checkedDays.length === 7) {
                parts.push(`pada <strong>Setiap Hari</strong>`);
            } else if (checkedDays.length === 5 && !checkedDays.includes('sat') && !checkedDays.includes('sun')) {
                parts.push(`pada <strong>Hari Kerja (Senin - Jumat)</strong>`);
            } else if (checkedDays.length === 2 && checkedDays.includes('sat') && checkedDays.includes('sun')) {
                parts.push(`pada <strong>Akhir Pekan (Sabtu & Minggu)</strong>`);
            } else if (checkedDays.length > 0) {
                const dLabels = checkedDays.map(d => dayNamesMap[d]);
                parts.push(`pada hari <strong>${dLabels.join(', ')}</strong>`);
            } else {
                parts.push(`<span class="text-rose-500 font-bold">(Belum ada hari yang dipilih)</span>`);
            }

            // Hours
            if (startTime && endTime) {
                parts.push(`pukul <strong>${startTime} - ${endTime} WIB</strong>`);
            } else if (startTime) {
                parts.push(`mulai pukul <strong>${startTime} WIB</strong>`);
            } else if (endTime) {
                parts.push(`sampai pukul <strong>${endTime} WIB</strong>`);
            } else {
                parts.push(`sepanjang 24 jam`);
            }

            $('#liveScheduleSummary').html(`Slide ini akan ditayangkan ${parts.join(', ')}.`);
        }

        $('input[name="days_of_week[]"], #inputStartDate, #inputEndDate, #inputStartTime, #inputEndTime').on('change input', updateLiveSummary);

        // Submit Schedule Form via AJAX
        $('#scheduleForm').on('submit', function (e) {
            e.preventDefault();

            const tvId = $('#modalTvId').val();
            const contentId = $('#modalContentId').val();
            const isScheduled = $('#toggleIsScheduled').is(':checked') ? 1 : 0;
            const startDate = $('#inputStartDate').val();
            const endDate = $('#inputEndDate').val();
            const startTime = $('#inputStartTime').val();
            const endTime = $('#inputEndTime').val();

            const daysOfWeek = [];
            $('.day-input:checked').each(function () {
                daysOfWeek.push($(this).val());
            });

            if (isScheduled && startDate && endDate && startDate > endDate) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rentang Tanggal Tidak Valid',
                    text: 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            const btn = $('#btnSaveSchedule');
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin mr-1"></i>Menyimpan...');

            $.ajax({
                url: '<?= base_url('admin/playlist/update-schedule') ?>',
                type: 'POST',
                data: {
                    tv_id: tvId,
                    content_id: contentId,
                    is_scheduled: isScheduled,
                    start_date: startDate,
                    end_date: endDate,
                    days_of_week: daysOfWeek,
                    start_time: startTime,
                    end_time: endTime
                },
                dataType: 'json',
                success: function (res) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-check mr-1"></i>Simpan Jadwal');
                    if (res.status === 'success') {
                        closeScheduleModal();
                        showToast('success', res.message || 'Jadwal berhasil diperbarui');
                        loadPlaylist(tvId);
                    } else {
                        Swal.fire('Error', res.message || 'Gagal menyimpan jadwal', 'error');
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-check mr-1"></i>Simpan Jadwal');
                    const msg = xhr.responseJSON?.message || 'Terjadi kesalahan sistem.';
                    Swal.fire('Error', msg, 'error');
                }
            });
        });

        // Reset Schedule Button
        $('#btnResetToAlways').on('click', function () {
            const tvId = $('#modalTvId').val();
            const contentId = $('#modalContentId').val();

            Swal.fire({
                title: 'Reset ke Selalu Tayang?',
                text: 'Slide ini akan kembali ditayangkan secara reguler setiap saat.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Reset',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/playlist/reset-schedule') ?>',
                        type: 'POST',
                        data: { tv_id: tvId, content_id: contentId },
                        dataType: 'json',
                        success: function (res) {
                            if (res.status === 'success') {
                                closeScheduleModal();
                                showToast('success', 'Jadwal slide direset ke Selalu Tayang');
                                loadPlaylist(tvId);
                            }
                        }
                    });
                }
            });
        });

        // ─── KALENDER PENAYANGAN INTERAKTIF ────────────────────────────────────
        function loadCalendarData() {
            const tvId = $('#tvSelector').val();
            if (!tvId) return;

            $('#currentMonthYearLabel').text(`${monthNamesIndo[currentMonth]} ${currentYear}`);
            $('#calendarGrid').html(`
                <div class="col-span-7 py-16 text-center text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-spinner fa-spin text-2xl mb-2 block text-blue-500"></i>
                    <span>Memuat kalender ${monthNamesIndo[currentMonth]} ${currentYear}...</span>
                </div>
            `);

            $.ajax({
                url: `<?= base_url('admin/playlist/calendar') ?>/${tvId}?year=${currentYear}&month=${currentMonth}`,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        currentCalendarData = res.data;
                        renderCalendarGrid(res.data);
                    }
                },
                error: function () {
                    $('#calendarGrid').html(`
                        <div class="col-span-7 py-12 text-center text-rose-500">
                            Gagal memuat data kalender.
                        </div>
                    `);
                }
            });
        }

        function renderCalendarGrid(data) {
            const firstDateObj = new Date(data.year, data.month - 1, 1);
            // In JS getDay(): 0 = Sun, 1 = Mon, ... 6 = Sat
            let firstDayOfWeek = firstDateObj.getDay();
            // Convert to 0 = Mon, 6 = Sun
            firstDayOfWeek = (firstDayOfWeek === 0) ? 6 : firstDayOfWeek - 1;

            const todayStr = new Date().toISOString().split('T')[0];
            let html = '';

            // Blank lead cells before day 1
            for (let i = 0; i < firstDayOfWeek; i++) {
                html += `
                    <div class="calendar-day-cell bg-slate-50/50 dark:bg-slate-950/20 border border-slate-100 dark:border-slate-900 rounded-2xl p-2 opacity-40 select-none"></div>
                `;
            }

            // Month day cells
            for (let d = 1; d <= data.days_in_month; d++) {
                const pad = n => String(n).padStart(2, '0');
                const dateStr = `${data.year}-${pad(data.month)}-${pad(d)}`;
                const dayObj = data.days[dateStr] || { active_count: 0, items: [] };
                const isToday = (dateStr === todayStr);

                const countBadge = dayObj.active_count > 0 
                    ? `<span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">${dayObj.active_count} Slide</span>`
                    : `<span class="text-[10px] text-slate-400 dark:text-slate-600">Kosong</span>`;

                // Mini chips for up to 2 items
                let chipsHtml = '';
                const maxChips = 2;
                const itemsToShow = (dayObj.items || []).slice(0, maxChips);

                itemsToShow.forEach(it => {
                    const cColor = it.category_color || '#3b82f6';
                    chipsHtml += `
                        <div class="truncate text-[10px] font-medium px-1.5 py-0.5 rounded-md text-slate-700 dark:text-slate-300 border flex items-center space-x-1" 
                             style="background-color: ${cColor}12; border-color: ${cColor}30;" 
                             title="${it.title}">
                            <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: ${cColor};"></span>
                            <span class="truncate">${it.title}</span>
                        </div>
                    `;
                });

                if (dayObj.items && dayObj.items.length > maxChips) {
                    const moreCount = dayObj.items.length - maxChips;
                    chipsHtml += `<div class="text-[9px] text-slate-400 font-semibold pl-1">+${moreCount} lainnya</div>`;
                }

                html += `
                    <div class="calendar-day-cell group relative bg-white dark:bg-slate-900 border ${isToday ? 'border-blue-500 dark:border-blue-400 ring-2 ring-blue-500/20' : 'border-slate-200 dark:border-slate-800'} hover:border-blue-400 dark:hover:border-blue-500/50 rounded-2xl p-2 sm:p-2.5 transition-all shadow-2xs flex flex-col justify-between cursor-pointer hover:shadow-md" data-date="${dateStr}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="w-6 h-6 rounded-lg ${isToday ? 'bg-blue-600 text-white font-bold' : 'text-slate-700 dark:text-slate-200 font-semibold'} text-xs flex items-center justify-center font-mono">
                                ${d}
                            </span>
                            ${countBadge}
                        </div>
                        <div class="space-y-1 flex-1 overflow-hidden my-1">
                            ${chipsHtml}
                        </div>
                    </div>
                `;
            }

            $('#calendarGrid').html(html);
        }

        // Calendar Month Navigation
        $('#btnPrevMonth').on('click', function () {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            loadCalendarData();
        });

        $('#btnNextMonth').on('click', function () {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            loadCalendarData();
        });

        $('#btnTodayMonth').on('click', function () {
            const now = new Date();
            currentYear = now.getFullYear();
            currentMonth = now.getMonth() + 1;
            loadCalendarData();
        });

        $('#btnRefreshCalendar').on('click', loadCalendarData);

        // ─── DAY DETAIL PREVIEW MODAL ─────────────────────────────────────────
        $(document).on('click', '.calendar-day-cell[data-date]', function () {
            const dateStr = $(this).data('date');
            if (!currentCalendarData || !currentCalendarData.days[dateStr]) return;

            const dayObj = currentCalendarData.days[dateStr];
            openDayDetailModal(dayObj);
        });

        function openDayDetailModal(dayObj) {
            $('#dayDetailTitle').text(`Penayangan: ${dayObj.day_name}`);
            $('#dayDetailSubtitle').text(`${dayObj.date} • Total ${dayObj.active_count} Slide`);
            $('#dayDetailCount').text(`${dayObj.active_count} Slide`);
            $('#dayDetailRotation').text(formatTime(dayObj.total_duration_sec));

            let html = '';
            if (!dayObj.items || dayObj.items.length === 0) {
                html = `
                    <div class="text-center py-8 text-slate-400 dark:text-slate-500">
                        <i class="fa-solid fa-circle-xmark text-2xl mb-2 block"></i>
                        <p class="text-xs">Tidak ada slide yang aktif pada tanggal ini.</p>
                    </div>
                `;
            } else {
                dayObj.items.forEach((it, idx) => {
                    const cColor = it.category_color || '#3b82f6';
                    html += `
                        <div class="p-3 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 flex items-center justify-between gap-3">
                            <div class="flex items-center space-x-2.5 min-w-0 flex-1">
                                <span class="w-6 h-6 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 font-mono font-bold text-xs flex items-center justify-center shrink-0">
                                    #${idx + 1}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h5 class="text-xs font-semibold text-slate-800 dark:text-slate-100 truncate">${it.title}</h5>
                                    <div class="flex items-center space-x-2 mt-0.5">
                                        <span class="text-[10px] font-semibold" style="color: ${cColor};">${it.category_name}</span>
                                        <span class="text-slate-300 dark:text-slate-700">•</span>
                                        <span class="text-[10px] text-slate-400">${it.schedule_text || 'Selalu Tayang'}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-xs font-mono font-semibold text-blue-600 dark:text-blue-400 bg-blue-500/10 px-2 py-1 rounded-lg shrink-0">
                                ${formatTime(it.duration)}
                            </span>
                        </div>
                    `;
                });
            }

            $('#dayDetailItemList').html(html);

            // Open Modal
            $('#dayDetailModal').removeClass('hidden');
        }

        function closeDayDetailModal() {
            $('#dayDetailModal').addClass('hidden');
        }

        $('#btnCloseDayDetailModal, #btnCloseDayDetailBtn').on('click', closeDayDetailModal);

        // Close Modal on Backdrop Click
        $('#dayDetailModal').on('click', function (e) {
            if (e.target === this) {
                closeDayDetailModal();
            }
        });

        // ─── INITIAL BOOT ─────────────────────────────────────────────────────
        const initialTvId = $('#tvSelector').val();
        updateSelectedPinBadge();
        loadPlaylist(initialTvId);
    });
</script>
<?= $this->endSection() ?>
