<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'TV Display' ?> — Slideshow</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="<?= asset_url('vendor/fontawesome/css/all.min.css') ?>">

    <!-- Compiled Tailwind CSS v4 -->
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">

    <!-- Chart.js -->
    <script src="<?= asset_url('vendor/chartjs/chart.umd.js') ?>"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    </style>

    <?= $this->renderSection('head') ?>
</head>
<body class="h-full bg-slate-950 text-white select-none antialiased">
    <?= $this->renderSection('content') ?>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
