<?php
declare(strict_types=1);
/**
 * Form Input Component — Reusable form input field partial.
 * Form Input Bileşeni — Yeniden kullanılabilir form input alanı parçası.
 *
 * Usage / Kullanım:
 *   <?php $this->insert('partials::input', ['name' => 'email', 'label' => 'E-Posta', 'type' => 'email']) ?>
 *   <?php $this->insert('partials::input', ['name' => 'password', 'label' => 'Şifre', 'type' => 'password']) ?>
 *   <?php $this->insert('partials::input', ['name' => 'name', 'label' => 'Ad', 'required' => true, 'placeholder' => 'Adınızı girin']) ?>
 *
 * Variables / Değişkenler:
 *   $name        — Input name attribute / Input name niteliği
 *   $label       — Label text / Etiket metni
 *   $type        — (optional, default: 'text') Input type / Input tipi
 *   $placeholder — (optional) Placeholder text / Placeholder metni
 *   $required    — (optional) Whether field is required / Alan zorunlu mu
 *   $value       — (optional) Default value (old() is used automatically) / Varsayılan değer
 *   $icon        — (optional) FontAwesome icon class / FontAwesome ikon sınıfı
 */
$type = htmlspecialchars($type ?? 'text', ENT_QUOTES, 'UTF-8');
$placeholder = $placeholder ?? '';
$required = $required ?? false;
// old()'dan HAM değeri iste (3. parametre false) — old() varsayılan olarak zaten
// escape eder; buradaki htmlspecialchars ile birleşince çift kodlanıyordu
// (O'Reilly value'da O&#039;Reilly görünüyordu). Tek escape katmanı burada.
// Ask old() for the RAW value (3rd param false) — old() escapes by default; combined
// with the htmlspecialchars here it double-encoded (O'Reilly rendered as O&#039;Reilly
// in the value attribute). The single escaping layer lives here.
$value = htmlspecialchars(old($name, (string) ($value ?? ''), false), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
// Attribute-safe name; raw $name is kept for old()/$errors lookups below.
// Nitelik-güvenli ad; ham $name aşağıdaki old()/$errors aramaları için korunur.
$nameAttr = htmlspecialchars((string) $name, ENT_QUOTES, 'UTF-8');
$icon = $icon ?? null;
$errors = $_SESSION['_flash_errors'] ?? [];
$hasError = isset($errors[$name]);
$errorMsg = $hasError ? (is_array($errors[$name]) ? $errors[$name][0] : $errors[$name]) : '';
?>

<div class="mb-4">
    <?php if (! empty($label)) { ?>
    <label for="<?= $nameAttr ?>" class="block text-sm font-medium text-gray-700 mb-1.5">
        <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
        <?php if ($required) { ?><span class="text-red-500">*</span><?php } ?>
    </label>
    <?php } ?>

    <div class="relative">
        <?php if ($icon) { ?>
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas <?= htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') ?> text-gray-400 text-sm"></i>
        </div>
        <?php } ?>
        <input
            type="<?= $type ?>"
            id="<?= $nameAttr ?>"
            name="<?= $nameAttr ?>"
            value="<?= $value ?>"
            placeholder="<?= htmlspecialchars($placeholder, ENT_QUOTES, 'UTF-8') ?>"
            <?= $required ? 'required' : '' ?>
            class="w-full px-3 py-2.5 <?= $icon ? 'pl-10' : '' ?> border <?= $hasError ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500' ?> rounded-lg text-sm transition focus:outline-none focus:ring-2"
        >
    </div>

    <?php if ($hasError) { ?>
    <p class="mt-1.5 text-xs text-red-600">
        <i class="fas fa-exclamation-circle mr-1"></i><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?>
    </p>
    <?php } ?>
</div>
