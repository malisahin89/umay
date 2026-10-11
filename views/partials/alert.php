<?php
declare(strict_types=1);
/**
 * Alert Component — Reusable notification/alert partial.
 * Alert Bileşeni — Yeniden kullanılabilir bildirim/uyarı parçası.
 *
 * Usage / Kullanım:
 *   <?php $this->insert('partials::alert', ['type' => 'success', 'message' => 'İşlem başarılı!']) ?>
 *   <?php $this->insert('partials::alert', ['type' => 'error', 'message' => 'Bir hata oluştu.']) ?>
 *   <?php $this->insert('partials::alert', ['type' => 'warning', 'message' => 'Dikkat!']) ?>
 *   <?php $this->insert('partials::alert', ['type' => 'info', 'message' => 'Bilgilendirme.']) ?>
 *
 * Variables / Değişkenler:
 *   $type    — 'success' | 'error' | 'warning' | 'info'
 *   $message — Alert message text / Uyarı mesaj metni
 *   $dismissible — (optional) true to show close button / kapatma butonu göstermek için true
 */
$styles = [
    'success' => ['bg' => 'bg-green-50',  'border' => 'border-green-200', 'text' => 'text-green-800', 'icon' => 'fa-check-circle',      'iconColor' => 'text-green-400'],
    'error' => ['bg' => 'bg-red-50',    'border' => 'border-red-200',   'text' => 'text-red-800',   'icon' => 'fa-exclamation-circle', 'iconColor' => 'text-red-400'],
    'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'fa-exclamation-triangle', 'iconColor' => 'text-yellow-400'],
    'info' => ['bg' => 'bg-blue-50',   'border' => 'border-blue-200',  'text' => 'text-blue-800',  'icon' => 'fa-info-circle',        'iconColor' => 'text-blue-400'],
];

$s = $styles[$type ?? 'info'] ?? $styles['info'];
$dismissible = $dismissible ?? false;
?>

<?php if (! empty($message)) { ?>
<div class="<?= $s['bg'] ?> border <?= $s['border'] ?> <?= $s['text'] ?> rounded-xl px-5 py-4 flex items-start gap-3" role="alert">
    <i class="fas <?= $s['icon'] ?> <?= $s['iconColor'] ?> mt-0.5 flex-shrink-0"></i>
    <p class="text-sm flex-1"><?= htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
    <?php if ($dismissible) { ?>
    <button onclick="this.closest('[role=alert]').remove()" class="<?= $s['text'] ?> opacity-50 hover:opacity-100 transition">
        <i class="fas fa-times"></i>
    </button>
    <?php } ?>
</div>
<?php } ?>
