<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/admin', ['title' => 'Diller']) ?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-zinc-900">Diller</h2>
        <p class="mt-1 text-sm text-zinc-500">Site dillerini yönetin, varsayılanı belirleyin.</p>
    </div>
    <a href="/admin/languages/create" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
        <i class="fa-solid fa-plus text-xs"></i> Yeni Dil
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/70">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                    <th class="px-5 py-3">Dil</th>
                    <th class="px-5 py-3">Slug</th>
                    <th class="px-5 py-3">Durum</th>
                    <th class="px-5 py-3">Varsayılan</th>
                    <th class="px-5 py-3 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                <?php foreach ($languages as $lang) { ?>
                    <tr class="transition hover:bg-zinc-50">
                        <td class="px-5 py-3 font-medium text-zinc-900">
                            <span class="inline-flex items-center gap-2.5">
                                <img src="https://flagcdn.com/24x18/<?= $this->e(\App\Support\Locale::flag((string) $lang->slug, is_string($lang->flag) ? $lang->flag : null)) ?>.png" width="20" height="15" alt="" class="rounded-sm ring-1 ring-black/5">
                                <?= $this->e($lang->name) ?>
                            </span>
                        </td>
                        <td class="px-5 py-3"><span class="rounded bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-600"><?= $this->e($lang->slug) ?></span></td>
                        <td class="px-5 py-3">
                            <?php if ($lang->status) { ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-0.5 text-xs font-semibold text-green-700"><i class="fa-solid fa-circle text-[6px]"></i> Aktif</span>
                            <?php } else { ?>
                                <span class="inline-flex items-center gap-1 rounded-full bg-zinc-100 px-2 py-0.5 text-xs font-semibold text-zinc-400"><i class="fa-solid fa-circle text-[6px]"></i> Pasif</span>
                            <?php } ?>
                        </td>
                        <td class="px-5 py-3">
                            <?php if ($lang->is_default) { ?>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-700"><i class="fa-solid fa-star text-[10px]"></i> Varsayılan</span>
                            <?php } else { ?>
                                <form method="POST" action="/admin/languages/<?= (int) $lang->id ?>/default">
                                    <?= $this->csrf() ?>
                                    <button class="text-xs font-medium text-indigo-600 hover:underline">Varsayılan yap</button>
                                </form>
                            <?php } ?>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/admin/languages/<?= (int) $lang->id ?>/edit"
                                   class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 px-2.5 py-1.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50">
                                    <i class="fa-solid fa-pen text-[10px] text-zinc-400"></i> Düzenle
                                </a>
                                <?php if (! $lang->is_default) { ?>
                                    <form method="POST" action="/admin/languages/<?= (int) $lang->id ?>/delete" onsubmit="return confirm('Silinsin mi?')">
                                        <?= $this->csrf() ?>
                                        <button class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50">
                                            <i class="fa-solid fa-trash text-[10px]"></i> Sil
                                        </button>
                                    </form>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
