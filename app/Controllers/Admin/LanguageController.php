<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Language;
use Core\Facades\DB;
use Core\Facades\View;
use Core\Request;

/**
 * Language management (list / create / edit / delete / set-default).
 * Dil yönetimi (listele / ekle / düzenle / sil / varsayılan yap).
 */
class LanguageController
{
    private function find(int|string $id): ?Language
    {
        /** @var Language|null $model */
        $model = Language::query()->find($id);

        return $model;
    }

    public function index(): void
    {
        View::render('admin/languages/index', [
            'title' => 'Diller',
            'languages' => Language::query()->orderByDesc('is_default')->orderBy('name')->get(),
        ]);
    }

    public function create(): void
    {
        View::render('admin/languages/form', ['title' => 'Yeni Dil', 'language' => null]);
    }

    public function edit(Request $request, string $id): void
    {
        $language = $this->find($id);
        if ($language === null) {
            abort(404);
        }

        View::render('admin/languages/form', ['title' => 'Dil Düzenle', 'language' => $language]);
    }

    public function store(Request $request): void
    {
        $errors = validate($request->all(), [
            'name' => 'required|min:2|max:100',
            'slug' => 'required|min:2|max:10|unique:languages,slug',
        ]);

        if ($errors) {
            $_SESSION['_flash_errors'] = $errors;
            $_SESSION['_old'] = $request->all();
            redirect('/admin/languages/create');

            return;
        }

        $lang = new Language;
        $lang->name = trim(is_string($n = $request->post('name')) ? $n : '');
        $lang->slug = trim(is_string($s = $request->post('slug')) ? $s : '');
        $lang->flag = is_string($f = $request->post('flag')) ? $f : null;
        $lang->status = in_array($request->post('status'), ['on', '1'], true) ? 1 : 0;
        $lang->is_default = false;
        $lang->save();
        Language::flushActive();

        flash('success', 'Dil eklendi.');
        redirect('/admin/languages');
    }

    public function update(Request $request, string $id): void
    {
        $lang = $this->find($id);
        if ($lang === null) {
            abort(404);
        }

        $lang->name = trim(is_string($n = $request->post('name')) ? $n : (string) $lang->name);
        $lang->flag = is_string($f = $request->post('flag')) ? $f : null;
        $lang->status = in_array($request->post('status'), ['on', '1'], true) ? 1 : 0;
        $lang->save();
        Language::flushActive();

        flash('success', 'Dil güncellendi.');
        redirect('/admin/languages');
    }

    public function setDefault(Request $request, string $id): void
    {
        $lang = $this->find($id);
        if ($lang === null) {
            abort(404);
        }

        // Clear all defaults first, then set the chosen one — the DB unique index on
        // the virtual is_default_flag column allows only one is_default = 1 at a time.
        // Önce tüm varsayılanları sıfırla, sonra seçileni ata — virtual is_default_flag
        // kolonundaki DB unique index aynı anda tek bir is_default = 1'e izin verir.
        DB::transaction(function () use ($lang) {
            Language::query()->update(['is_default' => false]);
            $lang->is_default = true;
            $lang->save();
        });
        Language::flushActive();

        flash('success', $lang->name.' varsayılan dil yapıldı.');
        redirect('/admin/languages');
    }

    public function destroy(Request $request, string $id): void
    {
        $lang = $this->find($id);

        if ($lang === null) {
            abort(404);
        }

        if ($lang->is_default) {
            flash('error', 'Varsayılan dil silinemez. Önce başka bir dili varsayılan yapın.');
            redirect('/admin/languages');

            return;
        }

        $lang->delete();
        Language::flushActive();

        flash('success', 'Dil silindi.');
        redirect('/admin/languages');
    }
}
