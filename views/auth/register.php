<?php declare(strict_types=1); ?>
<?php $this->layout('layouts/base', ['title' => 'Kayıt Ol']) ?>

<?php $this->start('body') ?>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Yeni Hesap Oluşturun</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Veya
            <a href="<?= $this->route('login.show') ?>" class="font-medium text-indigo-600 hover:text-indigo-500">
                mevcut hesabınıza giriş yapın
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" method="POST" action="<?= $this->route('register.store') ?>">
                <?= $this->csrf() ?>

                <?php if (! empty($error)) { ?>
                    <div class="rounded-md bg-red-50 p-4">
                        <h3 class="text-sm font-medium text-red-800"><?= $this->e($error) ?></h3>
                    </div>
                <?php } ?>

                <?php if (! empty($errors)) { ?>
                    <div class="rounded-md bg-red-50 p-4">
                        <h3 class="text-sm font-medium text-red-800">Lütfen aşağıdaki hataları düzeltin:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                            <?php foreach ($errors as $fieldMessages) { ?>
                                <?php foreach ((array) $fieldMessages as $message) { ?>
                                    <li><?= $this->e($message) ?></li>
                                <?php } ?>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Ad</label>
                        <input id="name" name="name" type="text" required value="<?= $this->old('name') ?>"
                               class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700">Soyad</label>
                        <input id="surname" name="surname" type="text" required value="<?= $this->old('surname') ?>"
                               class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Kullanıcı Adı</label>
                    <input id="username" name="username" type="text" required value="<?= $this->old('username') ?>"
                           class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">E-Posta Adresi</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="<?= $this->old('email') ?>"
                           class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
                    <input id="password" name="password" type="password" required placeholder="En az 8 karakter"
                           class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Şifre Tekrar</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Kayıt Ol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $this->end() ?>
