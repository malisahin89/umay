<?php declare(strict_types=1); ?>
<?php
/**
 * Structured editor for JSON columns — no raw JSON typing.
 *   mode 'keyvalue' → key + value rows  → stored as a JSON object
 *   mode 'list'     → single-value rows → stored as a JSON array
 * A hidden input (the real field) is (re)serialized by the shared form script.
 *
 * JSON kolonları için yapısal editör — ham JSON yazmak yok.
 *
 * @var string $name   full input name (e.g. "specifications" or "trans[tr][blocks]")
 * @var mixed  $value  array or JSON string
 * @var string $mode   'keyvalue' | 'list'
 * @var string $label
 */
$data = is_array($value) ? $value : (is_string($value) && $value !== '' ? json_decode($value, true) : []);
$data = is_array($data) ? $data : [];
$json = (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
$rowCls = 'w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 transition focus:ring-2 focus:ring-inset focus:ring-indigo-500';
$delCls = 'js-json-del flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-zinc-400 ring-1 ring-inset ring-zinc-200 transition hover:bg-red-50 hover:text-red-500';
?>
<label class="block text-sm font-medium text-zinc-700"><?= $this->e($label) ?></label>
<div class="mt-1 js-json rounded-xl bg-zinc-50 p-3 ring-1 ring-inset ring-zinc-200" data-mode="<?= $this->e($mode) ?>">
    <div class="js-json-rows space-y-2">
        <?php if ($mode === 'keyvalue') { ?>
            <?php foreach ($data as $k => $v) { ?>
                <div class="js-json-row flex items-center gap-2">
                    <input type="text" class="js-k <?= $rowCls ?>" value="<?= $this->e((string) $k) ?>" placeholder="Anahtar">
                    <input type="text" class="js-v <?= $rowCls ?>" value="<?= $this->e(is_scalar($v) ? (string) $v : (string) json_encode($v)) ?>" placeholder="Değer">
                    <button type="button" class="<?= $delCls ?>"><i class="fa-solid fa-xmark"></i></button>
                </div>
            <?php } ?>
        <?php } else { ?>
            <?php foreach ($data as $v) { ?>
                <div class="js-json-row flex items-center gap-2">
                    <input type="text" class="js-v <?= $rowCls ?>" value="<?= $this->e(is_scalar($v) ? (string) $v : (string) json_encode($v)) ?>" placeholder="Değer">
                    <button type="button" class="<?= $delCls ?>"><i class="fa-solid fa-xmark"></i></button>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
    <button type="button" class="js-json-add mt-2 inline-flex items-center gap-1.5 rounded-lg border border-dashed border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-500 transition hover:border-indigo-400 hover:text-indigo-600">
        <i class="fa-solid fa-plus text-xs"></i> Satır ekle
    </button>
    <input type="hidden" name="<?= $this->e($name) ?>" class="js-json-out" value="<?= $this->e($json) ?>">
</div>
