<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/base', ['title' => 'Giriş Yap']) ?>

<?php $this->start('body') ?>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Hesabınıza Giriş Yapın
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Veya
            <a href="<?= $this->route('register.show') ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                yeni bir hesap oluşturun
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="<?= $this->route('login.authenticate') ?>">
                <?= $this->csrf() ?>

                <?php if (! empty($error)) { ?>
                    <div class="rounded-md bg-red-50 p-4">
                        <h3 class="text-sm font-medium text-red-800"><?= $this->e($error) ?></h3>
                    </div>
                <?php } ?>

                <?php if (! empty($success)) { ?>
                    <div class="rounded-md bg-green-50 p-4">
                        <h3 class="text-sm font-medium text-green-800"><?= $this->e($success) ?></h3>
                    </div>
                <?php } ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E-Posta Adresi</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="<?= $this->old('email') ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox"
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">Beni Hatırla</label>
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Giriş Yap
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>
