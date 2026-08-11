<?php
$firstSlide    = (!empty($initialPlaylist) && is_array($initialPlaylist)) ? $initialPlaylist[0] : null;
$firstIsImage  = ($firstSlide && $firstSlide['type'] === 'image' && !empty($firstSlide['file_url']));
$hasPlaylist   = !empty($initialPlaylist);
?>

<?= $this->extend('layouts/display_layout') ?>

<?= $this->section('head') ?>
<?php if ($firstIsImage): ?>
    <link rel="preload" as="image" href="<?= esc($firstSlide['file_url']) ?>" fetchpriority="high">
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="relative w-screen h-screen overflow-hidden bg-slate-950 text-white select-none">

    <!-- Top Overlay Badge Header -->
    <header class="absolute top-6 left-6 right-6 z-30 flex items-center justify-between pointer-events-none">
        <!-- TV Name & Location -->
        <div class="flex items-center space-x-3.5 bg-slate-900/80 border border-slate-800/80 px-5 py-3 rounded-2xl backdrop-blur-xl shadow-2xl">
            <div class="w-10 h-10 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center text-blue-400">
                <i class="fa-solid fa-tv text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-sm text-white tracking-tight leading-none"><?= esc($tv['name']) ?></h1>
                <p class="text-[11px] text-slate-400 mt-1 flex items-center">
                    <i class="fa-solid fa-location-dot text-blue-400 mr-1 text-[10px]"></i>
                    <span><?= esc($tv['location'] ?: 'Lokasi TV') ?></span>
                </p>
            </div>
        </div>

        <!-- Clock & Category Pill -->
        <div class="flex items-center space-x-3">
            <div id="currentCategoryBadge" class="<?= ($firstSlide && !empty($firstSlide['category_name'])) ? '' : 'hidden' ?> px-3.5 py-2 rounded-xl text-xs font-semibold border backdrop-blur-xl shadow-lg" style="<?= ($firstSlide && !empty($firstSlide['category_color'])) ? 'background-color:' . $firstSlide['category_color'] . '25; color:' . $firstSlide['category_color'] . '; border-color:' . $firstSlide['category_color'] . '50;' : '' ?>">
                <span id="currentCategoryText"><?= esc($firstSlide['category_name'] ?? 'Kategori') ?></span>
            </div>
            <div class="bg-slate-900/80 border border-slate-800/80 px-4 py-2.5 rounded-2xl backdrop-blur-xl shadow-2xl flex items-center space-x-2 text-blue-400 font-mono font-bold text-sm">
                <i class="fa-regular fa-clock text-xs text-slate-400"></i>
                <span id="displayClock">00:00:00</span>
            </div>
        </div>
    </header>

    <!-- Slide Containers -->

    <!-- Container 1: Image Slide -->
    <div id="imageSlideContainer" class="absolute inset-0 z-10 flex items-center justify-center bg-slate-950" style="opacity:<?= $firstIsImage ? '1' : '0' ?>;pointer-events:<?= $firstIsImage ? 'auto' : 'none' ?>;transition:opacity 0.7s ease;">
        <img id="imageSlideElement" src="<?= $firstIsImage ? esc($firstSlide['file_url']) : '' ?>" alt="Slide Image" fetchpriority="high" decoding="sync" style="width:100%;height:100%;object-fit:contain;">
    </div>

    <!-- Container 2: HTML5 Video Slide -->
    <div id="videoSlideContainer" class="absolute inset-0 z-10 flex items-center justify-center bg-black" style="opacity:0;pointer-events:none;transition:opacity 0.7s ease;">
        <video id="videoSlideElement" style="width:100%;height:100%;object-fit:contain;" playsinline muted></video>
    </div>

    <!-- Container 3: YouTube Embed Slide -->
    <div id="youtubeSlideContainer" class="absolute inset-0 z-10 flex items-center justify-center bg-black" style="opacity:0;pointer-events:none;transition:opacity 0.7s ease;">
        <iframe
            id="youtubeIframe"
            style="width:100%;height:100%;border:0;"
            src=""
            allow="autoplay; encrypted-media"
            allowfullscreen
            title="YouTube Video Slide">
        </iframe>
    </div>

    <!-- Container 4: Chart.js Slide -->
    <div id="chartSlideContainer" class="absolute inset-0 z-10 flex items-center justify-center p-16 md:p-24" style="opacity:0;pointer-events:none;transition:opacity 0.7s ease;">
        <div class="w-full h-full max-w-5xl bg-slate-900/70 border border-slate-800/90 rounded-3xl p-8 backdrop-blur-2xl shadow-2xl flex flex-col justify-between">
            <div class="mb-4">
                <span id="chartCategoryPill" class="text-[11px] font-bold uppercase tracking-wider text-blue-400">GRAFIK DATA</span>
                <h2 id="chartTitleText" class="text-2xl font-extrabold text-white tracking-tight mt-1">Judul Grafik</h2>
            </div>
            <div class="flex-1 relative w-full h-full">
                <canvas id="slideshowChartCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Container 5: Empty Playlist State -->
    <div id="emptySlideContainer" class="absolute inset-0 z-20 flex items-center justify-center bg-slate-950 p-8" style="opacity:<?= !$hasPlaylist ? '1' : '0' ?>;pointer-events:<?= !$hasPlaylist ? 'auto' : 'none' ?>;">
        <div class="text-center max-w-md bg-slate-900/50 border border-slate-800 p-8 rounded-3xl backdrop-blur-xl">
            <div class="w-16 h-16 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl mx-auto mb-4 animate-pulse">
                <i class="fa-solid fa-tv"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Belum Ada Konten Penayangan</h3>
            <p class="text-xs text-slate-400 mt-2">
                TV Display ini belum memiliki slide konten aktif. Silakan hubungkan kategori atau atur playlist melalui portal admin.
            </p>
        </div>
    </div>

    <!-- Bottom Slide Progress Bar -->
    <div class="absolute bottom-0 left-0 right-0 z-30 h-1.5 bg-slate-900/80">
        <div id="slideProgressBar" class="h-full bg-gradient-to-r from-blue-500 via-sky-500 to-blue-400 transition-none w-0"></div>
    </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function () {
    'use strict';

    const tvSlug = '<?= esc($tv['slug']) ?>';
    const tvId   = <?= (int) $tv['id'] ?>;

    const VIDEO_SLIDE_DELAY = 3; // Jeda 3 detik setelah video selesai sebelum ke slide berikutnya

    let playlist          = <?= json_encode($initialPlaylist ?? []) ?>;
    let currentSlideIndex = 0;
    let slideTimer        = null;
    let progressTimer     = null;
    let chartInstance     = null;

    // ─── Clock ────────────────────────────────────────────────────────────────
    function updateClock() {
        const now = new Date();
        const el  = document.getElementById('displayClock');
        if (!el) return;
        const pad = n => String(n).padStart(2, '0');
        el.textContent = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // ─── DOM Helpers ──────────────────────────────────────────────────────────
    function el(id) { return document.getElementById(id); }

    function showEl(id) {
        const e = el(id);
        if (!e) return;
        e.style.opacity       = '1';
        e.style.pointerEvents = 'auto';
    }

    function hideEl(id) {
        const e = el(id);
        if (!e) return;
        e.style.opacity       = '0';
        e.style.pointerEvents = 'none';
    }

    function hideAllContainers() {
        ['imageSlideContainer', 'videoSlideContainer', 'youtubeSlideContainer',
         'chartSlideContainer', 'emptySlideContainer'].forEach(hideEl);

        // Pause HTML5 video
        const vid = el('videoSlideElement');
        if (vid) { try { vid.pause(); } catch(e){} }

        // Clear YouTube iframe src to stop playback without JS API
        const ytFrame = el('youtubeIframe');
        if (ytFrame) ytFrame.src = '';
    }

    // ─── Progress Bar ─────────────────────────────────────────────────────────
    function animateProgressBar(durationSec) {
        clearInterval(progressTimer);
        const bar = el('slideProgressBar');
        if (!bar) return;
        bar.style.width = '0%';

        let elapsed      = 0;
        const intervalMs = 100;
        const totalMs    = durationSec * 1000;

        progressTimer = setInterval(() => {
            elapsed += intervalMs;
            bar.style.width = Math.min((elapsed / totalMs) * 100, 100) + '%';
            if (elapsed >= totalMs) clearInterval(progressTimer);
        }, intervalMs);
    }

    // ─── Fetch Playlist JSON ───────────────────────────────────────────────────
    function fetchPlaylist(callback) {
        fetch(`<?= base_url('display/') ?>${tvSlug}/playlist`)
            .then(r => r.json())
            .then(res => {
                if (res.status === 'success') {
                    playlist = res.playlist || [];
                    if (typeof callback === 'function') callback();
                }
            })
            .catch(() => { /* silent fail, will retry next poll */ });
    }

    // ─── Preload Next Image ────────────────────────────────────────────────────
    function preloadNextImage(nextIdx) {
        if (!playlist.length) return;
        const t = playlist[nextIdx % playlist.length];
        if (t && t.type === 'image' && t.file_url) {
            (new Image()).src = t.file_url;
        }
    }

    // ─── Chart.js Render ──────────────────────────────────────────────────────
    function renderChartSlide(item) {
        const canvas = el('slideshowChartCanvas');
        if (!canvas) return;

        if (chartInstance) { chartInstance.destroy(); chartInstance = null; }

        const ctx       = canvas.getContext('2d');
        const chartType = item.chart_type || 'bar';
        const labels    = Array.isArray(item.chart_labels) ? item.chart_labels : [];
        const datasets  = Array.isArray(item.chart_datasets) ? item.chart_datasets : [];

        const COLORS = ['#6366f1','#10b981','#f59e0b','#f43f5e','#8b5cf6','#06b6d4'];

        const fmtDatasets = datasets.map(ds => ({
            label: ds.label || 'Data',
            data:  Array.isArray(ds.data) ? ds.data : [],
            backgroundColor: chartType === 'pie'
                ? COLORS
                : (chartType === 'line'
                    ? (ds.color || '#3b82f6') + '44'
                    : (ds.color || '#3b82f6')),
            borderColor: ds.color || '#3b82f6',
            borderWidth: 3,
            fill: chartType === 'line',
            tension: 0.4,
            pointBackgroundColor: ds.color || '#3b82f6',
        }));

        chartInstance = new Chart(ctx, {
            type: chartType,
            data: { labels, datasets: fmtDatasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 800 },
                plugins: {
                    legend: { labels: { color: '#f8fafc', font: { size: 14, weight: 'bold' } } }
                },
                scales: chartType !== 'pie' ? {
                    x: { ticks: { color: '#cbd5e1', font: { size: 13 } }, grid: { color: '#334155' } },
                    y: { ticks: { color: '#cbd5e1', font: { size: 13 } }, grid: { color: '#334155' } }
                } : {}
            }
        });
    }

    // ─── Next Slide ───────────────────────────────────────────────────────────
    function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % (playlist.length || 1);
        showSlide(currentSlideIndex);
    }

    // ─── Show Slide ───────────────────────────────────────────────────────────
    function showSlide(index) {
        clearTimeout(slideTimer);
        clearInterval(progressTimer);

        if (!playlist || playlist.length === 0) {
            hideAllContainers();
            showEl('emptySlideContainer');
            const badge = el('currentCategoryBadge');
            if (badge) badge.classList.add('hidden');
            return;
        }

        // Clamp index
        currentSlideIndex = Math.max(0, Math.min(index, playlist.length - 1));
        const item = playlist[currentSlideIndex];

        hideAllContainers();

        // Update category badge
        if (item.category_name) {
            const color  = item.category_color || '#6366f1';
            const badge  = el('currentCategoryBadge');
            const txtEl  = el('currentCategoryText');
            if (txtEl) txtEl.textContent = item.category_name;
            if (badge) {
                badge.style.backgroundColor = color + '25';
                badge.style.color           = color;
                badge.style.borderColor     = color + '50';
                badge.classList.remove('hidden');
            }
        }

        const duration = Math.max(3, parseInt(item.display_duration_seconds || item.video_duration_seconds) || 10);

        // ── IMAGE ─────────────────────────────────────────────────────────────
        if (item.type === 'image') {
            const imgEl = el('imageSlideElement');
            if (imgEl && item.file_url) {
                imgEl.onerror = () => {
                    console.warn('Gagal memuat gambar:', item.file_url);
                };
                imgEl.src = item.file_url;
            }
            showEl('imageSlideContainer');
            preloadNextImage(currentSlideIndex + 1);
            animateProgressBar(duration);
            slideTimer = setTimeout(nextSlide, duration * 1000);

        // ── VIDEO (upload) ────────────────────────────────────────────────────
        } else if (item.type === 'video' && item.video_source === 'upload') {
            const vid = el('videoSlideElement');
            if (vid && item.file_url) {
                vid.src = item.file_url;
                showEl('videoSlideContainer');
                vid.load();
                vid.play().catch(() => {});
                vid.onended = () => {
                    clearTimeout(slideTimer);
                    slideTimer = setTimeout(nextSlide, VIDEO_SLIDE_DELAY * 1000);
                };
            } else {
                nextSlide();
                return;
            }
            animateProgressBar(duration);
            slideTimer = setTimeout(nextSlide, (duration + VIDEO_SLIDE_DELAY + 5) * 1000);

        // ── VIDEO (YouTube) ───────────────────────────────────────────────────
        } else if (item.type === 'video' && item.video_source === 'youtube') {
            const ytId    = item.youtube_id;
            const ytFrame = el('youtubeIframe');
            if (ytId && ytFrame) {
                // Use plain embed URL — no JS API needed, works even with ad blockers
                ytFrame.src = `https://www.youtube-nocookie.com/embed/${ytId}?autoplay=1&mute=1&controls=0&rel=0&modestbranding=1&enablejsapi=0&playsinline=1`;
                showEl('youtubeSlideContainer');
            } else {
                nextSlide();
                return;
            }
            animateProgressBar(duration);
            slideTimer = setTimeout(nextSlide, (duration + VIDEO_SLIDE_DELAY) * 1000);

        // ── CHART ─────────────────────────────────────────────────────────────
        } else if (item.type === 'chart') {
            const titleEl = el('chartTitleText');
            const catEl   = el('chartCategoryPill');
            if (titleEl) titleEl.textContent = item.title || '';
            if (catEl)   catEl.textContent   = item.category_name || 'GRAFIK DATA';
            showEl('chartSlideContainer');
            renderChartSlide(item);
            animateProgressBar(duration);
            slideTimer = setTimeout(nextSlide, duration * 1000);

        // ── UNKNOWN ───────────────────────────────────────────────────────────
        } else {
            nextSlide();
        }
    }

    // ─── SSE Listener ────────────────────────────────────────────────────────
    function initSseListener() {
        if (!window.EventSource) return;

        const evtSource = new EventSource(`<?= base_url('display/') ?>${tvSlug}/events`);

        ['playlist_changed', 'content_changed'].forEach(evt => {
            evtSource.addEventListener(evt, () => {
                fetchPlaylist(() => showSlide(currentSlideIndex));
            });
        });

        evtSource.addEventListener('pin_regenerated', () => {
            evtSource.close();
            alert('PIN Display TV telah diperbarui oleh Admin. Halaman akan dimuat ulang.');
            window.location.href = '<?= base_url('/') ?>';
        });

        evtSource.onerror = () => { /* auto-reconnect by browser */ };
    }

    // ─── Polling Fallback (every 60 s) ────────────────────────────────────────
    setInterval(() => fetchPlaylist(), 60000);

    // ─── Boot (Synchronous First Render for Zero-Delay LCP) ─────────────────
    showSlide(0);
    initSseListener();

})();
</script>
<?= $this->endSection() ?>
