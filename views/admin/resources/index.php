<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/admin', ['title' => $cfg['label'] ?? $resource]) ?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold text-zinc-900"><?= $this->e($cfg['label'] ?? $resource) ?></h2>
        <p class="mt-1 text-sm text-zinc-500"><?= (int) count($rows) ?> kayıt</p>
    </div>
    <a href="/admin/<?= $this->e($resource) ?>/create"
       class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
        <i class="fa-solid fa-plus text-xs"></i> Yeni Ekle
    </a>
</div>

<div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200/70">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-200 bg-zinc-50 text-left text-xs font-semibold uppercase tracking-wide text-zinc-500">
                    <th class="px-5 py-3 w-16">ID</th>
                    <th class="px-5 py-3">Başlık</th>
                    <th class="px-5 py-3">Diller</th>
                    <th class="px-5 py-3 text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100">
                <?php if (empty($rows)) { ?>
                    <tr><td colspan="4" class="px-5 py-12 text-center text-zinc-400">
                        <i class="fa-regular fa-folder-open mb-2 block text-2xl text-zinc-300"></i>
                        Kayıt yok.
                    </td></tr>
                <?php } ?>
                <?php foreach ($rows as $row) { ?>
                    <tr class="transition hover:bg-zinc-50">
                        <td class="px-5 py-3 text-zinc-400">#<?= (int) $row['id'] ?></td>
                        <td class="px-5 py-3 font-medium text-zinc-900"><?= $this->e($row['title']) ?></td>
                        <td class="px-5 py-3">
                            <div class="flex flex-wrap gap-1">
                                <?php foreach (($row['status'] ?? []) as $st) { ?>
                                    <span class="inline-block rounded px-1.5 py-0.5 text-xs font-semibold <?= $st['has'] ? 'bg-green-100 text-green-700' : 'bg-zinc-100 text-zinc-400' ?>"
                                          title="<?= $st['has'] ? 'Çeviri var' : 'Çeviri yok' ?>"><?= $this->e(strtoupper($st['slug'])) ?></span>
                                <?php } ?>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="/admin/<?= $this->e($resource) ?>/<?= (int) $row['id'] ?>/edit"
                                   class="inline-flex items-center gap-1.5 rounded-lg border border-zinc-300 px-2.5 py-1.5 text-xs font-medium text-zinc-700 transition hover:bg-zinc-50">
                                    <i class="fa-solid fa-pen text-[10px] text-zinc-400"></i> Düzenle
                                </a>
                                <form method="POST" action="/admin/<?= $this->e($resource) ?>/<?= (int) $row['id'] ?>/delete"
                                      onsubmit="return confirm('Silinsin mi?')">
                                    <?= $this->csrf() ?>
                                    <button class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 px-2.5 py-1.5 text-xs font-medium text-red-600 transition hover:bg-red-50">
                                        <i class="fa-solid fa-trash text-[10px]"></i> Sil
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
