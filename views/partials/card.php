<?php
declare(strict_types=1);
/**
 * Card Component — Reusable card container partial.
 * Card Bileşeni — Yeniden kullanılabilir kart kapsayıcı parçası.
 *
 * Usage / Kullanım:
 *   <?php $this->insert('partials::card', ['title' => 'Kullanıcı Bilgileri', 'content' => $htmlContent]) ?>
 *   <?php $this->insert('partials::card', ['title' => 'İstatistik', 'icon' => 'fa-chart-bar', 'content' => '...']) ?>
 *
 * Variables / Değişkenler:
 *   $title   — Card header title / Kart başlık metni
 *   $icon    — (optional) FontAwesome icon class / FontAwesome ikon sınıfı
 *   $content — HTML content for card body / Kart gövdesi için HTML içerik
 *   $footer  — (optional) HTML content for card footer / Kart alt bilgi HTML içeriği
 */
$icon = $icon ?? null;
$footer = $footer ?? null;
?>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition hover:shadow-md">
    <?php if (! empty($title)) { ?>
    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
        <?php if ($icon) { ?>
        <i class="fas <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> text-gray-400"></i>
        <?php } ?>
        <h3 class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
    </div>
    <?php } ?>

    <div class="px-5 py-4">
        <?= $content ?? '' ?>
    </div>

    <?php if ($footer) { ?>
    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 text-sm text-gray-500">
        <?= $footer ?>
    </div>
    <?php } ?>
</div>
