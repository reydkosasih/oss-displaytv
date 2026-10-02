<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $title ?? 'TV Display' ?> — Slideshow</title>
    
    <!-- Web App Manifest -->
    <link rel="manifest" href="<?= base_url('site.webmanifest') ?>">

    <!-- Theme Color & Mobile Status Bar (Android & iOS) -->
    <meta name="theme-color" content="#020617">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="OSS Display TV">

    <!-- Icons & Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/icons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/icons/icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="512x512" href="<?= base_url('assets/icons/icon-512x512.png') ?>">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/logo/logo.png') ?>">
    
    <!-- Self-hosted Inter Font: Preload critical woff2 subset (eliminates CDN roundtrip) -->
    <link rel="preload" href="<?= base_url('assets/fonts/inter-latin.woff2') ?>" as="font" type="font/woff2" crossorigin>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="<?= asset_url('vendor/fontawesome/css/all.min.css') ?>">

    <!-- Compiled Tailwind CSS v4 -->
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>

    <?= $this->renderSection('head') ?>
</head>
<body class="h-full bg-slate-950 text-white select-none antialiased">
    <?= $this->renderSection('content') ?>

    <!-- Chart.js — Sync di akhir body agar tersedia untuk inline scripts di renderSection('scripts') -->
    <script src="<?= asset_url('vendor/chartjs/chart.umd.js') ?>"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
