<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Language;
use App\Support\ImageUploader;
use App\Support\Locale;
use App\Support\Translatable;
use Core\Facades\View;
use Core\Model;
use Core\Request;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Generic CRUD for every translatable resource, driven by config/admin_resources.php.
 * One controller manages posts, pages, categories, tags, products, slides, popups
 * and menu-items — each with per-locale translation tabs.
 *
 * config/admin_resources.php tarafından yönlendirilen, her çevrilebilir kaynak için
 * generic CRUD. Tek controller; posts, pages, categories, tags, products, slides,
 * popups ve menu-items'ı locale sekmeleriyle yönetir.
 */
class ResourceController
{
    /**
     * Resolve a resource config or 404.
     *
     * @return array<string, mixed>
     */
    private function config(string $resource): array
    {
        $cfg = config('admin_resources.'.$resource);

        if (! is_array($cfg)) {
            abort(404, "Bilinmeyen kaynak: {$resource}");
        }

        /** @var array<string, mixed> $cfg */
        return $cfg;
    }

    /** @return class-string<Model> */
    private function modelClass(string $resource): string
    {
        /** @var class-string<Model> $class */
        $class = $this->config($resource)['model'];

        return $class;
    }

    /**
     * Read a string value from the config array.
     *
     * @param  array<string, mixed>  $cfg
     */
    private function str(array $cfg, string $key): string
    {
        return is_string($v = $cfg[$key] ?? null) ? $v : '';
    }

    /** @return array<int, Language> */
    private function languages(): array
    {
        /** @var array<int, Language> $langs */
        $langs = Language::query()->where('status', 1)->orderByDesc('is_default')->get()->all();

        return $langs;
    }

    public function index(Request $request, string $resource): void
    {
        $cfg = $this->config($resource);
        $class = $this->modelClass($resource);
        $titleField = $this->str($cfg, 'title_field');
        $default = Locale::default();
        $languages = $this->languages();

        $rows = [];
        foreach ($class::query()->with('translations')->orderByDesc('id')->get() as $record) {
            /** @var Model&Translatable $record */
            $t = $record->translation($default);
            $title = $t !== null && is_string($t->{$titleField}) ? $t->{$titleField} : '—';

            // Which active languages already have a translation row — drives the
            // per-language status badges in the listing (mirrors the reference panel).
            // Hangi aktif dillerin çeviri satırı var — listedeki dil rozetlerini besler
            // (referans paneldeki davranışın aynısı).
            $present = [];
            /** @var iterable<Model> $translations */
            $translations = $record->getRelationValue('translations');
            foreach ($translations as $tr) {
                $s = $tr->getAttribute('language_slug');
                if (is_string($s)) {
                    $present[$s] = true;
                }
            }

            $status = [];
            foreach ($languages as $lang) {
                $slug = is_string($sv = $lang->getAttribute('slug')) ? $sv : '';
                $status[] = ['slug' => $slug, 'has' => isset($present[$slug])];
            }

            $rows[] = [
                'id' => $record->getKey(),
                'title' => $title,
                'status' => $status,
            ];
        }

        View::render('admin/resources/index', [
            'title' => $this->str($cfg, 'label'),
            'resource' => $resource,
            'cfg' => $cfg,
            'rows' => $rows,
        ]);
    }

    public function create(Request $request, string $resource): void
    {
        $cfg = $this->withResolvedOptions($this->config($resource), null);
        $relData = $this->relationData($cfg, null);

        View::render('admin/resources/form', [
            'title' => $this->str($cfg, 'label').' — Yeni',
            'resource' => $resource,
            'cfg' => $cfg,
            'record' => null,
            'neutral' => [],
            'trans' => [],
            'languages' => $this->languages(),
            'relOptions' => $relData['options'],
            'relSelected' => $relData['selected'],
        ]);
    }

    public function edit(Request $request, string $resource, string $id): void
    {
        $cfg = $this->config($resource);
        $class = $this->modelClass($resource);

        /** @var (Model&Translatable)|null $record */
        $record = $class::query()->find($id);
        if ($record === null) {
            abort(404);
        }

        $cfg = $this->withResolvedOptions($cfg, $record);

        $neutral = [];
        /** @var array<string, mixed> $fields */
        $fields = $cfg['fields'];
        foreach (array_keys($fields) as $name) {
            $neutral[$name] = $record->getAttribute($name);
        }

        $trans = [];
        /** @var iterable<Model> $translations */
        $translations = $record->getRelationValue('translations');
        foreach ($translations as $t) {
            $slug = $t->getAttribute('language_slug');
            $trans[is_string($slug) ? $slug : ''] = $t;
        }

        $relData = $this->relationData($cfg, $record);

        View::render('admin/resources/form', [
            'title' => $this->str($cfg, 'label').' — Düzenle',
            'resource' => $resource,
            'cfg' => $cfg,
            'record' => $record,
            'neutral' => $neutral,
            'trans' => $trans,
            'languages' => $this->languages(),
            'relOptions' => $relData['options'],
            'relSelected' => $relData['selected'],
        ]);
    }

    public function store(Request $request, string $resource): void
    {
        $cfg = $this->config($resource);
        $class = $this->modelClass($resource);

        /** @var Model&Translatable $record */
        $record = new $class;
        $this->fillNeutral($record, $cfg, $request);
        $this->fillImages($record, $cfg, $request, $resource);
        $this->fillGallery($record, $cfg, $request, $resource);

        // Auto-assign the owner FK (e.g. posts.user_id) to the current admin.
        // Sahip FK'sini (örn. posts.user_id) mevcut admin'e ata.
        $owner = $this->str($cfg, 'owner_field');
        if ($owner !== '') {
            $record->setAttribute($owner, (int) auth()?->getAuthIdentifier());
        }

        $record->save();
        $this->saveTranslations($record, $cfg, $request);
        $this->syncRelations($record, $cfg, $request);

        flash('success', $this->str($cfg, 'label').' oluşturuldu.');
        redirect('/admin/'.$resource);
    }

    public function update(Request $request, string $resource, string $id): void
    {
        $cfg = $this->config($resource);
        $class = $this->modelClass($resource);

        /** @var (Model&Translatable)|null $record */
        $record = $class::query()->find($id);
        if ($record === null) {
            abort(404);
        }

        $this->fillNeutral($record, $cfg, $request);
        $this->fillImages($record, $cfg, $request, $resource);
        $this->fillGallery($record, $cfg, $request, $resource);
        $record->save();
        $this->saveTranslations($record, $cfg, $request);
        $this->syncRelations($record, $cfg, $request);

        flash('success', $this->str($cfg, 'label').' güncellendi.');
        redirect('/admin/'.$resource);
    }

    public function destroy(Request $request, string $resource, string $id): void
    {
        $cfg = $this->config($resource);
        $class = $this->modelClass($resource);

        /** @var (Model&Translatable)|null $record */
        $record = $class::query()->find($id);
        if ($record !== null) {
            $record->delete(); // translations cascade via FK
        }

        flash('success', $this->str($cfg, 'label').' silindi.');
        redirect('/admin/'.$resource);
    }

    /**
     * Copy language-neutral fields from the request onto the base record.
     *
     * @param  array<string, mixed>  $cfg
     */
    private function fillNeutral(Model $record, array $cfg, Request $request): void
    {
        /** @var array<string, array{type: string, label: string}> $fields */
        $fields = $cfg['fields'];

        foreach ($fields as $name => $meta) {
            // Image/gallery fields are file uploads, handled in fillImages()/fillGallery().
            // Görsel/galeri alanları dosya yüklemesidir; fillImages()/fillGallery()'de işlenir.
            if ($meta['type'] === 'image' || $meta['type'] === 'gallery') {
                continue;
            }

            $raw = $request->post($name);

            // A checkbox always resolves (present = on, absent = off). Other field
            // types are only written when a value was actually submitted — otherwise
            // we leave the column untouched so its DB default (create) or existing
            // value (update) stands, instead of forcing NULL into a NOT NULL column.
            // Checkbox her zaman çözülür (var = açık, yok = kapalı). Diğer tipler
            // yalnızca bir değer gönderildiğinde yazılır — aksi halde sütun olduğu gibi
            // bırakılır; NOT NULL sütuna NULL zorlamak yerine DB default'u (create) ya
            // da mevcut değer (update) geçerli kalır.
            if ($meta['type'] === 'checkbox' || $meta['type'] === 'toggle') {
                $record->setAttribute($name, ($raw === 'on' || $raw === '1') ? 1 : 0);
            } elseif ($meta['type'] === 'number') {
                if (is_numeric($raw)) {
                    $record->setAttribute($name, $raw);
                }
            } elseif ($meta['type'] === 'datetime') {
                // Empty datetime-local input clears the column instead of forcing an
                // invalid "" into a datetime cast.
                // Boş datetime-local alanı, cast'e geçersiz "" zorlamak yerine sütunu boşaltır.
                if (is_string($raw)) {
                    $record->setAttribute($name, $raw === '' ? null : $raw);
                }
            } elseif ($meta['type'] === 'select') {
                // A blank option (e.g. "— Yok —" for a nullable FK) clears the column.
                // Boş seçenek (örn. nullable FK için "— Yok —") sütunu boşaltır.
                if (is_string($raw)) {
                    $record->setAttribute($name, $raw === '' ? null : $raw);
                }
            } elseif (in_array($meta['type'], ['json', 'keyvalue', 'list'], true)) {
                // The column is array-cast on the model; decode the posted JSON (from the
                // structured key-value / list editor, or a raw textarea) to an array so the
                // cast encodes it exactly once. Invalid/blank clears the column.
                // Sütun modelde array-cast; gönderilen JSON'ı (yapısal editör ya da ham
                // textarea'dan) diziye çöz ki cast bir kez encode etsin. Geçersiz/boş → boşalt.
                if (is_string($raw)) {
                    $trimmed = trim($raw);
                    $decoded = $trimmed === '' ? null : json_decode($trimmed, true);
                    $record->setAttribute($name, is_array($decoded) ? $decoded : null);
                }
            } elseif (is_string($raw)) {
                $record->setAttribute($name, $raw);
            }
        }
    }

    /**
     * Process uploaded image fields → WebP, set the stored path on the record, and
     * delete the previous file when replaced. Fields with no new upload are left as-is
     * (so editing without re-uploading keeps the existing image).
     * Yüklenen görsel alanlarını işle → WebP, kaydedilen yolu kayda ata ve değişince
     * eski dosyayı sil. Yeni yükleme olmayan alanlar olduğu gibi bırakılır (yeniden
     * yüklemeden düzenlemek mevcut görseli korur).
     *
     * @param  array<string, mixed>  $cfg
     */
    private function fillImages(Model $record, array $cfg, Request $request, string $resource): void
    {
        /** @var array<string, array{type: string, label: string}> $fields */
        $fields = $cfg['fields'];

        foreach ($fields as $name => $meta) {
            if ($meta['type'] !== 'image' || ! $request->hasFile($name)) {
                continue;
            }

            $file = $request->file($name);
            if (! is_array($file)) {
                continue;
            }

            $old = $record->getAttribute($name);
            $path = ImageUploader::storeWebp($file, $resource);

            if ($path !== null) {
                $record->setAttribute($name, $path);
                if (is_string($old) && $old !== '' && $old !== $path) {
                    ImageUploader::delete($old);
                }
            }
        }
    }

    /**
     * Process "gallery" (multiple image) fields: drop images ticked for removal, append
     * newly uploaded ones (each → WebP), and store the resulting path array on the record.
     * "gallery" (çoklu görsel) alanlarını işle: silme işaretlileri çıkar, yeni yüklenenleri
     * ekle (her biri → WebP) ve elde edilen yol dizisini kayda yaz.
     *
     * @param  array<string, mixed>  $cfg
     */
    private function fillGallery(Model $record, array $cfg, Request $request, string $resource): void
    {
        /** @var array<string, array{type: string, label: string}> $fields */
        $fields = $cfg['fields'];

        foreach ($fields as $name => $meta) {
            if ($meta['type'] !== 'gallery') {
                continue;
            }

            // Start from the existing (string) paths.
            // Mevcut (string) yollardan başla.
            $current = $record->getAttribute($name);
            $images = is_array($current) ? array_values(array_filter($current, 'is_string')) : [];

            // Remove the paths the user ticked (and delete their files).
            // Kullanıcının işaretlediği yolları çıkar (ve dosyalarını sil).
            $removeAll = $request->post('gallery_remove');
            $remove = is_array($removeAll) && is_array($removeAll[$name] ?? null) ? $removeAll[$name] : [];
            if ($remove !== []) {
                $images = array_values(array_filter($images, static function (string $p) use ($remove): bool {
                    if (in_array($p, $remove, true)) {
                        ImageUploader::delete($p);

                        return false;
                    }

                    return true;
                }));
            }

            // Append newly uploaded files ($_FILES[$name] has parallel arrays for multiple).
            // Yeni yüklenen dosyaları ekle ($_FILES[$name] çoklu için paralel diziler tutar).
            $files = $request->file($name);
            if (is_array($files) && is_array($files['tmp_name'] ?? null)) {
                $tmps = $files['tmp_name'];
                $names = is_array($files['name'] ?? null) ? $files['name'] : [];
                $types = is_array($files['type'] ?? null) ? $files['type'] : [];
                $errors = is_array($files['error'] ?? null) ? $files['error'] : [];
                $sizes = is_array($files['size'] ?? null) ? $files['size'] : [];
                $count = count($tmps);
                for ($i = 0; $i < $count; $i++) {
                    $one = [
                        'name' => $names[$i] ?? '',
                        'type' => $types[$i] ?? '',
                        'tmp_name' => $tmps[$i] ?? '',
                        'error' => $errors[$i] ?? UPLOAD_ERR_NO_FILE,
                        'size' => $sizes[$i] ?? 0,
                    ];
                    $path = ImageUploader::storeWebp($one, $resource.'/gallery');
                    if ($path !== null) {
                        $images[] = $path;
                    }
                }
            }

            $record->setAttribute($name, $images);
        }
    }

    /**
     * Upsert one translation row per active language.
     *
     * @param  Model&Translatable  $record
     * @param  array<string, mixed>  $cfg
     */
    private function saveTranslations(Model $record, array $cfg, Request $request): void
    {
        /** @var array<string, array{type: string, label: string}> $translatable */
        $translatable = $cfg['translatable'];
        $titleField = $this->str($cfg, 'title_field');

        $trans = $request->post('trans');
        if (! is_array($trans)) {
            return;
        }

        /** @var class-string<Model> $tClass */
        $tClass = $record->translationModel();
        $fk = $record->translationForeignKey();

        foreach ($this->languages() as $lang) {
            $slug = is_string($s = $lang->getAttribute('slug')) ? $s : '';
            $values = $trans[$slug] ?? null;
            if (! is_array($values)) {
                continue;
            }

            // Skip languages left blank (no title) — avoids empty translation rows.
            // Başlıksız bırakılan dilleri atla — boş çeviri satırı oluşmasın.
            $titleValue = $values[$titleField] ?? null;
            if (! is_string($titleValue) || trim($titleValue) === '') {
                continue;
            }

            /** @var Model|null $t */
            $t = $tClass::query()
                ->where($fk, $record->getKey())
                ->where('language_slug', $slug)
                ->first();

            if ($t === null) {
                $t = new $tClass;
            }

            $fill = [$fk => $record->getKey(), 'language_slug' => $slug];
            foreach ($translatable as $field => $meta) {
                $fv = $values[$field] ?? null;

                // JSON-backed fields (structured keyvalue/list or raw json) are array-cast
                // on the translation model — decode the posted JSON to an array so the cast
                // encodes it exactly once (avoids double-encoding). Others store as string.
                // JSON-tabanlı alanlar (yapısal keyvalue/list ya da ham json) çeviri modelinde
                // array-cast — gönderilen JSON'ı diziye çöz ki cast bir kez encode etsin.
                if (in_array($meta['type'], ['keyvalue', 'list', 'json'], true)) {
                    $trimmed = is_string($fv) ? trim($fv) : '';
                    $decoded = $trimmed === '' ? null : json_decode($trimmed, true);
                    $fill[$field] = is_array($decoded) ? $decoded : null;
                } else {
                    $fill[$field] = is_string($fv) ? $fv : null;
                }
            }

            // Slug handling: auto-generate from the title when left blank, always
            // normalize (Turkish-safe), then de-duplicate within the same language.
            // Slug: boş bırakılırsa başlıktan üret, her zaman normalize et (Türkçe-güvenli),
            // sonra aynı dil içinde benzersizleştir.
            if (array_key_exists('slug', $translatable)) {
                $slugInput = is_string($fill['slug'] ?? null) ? trim($fill['slug']) : '';
                $slugValue = str_slug($slugInput !== '' ? $slugInput : $titleValue);
                $key = $t->getKey();
                $excludeId = $t->exists && is_numeric($key) ? (int) $key : null;
                $fill['slug'] = $this->uniqueSlug($tClass, $slugValue, $slug, $excludeId);
            }

            $t->fill($fill);
            $t->save();
        }
    }

    /**
     * Resolve select fields that pull options from another model (FK dropdowns) into a
     * concrete id→label options list — so the form renders a proper dropdown instead of a
     * raw ID box. A record is never offered as its own parent.
     * Başka modelden seçenek çeken select alanlarını (FK dropdown) somut id→label listesine
     * çevirir — form ham ID kutusu yerine düzgün açılır menü gösterir.
     *
     * @param  array<string, mixed>  $cfg
     * @return array<string, mixed>
     */
    private function withResolvedOptions(array $cfg, ?Model $current): array
    {
        if (! is_array($cfg['fields'] ?? null)) {
            return $cfg;
        }
        /** @var array<string, array<string, mixed>> $fields */
        $fields = $cfg['fields'];
        $default = Locale::default();
        $currentId = $current !== null && is_numeric($ck = $current->getKey()) ? (int) $ck : -1;

        foreach ($fields as $name => $meta) {
            if (($meta['type'] ?? '') !== 'select' || ! isset($meta['options_model'])) {
                continue;
            }
            $class = $meta['options_model'];
            if (! is_string($class) || ! is_subclass_of($class, Model::class)) {
                continue;
            }
            $titleField = is_string($meta['options_title'] ?? null) ? $meta['options_title'] : 'name';

            $opts = [];
            if (! empty($meta['nullable'])) {
                $opts[''] = '— Yok —';
            }
            foreach ($class::query()->orderByDesc('id')->limit(500)->get() as $rec) {
                if (! $rec instanceof Model) {
                    continue;
                }
                $id = is_numeric($k = $rec->getKey()) ? (int) $k : 0;
                if ($current !== null && $current::class === $class && $id === $currentId) {
                    continue; // never self-parent
                }
                if ($rec instanceof Translatable) {
                    $t = $rec->translation($default);
                    $lbl = $t !== null && is_string($t->{$titleField}) ? $t->{$titleField} : '#'.$id;
                } else {
                    $lv = $rec->getAttribute($titleField);
                    $lbl = is_scalar($lv) && (string) $lv !== '' ? (string) $lv : '#'.$id;
                }
                $opts[$id] = $lbl;
            }
            $fields[$name]['options'] = $opts;
        }

        $cfg['fields'] = $fields;

        return $cfg;
    }

    /**
     * Build select options + currently-selected IDs for each configured relation.
     * Options are labelled with the related record's default-locale title.
     * Her yapılandırılmış ilişki için seçenekler + seçili ID'leri kur. Seçenekler ilgili
     * kaydın varsayılan dildeki başlığıyla etiketlenir.
     *
     * @param  array<string, mixed>  $cfg
     * @return array{options: array<string, array<int, string>>, selected: array<string, array<int, int>>}
     */
    private function relationData(array $cfg, ?Model $record): array
    {
        $options = [];
        $selected = [];
        /** @var array<string, array<string, mixed>> $relations */
        $relations = is_array($cfg['relations'] ?? null) ? $cfg['relations'] : [];
        $default = Locale::default();

        foreach ($relations as $name => $meta) {
            $class = $meta['model'] ?? null;
            if (! is_string($class) || ! is_subclass_of($class, Model::class)) {
                continue;
            }
            $titleField = is_string($meta['title_field'] ?? null) ? $meta['title_field'] : 'name';

            $opts = [];
            foreach ($class::query()->with('translations')->orderByDesc('id')->get() as $rec) {
                if (! $rec instanceof Model) {
                    continue;
                }
                $id = is_numeric($k = $rec->getKey()) ? (int) $k : 0;
                $t = $rec instanceof Translatable ? $rec->translation($default) : null;
                $label = $t !== null && is_string($t->{$titleField}) ? $t->{$titleField} : '#'.$id;
                $opts[$id] = $label;
            }
            $options[$name] = $opts;

            $sel = [];
            if ($record !== null) {
                $relation = $record->{$name}();
                if ($relation instanceof BelongsToMany) {
                    foreach ($relation->get() as $r) {
                        $sel[] = is_numeric($rk = $r->getKey()) ? (int) $rk : 0;
                    }
                }
            }
            $selected[$name] = $sel;
        }

        return ['options' => $options, 'selected' => $selected];
    }

    /**
     * Sync each configured many-to-many relation from the posted rel[<name>][] IDs.
     * A relation left unchecked syncs to an empty set (detaches all).
     * Her yapılandırılmış çoktan-çoğa ilişkiyi gönderilen rel[<name>][] ID'lerinden
     * senkronla. İşaretlenmeyen ilişki boş kümeye senkronlanır (tümünü ayırır).
     *
     * @param  array<string, mixed>  $cfg
     */
    private function syncRelations(Model $record, array $cfg, Request $request): void
    {
        $relations = is_array($cfg['relations'] ?? null) ? $cfg['relations'] : [];
        if ($relations === []) {
            return;
        }

        $posted = $request->post('rel');
        $posted = is_array($posted) ? $posted : [];

        foreach (array_keys($relations) as $name) {
            $rawIds = $posted[$name] ?? null;
            $ids = is_array($rawIds)
                ? array_values(array_map(static fn ($v): int => is_numeric($v) ? (int) $v : 0, $rawIds))
                : [];

            $relation = $record->{$name}();
            if ($relation instanceof BelongsToMany) {
                $relation->sync($ids);
            }
        }
    }

    /**
     * Return a slug unique within one language, appending -1, -2, … on collision.
     * Bir dil içinde benzersiz slug döndürür; çakışmada -1, -2, … ekler.
     *
     * @param  class-string<Model>  $tClass
     */
    private function uniqueSlug(string $tClass, string $slug, string $languageSlug, ?int $excludeId): string
    {
        if ($slug === '') {
            $slug = 'icerik';
        }

        $base = $slug;
        $counter = 1;

        while (true) {
            $query = $tClass::query()
                ->where('slug', $slug)
                ->where('language_slug', $languageSlug);

            if ($excludeId !== null) {
                $query->where('id', '!=', $excludeId);
            }

            if (! $query->exists()) {
                return $slug;
            }

            $slug = $base.'-'.$counter;
            $counter++;
        }
    }
}
