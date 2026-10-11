<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlspecialchars($title ?? 'Umay Framework') ?></title>
    <link rel="icon" type="image/png" href="/umay.png">

    <!-- Tailwind CSS (Play CDN) — intentionally NOT nonce'd.
         A nonce would override the CSP host allow-list and let this script run even
         in production, where the strict CSP (SecurityHeaders) deliberately omits
         cdn.tailwindcss.com. Without a nonce it loads in local (host is whitelisted)
         and is correctly blocked in production — self-host/compile your CSS there.
         Tailwind CSS (Play CDN) — bilerek nonce'SUZ.
         Nonce, CSP host izin listesini geçersiz kılıp bu script'i production'da bile
         çalıştırırdı; oysa sıkı CSP (SecurityHeaders) cdn.tailwindcss.com'u kasıtlı
         olarak dışlar. Nonce olmadan local'de yüklenir (host whitelisted), production'da
         doğru biçimde engellenir — orada CSS'inizi self-host edin/derleyin. -->
    <script src="https://cdn.tailwindcss.com"></script>

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
