<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/admin', ['title' => $title]) ?>
<?php $isEdit = $record !== null; ?>
<?php $action = $isEdit ? '/admin/'.$resource.'/'.(int) $record->id : '/admin/'.$resource; ?>
<?php
// Multipart form needed when any neutral field is an image upload.
// Herhangi bir neutral alan görsel yükleme ise multipart form gerekir.
$hasUpload = false;
foreach ($cfg['fields'] as $fMeta) {
    if (in_array($fMeta['type'] ?? '', ['image', 'gallery'], true)) { $hasUpload = true; break; }
}
// Any rich-text field → load Summernote assets.
// Herhangi bir rich-text alan → Summernote asset'lerini yükle.
$hasRich = false;
foreach ($cfg['translatable'] as $tMeta) {
    if (($tMeta['type'] ?? '') === 'richtext') { $hasRich = true; break; }
}

// Shared input styling — mirrors Filament's .fi-input-wrp: soft inset ring (not a hard
// border), white bg, subtle shadow, 2px primary ring on focus.
// Ortak input stili — Filament .fi-input-wrp'nin aynısı: yumuşak inset ring (sert kenar
// değil), beyaz zemin, hafif gölge, focus'ta 2px primary ring.
$inputCls = 'mt-1 w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 transition placeholder:text-zinc-400 focus:ring-2 focus:ring-inset focus:ring-indigo-500';

// Per-language accent palette (mirrors the reference translations-repeater colours).
// Dil başına vurgu paleti (referanstaki repeater renklerinin aynısı).
$palette = ['#3b82f6', '#f97316', '#22c55e', '#a855f7', '#ef4444', '#14b8a6', '#eab308', '#ec4899', '#6366f1', '#84cc16'];
?>

<form method="POST" action="<?= $this->e($action) ?>" class="mx-auto max-w-4xl space-y-6"<?= $hasUpload ? ' enctype="multipart/form-data"' : '' ?>>
    <?= $this->csrf() ?>

    <!-- Language-neutral fields / Dilden bağımsız alanlar -->
    <section class="rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/70">
        <div class="border-b border-zinc-100 px-6 py-4">
            <h2 class="text-sm font-semibold text-zinc-900">Genel Bilgiler</h2>
            <p class="mt-0.5 text-xs text-zinc-500">Dilden bağımsız alanlar.</p>
        </div>
        <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2">
            <?php foreach ($cfg['fields'] as $name => $meta) { ?>
                <?php
                $val = $neutral[$name] ?? '';
                $type = $meta['type'];
                $wide = in_array($type, ['checkbox', 'toggle', 'json', 'keyvalue', 'list', 'image', 'gallery'], true);
                ?>
                <div class="<?= $wide ? 'sm:col-span-2' : '' ?>">
                    <?php if ($type === 'checkbox' || $type === 'toggle') { ?>
                        <!-- Filament-style toggle switch / Filament tarzı toggle anahtarı -->
                        <label class="flex cursor-pointer items-center gap-3">
                            <input type="checkbox" name="<?= $this->e($name) ?>" value="1" <?= $val ? 'checked' : '' ?> class="peer sr-only">
                            <span class="relative h-6 w-11 rounded-full bg-zinc-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition after:content-[''] peer-checked:after:translate-x-5"></span>
                            <span class="text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></span>
                        </label>
                    <?php } elseif ($type === 'select') { ?>
                        <?php $options = is_array($meta['options'] ?? null) ? $meta['options'] : []; ?>
                        <label class="block text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></label>
                        <select name="<?= $this->e($name) ?>" class="<?= $inputCls ?>">
                            <?php foreach ($options as $ov => $ol) { ?>
                                <option value="<?= $this->e((string) $ov) ?>" <?= (string) $val === (string) $ov ? 'selected' : '' ?>><?= $this->e((string) $ol) ?></option>
                            <?php } ?>
                        </select>
                    <?php } elseif ($type === 'image') { ?>
                        <?php
                        $imgUrl = (is_string($val) && $val !== '')
                            ? ((str_starts_with($val, 'http') || str_starts_with($val, '/')) ? $val : '/storage/'.ltrim($val, '/'))
                            : null;
                        ?>
                        <label class="block text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></label>
                        <div class="mt-1 flex items-center gap-4">
                            <?php if ($imgUrl !== null) { ?>
                                <img src="<?= $this->e($imgUrl) ?>" alt="" class="h-20 w-20 rounded-lg object-cover ring-1 ring-zinc-950/10">
                            <?php } else { ?>
                                <div class="flex h-20 w-20 items-center justify-center rounded-lg text-zinc-300 ring-1 ring-dashed ring-zinc-300">
                                    <i class="fa-regular fa-image text-xl"></i>
                                </div>
                            <?php } ?>
                            <input type="file" name="<?= $this->e($name) ?>" accept="image/*"
                                   class="block w-full text-sm text-zinc-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                        <p class="mt-1 text-xs text-zinc-400">Otomatik WebP'ye çevrilir. Boş bırakılırsa mevcut görsel korunur.</p>
                    <?php } elseif ($type === 'gallery') { ?>
                        <?php $items = is_array($val) ? array_values(array_filter($val, 'is_string')) : []; ?>
                        <label class="block text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></label>
                        <?php if ($items !== []) { ?>
                            <div class="mt-2 grid grid-cols-3 gap-3 sm:grid-cols-5">
                                <?php foreach ($items as $img) { ?>
                                    <?php $u = (str_starts_with($img, 'http') || str_starts_with($img, '/')) ? $img : '/storage/'.ltrim($img, '/'); ?>
                                    <label class="group relative block cursor-pointer">
                                        <img src="<?= $this->e($u) ?>" alt="" class="aspect-square w-full rounded-lg object-cover ring-1 ring-zinc-950/10">
                                        <span class="absolute inset-0 flex items-center justify-center rounded-lg bg-red-600/0 opacity-0 transition group-hover:bg-red-600/70 group-hover:opacity-100">
                                            <i class="fa-solid fa-trash text-white"></i>
                                        </span>
                                        <input type="checkbox" name="gallery_remove[<?= $this->e($name) ?>][]" value="<?= $this->e($img) ?>" class="peer sr-only">
                                        <span class="absolute inset-0 hidden rounded-lg bg-red-600/70 peer-checked:flex items-center justify-center text-xs font-bold uppercase text-white">Silinecek</span>
                                    </label>
                                <?php } ?>
                            </div>
                            <p class="mt-1 text-xs text-zinc-400">Silmek için görsele tıklayın (kaydedince uygulanır).</p>
                        <?php } ?>
                        <input type="file" name="<?= $this->e($name) ?>[]" accept="image/*" multiple
                               class="mt-2 block w-full text-sm text-zinc-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-zinc-400">Birden çok görsel seçebilirsiniz; her biri WebP'ye çevrilir ve galeriye eklenir.</p>
                    <?php } elseif ($type === 'keyvalue' || $type === 'list') { ?>
                        <?= $this->insert('admin/partials/jsonfield', ['name' => $name, 'value' => $val, 'mode' => $type === 'keyvalue' ? 'keyvalue' : 'list', 'label' => $meta['label']]) ?>
                    <?php } elseif ($type === 'json') { ?>
                        <?php
                        $jsonText = is_array($val)
                            ? (string) json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                            : (is_string($val) ? $val : '');
                        ?>
                        <label class="block text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></label>
                        <textarea name="<?= $this->e($name) ?>" rows="4" placeholder='["ornek", "deger"]'
                                  class="<?= $inputCls ?> font-mono"><?= $this->e($jsonText) ?></textarea>
                    <?php } else { ?>
                        <?php
                        if ($type === 'datetime') {
                            $inputType = 'datetime-local';
                            $display = $val instanceof \DateTimeInterface
                                ? $val->format('Y-m-d\TH:i')
                                : (is_string($val) && $val !== '' ? date('Y-m-d\TH:i', (int) strtotime($val)) : '');
                        } else {
                            $inputType = $type === 'number' ? 'number' : 'text';
                            $display = is_scalar($val) ? (string) $val : '';
                        }
                        ?>
                        <label class="block text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></label>
                        <input type="<?= $inputType ?>" name="<?= $this->e($name) ?>" value="<?= $this->e($display) ?>" class="<?= $inputCls ?>">
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Many-to-many relations — chip multi-select (click to toggle, no Ctrl needed) -->
    <!-- Çoktan-çoğa ilişkiler — chip çoklu seçim (tıkla-seç, Ctrl gerekmez) -->
    <?php $relations = is_array($cfg['relations'] ?? null) ? $cfg['relations'] : []; ?>
    <?php if ($relations !== []) { ?>
        <section class="rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/70">
            <div class="border-b border-zinc-100 px-6 py-4">
                <h2 class="text-sm font-semibold text-zinc-900">İlişkiler</h2>
                <p class="mt-0.5 text-xs text-zinc-500">İlgili kayıtları seçmek için etikete tıklayın.</p>
            </div>
            <div class="space-y-6 p-6">
                <?php foreach ($relations as $rname => $rmeta) { ?>
                    <?php
                    $opts = ($relOptions ?? [])[$rname] ?? [];
                    $sel = ($relSelected ?? [])[$rname] ?? [];
                    $relLabel = $rmeta['label'] ?? $rname;
                    ?>
                    <div data-rel="<?= $this->e($rname) ?>">
                        <div class="mb-2.5 flex items-center justify-between gap-3">
                            <label class="text-sm font-medium text-zinc-700">
                                <?= $this->e($relLabel) ?>
                                <span class="ml-1 rounded-full bg-indigo-50 px-1.5 py-0.5 text-xs font-semibold text-indigo-600" data-relcount="<?= $this->e($rname) ?>"><?= (int) count($sel) ?></span>
                            </label>
                            <?php if (count($opts) > 8) { ?>
                                <input type="text" data-chipfilter="<?= $this->e($rname) ?>" placeholder="Filtrele…"
                                       class="w-40 rounded-lg border-0 bg-zinc-100 px-3 py-1.5 text-xs text-zinc-700 outline-none ring-1 ring-inset ring-transparent transition placeholder:text-zinc-400 focus:bg-white focus:ring-indigo-500">
                            <?php } ?>
                        </div>
                        <?php if ($opts === []) { ?>
                            <div class="rounded-lg border border-dashed border-zinc-200 px-4 py-6 text-center text-sm text-zinc-400">
                                Henüz <?= $this->e($relLabel) ?> yok.
                            </div>
                        <?php } else { ?>
                            <div class="flex flex-wrap gap-2" data-chips="<?= $this->e($rname) ?>">
                                <?php foreach ($opts as $id => $label) { ?>
                                    <label class="chip cursor-pointer" data-label="<?= $this->e(mb_strtolower((string) $label)) ?>">
                                        <input type="checkbox" name="rel[<?= $this->e($rname) ?>][]" value="<?= (int) $id ?>" class="peer sr-only" <?= in_array((int) $id, $sel, true) ? 'checked' : '' ?>>
                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-zinc-200 bg-white px-3 py-1.5 text-sm font-medium text-zinc-600 transition select-none hover:border-zinc-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:[&_.chk]:opacity-100 peer-checked:[&_.chk]:w-2.5">
                                            <i class="chk fa-solid fa-check w-0 text-[10px] text-indigo-500 opacity-0 transition-all"></i>
                                            <?= $this->e((string) $label) ?>
                                        </span>
                                    </label>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
    <?php } ?>

    <!-- Per-language translation tabs / Dile göre çeviri sekmeleri -->
    <section class="rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/70">
        <div class="border-b border-zinc-100 px-6 py-4">
            <h2 class="text-sm font-semibold text-zinc-900">Çeviriler</h2>
            <p class="mt-0.5 text-xs text-zinc-500">Her dil için içerik girin. Renk, dili ayırt etmeniz içindir.</p>
        </div>
        <div class="p-6">
            <div class="mb-5 flex flex-wrap gap-1.5" id="langTabs">
                <?php foreach ($languages as $i => $lang) { ?>
                    <?php $color = $palette[$i % count($palette)]; ?>
                    <button type="button" data-tab="<?= $this->e($lang->slug) ?>"
                            class="lang-tab inline-flex items-center gap-2 rounded-lg px-3 py-1.5 text-sm font-medium transition <?= $i === 0 ? 'bg-zinc-100 text-zinc-900' : 'text-zinc-500 hover:bg-zinc-50' ?>">
                        <span class="h-2 w-2 rounded-full" style="background:<?= $color ?>"></span>
                        <?= $this->e($lang->flag) ?> <?= $this->e($lang->name) ?>
                    </button>
                <?php } ?>
            </div>

            <?php foreach ($languages as $i => $lang) { ?>
                <?php $slug = (string) $lang->slug; ?>
                <?php $t = $trans[$slug] ?? null; ?>
                <?php $color = $palette[$i % count($palette)]; ?>
                <div class="lang-panel space-y-4 rounded-lg py-4 pl-5 pr-1 <?= $i === 0 ? '' : 'hidden' ?>"
                     data-panel="<?= $this->e($slug) ?>"
                     style="border-left:4px solid <?= $color ?>;background:<?= $color ?>0f">
                    <?php $titleField = (string) ($cfg['title_field'] ?? ''); ?>
                    <?php foreach ($cfg['translatable'] as $fname => $meta) { ?>
                        <?php
                        $fval = $t !== null ? $t->getAttribute($fname) : '';
                        $fvalStr = is_scalar($fval) ? (string) $fval : '';
                        $roleClass = $fname === $titleField ? ' js-title' : ($fname === 'slug' ? ' js-slug' : '');
                        ?>
                        <div>
                            <?php if ($meta['type'] === 'keyvalue' || $meta['type'] === 'list') { ?>
                                <?= $this->insert('admin/partials/jsonfield', ['name' => 'trans['.$slug.']['.$fname.']', 'value' => $fval, 'mode' => $meta['type'] === 'keyvalue' ? 'keyvalue' : 'list', 'label' => $meta['label']]) ?>
                            <?php } else { ?>
                                <label class="block text-sm font-medium text-zinc-700"><?= $this->e($meta['label']) ?></label>
                                <?php if ($meta['type'] === 'textarea' || $meta['type'] === 'json' || $meta['type'] === 'richtext') { ?>
                                    <?php
                                    $taClass = $inputCls;
                                    if ($meta['type'] === 'json') {
                                        $taClass .= ' font-mono';
                                    } elseif ($meta['type'] === 'richtext') {
                                        $taClass .= ' js-richtext';
                                    }
                                    ?>
                                    <textarea name="trans[<?= $this->e($slug) ?>][<?= $this->e($fname) ?>]" rows="4"
                                              class="<?= $taClass ?><?= $this->e($roleClass) ?>"><?= $this->e($fvalStr) ?></textarea>
                                <?php } else { ?>
                                    <input type="text" name="trans[<?= $this->e($slug) ?>][<?= $this->e($fname) ?>]"
                                           value="<?= $this->e($fvalStr) ?>" class="<?= $inputCls . $this->e($roleClass) ?>">
                                <?php } ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <p class="text-xs text-zinc-400">Başlık boş bırakılırsa bu dil kaydedilmez. Slug boşsa başlıktan üretilir.</p>
                </div>
            <?php } ?>
        </div>
    </section>

    <div class="flex items-center gap-3">
        <button class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            <i class="fa-solid fa-floppy-disk text-xs"></i> Kaydet
        </button>
        <a href="/admin/<?= $this->e($resource) ?>" class="rounded-lg border border-zinc-300 px-5 py-2.5 text-sm font-medium text-zinc-700 transition hover:bg-zinc-50">İptal</a>
    </div>
</form>

<script nonce="<?= $this->nonce() ?>">
document.querySelectorAll('.lang-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var slug = btn.getAttribute('data-tab');
        document.querySelectorAll('.lang-tab').forEach(function (b) {
            b.classList.remove('bg-zinc-100', 'text-zinc-900');
            b.classList.add('text-zinc-500');
        });
        btn.classList.add('bg-zinc-100', 'text-zinc-900');
        btn.classList.remove('text-zinc-500');
        document.querySelectorAll('.lang-panel').forEach(function (p) {
            p.classList.toggle('hidden', p.getAttribute('data-panel') !== slug);
        });
    });
});

// Slug auto-generation — mirrors PHP str_slug() (Turkish-safe). On title blur, fill an
// empty slug in the SAME language panel; manual edits are never overwritten.
// Slug otomatik üretimi — PHP str_slug()'ın aynısı. Başlıktan çıkınca AYNI panelde boş
// slug'ı doldurur; elle yazılan ezilmez.
function umaySlugify(text) {
    var map = { 'ç':'c','ğ':'g','ı':'i','ö':'o','ş':'s','ü':'u','Ç':'c','Ğ':'g','İ':'i','Ö':'o','Ş':'s','Ü':'u' };
    return (text || '').replace(/[çğıöşüÇĞİÖŞÜ]/g, function (c) { return map[c] || c; })
        .toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/[\s-]+/g, '-').replace(/^-+|-+$/g, '');
}
document.querySelectorAll('.lang-panel').forEach(function (panel) {
    var title = panel.querySelector('.js-title'), slug = panel.querySelector('.js-slug');
    if (!title || !slug) return;
    title.addEventListener('blur', function () {
        if (slug.value.trim() === '') { slug.value = umaySlugify(title.value); }
    });
});

// Relations chip multi-select: live selected-count badge + client-side filter.
// İlişki chip çoklu-seçim: canlı seçili-sayı rozeti + istemci-taraflı filtre.
document.querySelectorAll('[data-rel]').forEach(function (group) {
    var name = group.getAttribute('data-rel');
    var badge = group.querySelector('[data-relcount="' + name + '"]');
    var boxes = group.querySelectorAll('input[type="checkbox"]');
    function refresh() {
        if (!badge) return;
        var n = 0;
        boxes.forEach(function (b) { if (b.checked) n++; });
        badge.textContent = n;
    }
    boxes.forEach(function (b) { b.addEventListener('change', refresh); });
    var filter = group.querySelector('[data-chipfilter="' + name + '"]');
    if (filter) {
        filter.addEventListener('input', function () {
            var q = filter.value.trim().toLowerCase();
            group.querySelectorAll('.chip').forEach(function (chip) {
                var label = chip.getAttribute('data-label') || '';
                chip.style.display = (q === '' || label.indexOf(q) !== -1) ? '' : 'none';
            });
        });
    }
});

// Structured JSON editors (key-value / list) → serialize rows into the hidden input.
// Yapısal JSON editörleri (key-value / liste) → satırları gizli input'a serialize et.
document.querySelectorAll('.js-json').forEach(function (comp) {
    var mode = comp.getAttribute('data-mode');
    var rows = comp.querySelector('.js-json-rows');
    var out = comp.querySelector('.js-json-out');
    var addBtn = comp.querySelector('.js-json-add');
    var rowCls = 'w-full rounded-lg border-0 bg-white px-3 py-2 text-sm text-zinc-900 shadow-sm ring-1 ring-inset ring-zinc-300 transition focus:ring-2 focus:ring-inset focus:ring-indigo-500';
    var delCls = 'js-json-del flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-zinc-400 ring-1 ring-inset ring-zinc-200 transition hover:bg-red-50 hover:text-red-500';
    function serialize() {
        var res = mode === 'keyvalue' ? {} : [];
        rows.querySelectorAll('.js-json-row').forEach(function (r) {
            if (mode === 'keyvalue') {
                var k = (r.querySelector('.js-k') || {}).value;
                var v = (r.querySelector('.js-v') || {}).value;
                if (k && k.trim()) res[k.trim()] = v;
            } else {
                var v = (r.querySelector('.js-v') || {}).value;
                if (v && v.trim()) res.push(v.trim());
            }
        });
        out.value = JSON.stringify(res);
    }
    function makeRow() {
        var row = document.createElement('div');
        row.className = 'js-json-row flex items-center gap-2';
        var html = '';
        if (mode === 'keyvalue') html += '<input type="text" class="js-k ' + rowCls + '" placeholder="Anahtar">';
        html += '<input type="text" class="js-v ' + rowCls + '" placeholder="Değer">';
        html += '<button type="button" class="' + delCls + '"><i class="fa-solid fa-xmark"></i></button>';
        row.innerHTML = html;
        return row;
    }
    if (addBtn) addBtn.addEventListener('click', function () { rows.appendChild(makeRow()); serialize(); });
    comp.addEventListener('input', serialize);
    comp.addEventListener('click', function (e) {
        var del = e.target.closest('.js-json-del');
        if (del) { del.closest('.js-json-row').remove(); serialize(); }
    });
    serialize();
});
</script>

<?php if ($hasRich) { ?>
    <!-- Rich-text editor (Summernote Lite) — self-hosted; CSP-compatible (same-origin). -->
    <link rel="stylesheet" href="/vendor/summernote/summernote-lite.min.css">
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/summernote/summernote-lite.min.js"></script>
    <script src="/vendor/summernote/lang/summernote-tr-TR.min.js"></script>
    <script nonce="<?= $this->nonce() ?>">
    (function () {
        var csrfEl = document.querySelector('input[name="csrf_token"]');
        var CSRF = csrfEl ? csrfEl.value : '';
        function uploadRichImage(file, $editor) {
            var data = new FormData();
            data.append('image', file);
            jQuery.ajax({
                url: '/admin/uploads/image', method: 'POST', data: data,
                processData: false, contentType: false, headers: { 'X-CSRF-TOKEN': CSRF },
                success: function (res) {
                    if (res && res.url) { $editor.summernote('insertImage', res.url); }
                    else { alert('Görsel yüklenemedi.'); }
                },
                error: function (xhr) {
                    var msg = 'Görsel yüklenemedi.';
                    try { msg = JSON.parse(xhr.responseText).error || msg; } catch (e) {}
                    alert(msg);
                }
            });
        }
        jQuery('.js-richtext').each(function () {
            var $ta = jQuery(this);
            $ta.summernote({
                height: 260, lang: 'tr-TR',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        for (var i = 0; i < files.length; i++) { uploadRichImage(files[i], $ta); }
                    }
                }
            });
        });
        var form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function () {
                jQuery('.js-richtext').each(function () { jQuery(this).val(jQuery(this).summernote('code')); });
            });
        }
    })();
    </script>
<?php } ?>
