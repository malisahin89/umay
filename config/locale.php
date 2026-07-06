<?php

declare(strict_types=1);

/**
 * Localization configuration.
 * Yerelleştirme yapılandırması.
 *
 * default  — active locale when none is resolved from the URL.
 * fallback — locale used when a record has no translation in the active locale.
 *
 * default  — URL'den locale çözülemediğinde kullanılan aktif dil.
 * fallback — kayıt aktif dilde çevirisi olmadığında düşülen dil.
 */
return [
    'default' => $_ENV['APP_LOCALE'] ?? 'tr',
    'fallback' => $_ENV['APP_FALLBACK_LOCALE'] ?? 'tr',
];
