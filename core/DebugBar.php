<?php

declare(strict_types=1);

namespace Core;

use Core\Profiler\Profiler;

/**
 * Umay Debug Bar — Profiler Facade.
 *
 * Geriye dönük uyumluluk için tüm static API'ler korunmuştur.
 * Tüm çağrılar Core\Profiler\Profiler sınıfına yönlendirilir.
 *
 * Paneller: Timeline · Route · Queries · Models · Views · Events · Cache · Mails · Logs · Auth · Session · PHP · Request · History
 */
class DebugBar
{
    // ── Public API (Profiler'a delege) ────────────────────────────────────

    public static function init(): void
    {
        Profiler::init();
    }

    public static function isEnabled(): bool
    {
        return Profiler::isEnabled();
    }

    public static function startMeasure(string $name, ?float $start = null): void
    {
        Profiler::startMeasure($name, $start);
    }

    public static function stopMeasure(string $name): void
    {
        Profiler::stopMeasure($name);
    }

    /** Eloquent query listener'dan çağrılır — caller tespiti dahil */
    public static function addQuery(array $q): void
    {
        Profiler::addQuery($q);
    }

    public static function addLog(string $level, string $message, array $context = []): void
    {
        Profiler::addLog($level, $message, $context);
    }

    public static function addView(string $template, array $data = []): void
    {
        Profiler::addView($template, $data);
    }

    public static function addEvent(string $eventClass, mixed $payload = null): void
    {
        Profiler::addEvent($eventClass, $payload);
    }

    public static function addCacheOp(string $type, string $key, bool $hit = false): void
    {
        Profiler::addCacheOp($type, $key, $hit);
    }

    public static function addMail(array $mail): void
    {
        Profiler::addMail($mail);
    }

    public static function setRoute(array $info): void
    {
        Profiler::setRoute($info);
    }

    public static function addException(\Throwable $e): void
    {
        Profiler::addException($e);
    }

    /** Middleware çalışma süresini kaydet */
    public static function addMiddlewareTiming(string $name, float $ms): void
    {
        Profiler::addMiddlewareTiming($name, $ms);
    }

    /**
     * Toolbar HTML'ini döndürür.
     * View::render() tarafından </body> öncesine enjekte edilir.
     */
    public static function render(): string
    {
        return Profiler::renderToolbar();
    }

    /**
     * debug_backtrace içinde ilk app/ dosyasını ve model adını bulur.
     */
    public static function findCaller(): array
    {
        return Profiler::findCaller();
    }
}
