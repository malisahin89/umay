<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/base', ['title' => 'Panelim']) ?>

<?php $this->start('body') ?>
<div class="px-4 py-6 sm:px-6 max-w-5xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Hoş geldin, <?= $this->e($user->name ?? 'Kullanıcı') ?>! 👋</h1>
            <p class="text-sm text-gray-500 mt-1">Hesap bilgileriniz aşağıda yer almaktadır.</p>
        </div>
        <form method="POST" action="<?= $this->route('logout') ?>">
            <?= $this->csrf() ?>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                Çıkış Yap
            </button>
        </form>
    </div>

    <?php if (! empty($success)) { ?>
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <h3 class="text-sm font-medium text-green-800"><?= $this->e($success) ?></h3>
        </div>
    <?php } ?>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Kullanıcı Adı</p>
            <p class="text-sm font-bold text-gray-900 mt-1">@<?= $this->e($user->username ?? '') ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">E-Posta</p>
            <p class="text-sm font-bold text-gray-900 mt-1"><?= $this->e($user->email ?? '') ?></p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Rol</p>
            <p class="text-sm font-bold text-gray-900 mt-1">
                <?= ($user->role ?? 'member') === 'admin' ? 'Yönetici' : 'Üye' ?>
            </p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-gray-700">Hesap Bilgileri</h2>
        </div>
        <dl class="divide-y divide-gray-100">
            <div class="px-5 py-3.5 flex items-center gap-4">
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide w-32 flex-shrink-0">Ad Soyad</dt>
                <dd class="text-sm text-gray-900"><?= $this->e(trim(($user->name ?? '').' '.($user->surname ?? ''))) ?></dd>
            </div>
            <div class="px-5 py-3.5 flex items-center gap-4">
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide w-32 flex-shrink-0">Kullanıcı Adı</dt>
                <dd class="text-sm text-gray-900">@<?= $this->e($user->username ?? '') ?></dd>
            </div>
            <div class="px-5 py-3.5 flex items-center gap-4">
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide w-32 flex-shrink-0">E-Posta</dt>
                <dd class="text-sm text-gray-900"><?= $this->e($user->email ?? '') ?></dd>
            </div>
            <div class="px-5 py-3.5 flex items-center gap-4">
                <dt class="text-xs font-medium text-gray-400 uppercase tracking-wide w-32 flex-shrink-0">Kayıt Tarihi</dt>
                <dd class="text-sm text-gray-900">
                    <?= $user && $user->created_at ? $this->e($user->created_at->format('d.m.Y')) : '-' ?>
                </dd>
            </div>
        </dl>
    </div>

</div>
<?php $this->end() ?>
