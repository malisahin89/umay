<?php
declare(strict_types=1);
/**
 * Button Component — Reusable button partial.
 * Button Bileşeni — Yeniden kullanılabilir buton parçası.
 *
 * Usage / Kullanım:
 *   <?php $this->insert('partials::button', ['text' => 'Kaydet', 'type' => 'submit']) ?>
 *   <?php $this->insert('partials::button', ['text' => 'Sil', 'variant' => 'danger', 'icon' => 'fa-trash']) ?>
 *   <?php $this->insert('partials::button', ['text' => 'İptal', 'variant' => 'secondary', 'href' => '/dashboard']) ?>
 *
 * Variables / Değişkenler:
 *   $text    — Button text / Buton metni
 *   $variant — (optional) 'primary' | 'secondary' | 'danger' | 'success' (default: 'primary')
 *   $type    — (optional) 'submit' | 'button' | 'reset' (default: 'button')
 *   $icon    — (optional) FontAwesome icon class / FontAwesome ikon sınıfı
 *   $href    — (optional) If set, renders as <a> link / Eğer verilirse <a> link olarak render eder
 *   $loading — (optional) Loading text / Yükleniyor metni
 *   $full    — (optional) Full width / Tam genişlik
 */
$variant = $variant ?? 'primary';
$type = htmlspecialchars($type ?? 'button', ENT_QUOTES, 'UTF-8');
$icon = $icon ?? null;
$href = $href ?? null;
$loading = $loading ?? null;
$full = $full ?? false;

$variants = [
    'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500',
    'secondary' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-indigo-500',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
    'success' => 'bg-emerald-600 hover:bg-emerald-700 text-white focus:ring-emerald-500',
];

$classes = 'inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-offset-2 '
    .($variants[$variant] ?? $variants['primary'])
    .($full ? ' w-full' : '');
?>

<?php if ($href) { ?>
<a href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" class="<?= $classes ?>">
    <?php if ($icon) { ?><i class="fas <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?>"></i><?php } ?>
    <?= htmlspecialchars($text ?? 'Button', ENT_QUOTES, 'UTF-8') ?>
</a>
<?php } else { ?>
<button type="<?= $type ?>" class="<?= $classes ?>">
    <?php if ($icon) { ?><i class="fas <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?>"></i><?php } ?>
    <?= htmlspecialchars($text ?? 'Button', ENT_QUOTES, 'UTF-8') ?>
</button>
<?php } ?>
