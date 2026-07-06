<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($title ?? 'Umay Framework') ?></title>
    <?php if (! empty($description)) { ?>
        <meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES) ?>">
        <meta property="og:description" content="<?= htmlspecialchars($description, ENT_QUOTES) ?>">
    <?php } ?>
    <meta property="og:title" content="<?= htmlspecialchars($title ?? 'Umay') ?>">
    <meta property="og:type" content="website">
    <link rel="icon" type="image/png" href="/umay.png">

    <!-- Compiled Tailwind CSS (self-hosted) — built by `npm run build:css` from
         resources/css/app.css, scanning views/**/*.php. Served from /public → 'self' in CSP,
         no runtime CDN compilation (was cdn.tailwindcss.com). Rebuild after adding classes.
         Derlenmiş Tailwind CSS (self-host) — `npm run build:css` ile üretilir. Runtime CDN
         derlemesi yok (eskiden cdn.tailwindcss.com'du). Yeni class ekleyince yeniden derle. -->
    <link rel="stylesheet" href="/css/app.css">

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style nonce="<?= $this->nonce() ?>">
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="antialiased">
    <?= $this->section('body') ?>
</body>
</html>
