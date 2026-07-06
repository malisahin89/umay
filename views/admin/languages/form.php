<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/admin', ['title' => $title]) ?>
<?php
$inputCls = 'mt-1 w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 transition placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-500';
$isEdit = $language !== null;
?>

<div class="mx-auto max-w-xl">
    <?php if (! empty($errors)) { ?>
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
            <ul class="list-disc pl-5">
                <?php foreach ($errors as $fieldMessages) { ?>
                    <?php foreach ((array) $fieldMessages as $m) { ?><li><?= $this->e($m) ?></li><?php } ?>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>

    <form method="POST" action="<?= $isEdit ? '/admin/languages/'.(int) $language->id : '/admin/languages' ?>"
          class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-zinc-200/70">
        <?= $this->csrf() ?>

        <div>
            <label class="block text-sm font-medium text-zinc-700">Dil adı</label>
            <input name="name" required value="<?= $this->e($isEdit ? $language->name : $this->old('name')) ?>" class="<?= $inputCls ?>">
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-700">Slug (locale kodu, örn. tr, en)</label>
            <input name="slug" required <?= $isEdit ? 'readonly' : '' ?>
                   value="<?= $this->e($isEdit ? $language->slug : $this->old('slug')) ?>"
                   class="<?= $inputCls ?> <?= $isEdit ? 'bg-zinc-100 text-zinc-500' : '' ?>">
            <?php if ($isEdit) { ?><p class="mt-1 text-xs text-zinc-400">Slug değiştirilemez (çeviriler buna bağlıdır).</p><?php } ?>
        </div>

        <div>
            <label class="block text-sm font-medium text-zinc-700">Ülke kodu (bayrak)</label>
            <input name="flag" value="<?= $this->e($isEdit ? (string) $language->flag : '') ?>" placeholder="örn. tr, gb, de" class="<?= $inputCls ?>">
            <p class="mt-1 text-xs text-zinc-400">2 harfli ISO ülke kodu → flagcdn bayrağı. Boşsa dil kodundan tahmin edilir.</p>
        </div>

        <label class="flex cursor-pointer items-center gap-3">
            <input type="checkbox" name="status" value="1" <?= (! $isEdit || $language->status) ? 'checked' : '' ?> class="peer sr-only">
            <span class="relative h-6 w-11 rounded-full bg-zinc-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
            <span class="text-sm font-medium text-zinc-700">Aktif</span>
        </label>

        <div class="flex items-center gap-3 pt-2">
            <button class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
                <i class="fa-solid fa-floppy-disk text-xs"></i> Kaydet
            </button>
            <a href="/admin/languages" class="rounded-lg border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">İptal</a>
        </div>
    </form>
</div>
