<?php

declare(strict_types=1);

namespace Core\Profiler\Contracts;

/**
 * DataCollectorInterface — Profiler data collector contract.
 * DataCollectorInterface — Profiler veri toplayıcı sözleşmesi.
 *
 * Every new metric type must implement this interface.
 * Her yeni metrik türü bu arayüzü implement etmelidir.
 * Collectors must work independently; if one fails, the others should continue.
 * Collector'lar bağımsız çalışmalı, biri patlarsa diğerleri devam etmeli.
 */
interface DataCollectorInterface
{
    /**
     * Unique name of the collector (panel tab name).
     * Collector'ın benzersiz adı (panel tab adı).
     * Example: "queries", "views", "timeline" // Örnek: "queries", "views", "timeline"
     */
    public function getName(): string;

    /**
     * Returns the collected data as a serialize-safe array.
     * Toplanan verileri serialize-safe array olarak döndürür.
     * This method is called at the end of the request.
     * Bu metot request sonunda çağrılır.
     */
    public function collect(): array;

    /**
     * Resets the collector (cleanup between requests).
     * Collector'ı sıfırlar (request arası temizlik).
     */
    public function reset(): void;
}
