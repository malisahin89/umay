<?php

declare(strict_types=1);

namespace Core;

/**
 * Csp — request-local Content-Security-Policy nonce holder.
 * Csp — request bazlı Content-Security-Policy nonce tutucusu.
 *
 * The nonce is kept in a static (request-local) property instead of the shared
 * session. Storing it in $_SESSION let concurrent requests overwrite each other's
 * nonce, so a page could be served with a CSP header whose nonce no longer matched
 * the value rendered into its <script>/<style> tags — silently breaking them.
 *
 * Nonce, paylaşılan session yerine static (request bazlı) bir property'de tutulur.
 * $_SESSION'da saklamak, eşzamanlı isteklerin birbirinin nonce'unu ezmesine yol
 * açıyordu; böylece bir sayfa, nonce'u artık <script>/<style> etiketlerine basılan
 * değerle eşleşmeyen bir CSP header'ı ile sunulabilir ve sessizce bozulurdu.
 *
 * SecurityHeaders sets it once per request; the View `nonce()` helper reads it back.
 * SecurityHeaders her istekte bir kez set eder; View `nonce()` helper'ı geri okur.
 */
final class Csp
{
    private static ?string $nonce = null;

    /**
     * Current request nonce — generated lazily on first access.
     * Mevcut istek nonce'u — ilk erişimde tembel üretilir.
     */
    public static function nonce(): string
    {
        return self::$nonce ??= base64_encode(random_bytes(16));
    }

    /**
     * Force a fresh nonce for the current request (called by SecurityHeaders).
     * Mevcut istek için yeni nonce üret (SecurityHeaders tarafından çağrılır).
     */
    public static function reset(): void
    {
        self::$nonce = null;
    }
}
