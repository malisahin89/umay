<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Content translation for the "base table + <model>_translations" pattern.
 * "Temel tablo + <model>_translations" deseni için içerik çevirisi.
 *
 * A model using this trait keeps language-neutral columns on its own table and
 * language-specific columns on a sibling <Model>Translation model, joined by a
 * language_slug column (FK → languages.slug).
 *
 * Bu trait'i kullanan model, dilden bağımsız kolonları kendi tablosunda; dile
 * özel kolonları ise language_slug (FK → languages.slug) ile eşleşen kardeş
 * <Model>Translation modelinde tutar.
 *
 * Setup on the base model / Temel modelde kurulum:
 *   use HasTranslations;
 *   protected array $translatable = ['title', 'slug', 'content'];
 *
 * Usage / Kullanım:
 *   $post->title;                       // active locale, falls back to default
 *   $post->translation('en')?->title;   // explicit locale
 *   Post::whereTranslation('slug', $slug)->first();
 *
 * @property array<int, string> $translatable
 * @property-read Collection<int, Model> $translations
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> whereTranslation(string $field, mixed $value, ?string $locale = null)
 *
 * @mixin \Core\Model
 */
trait HasTranslations
{
    /** Sibling translation model class, e.g. App\Models\Post → App\Models\PostTranslation. */
    public function translationModel(): string
    {
        return static::class.'Translation';
    }

    /** FK on the translation table pointing back to the base row, e.g. post_id. */
    public function translationForeignKey(): string
    {
        return Str::snake(class_basename(static::class)).'_id';
    }

    public function translations(): HasMany
    {
        return $this->hasMany($this->translationModel(), $this->translationForeignKey());
    }

    /**
     * Translation row for the given (or active) locale, falling back to the
     * configured fallback locale when the requested one is missing.
     * Verilen (veya aktif) locale için çeviri satırı; istenen yoksa yapılandırılmış
     * fallback diline düşer.
     */
    public function translation(?string $locale = null): ?Model
    {
        $locale = $locale ?: Locale::get();

        $row = $this->translations->firstWhere('language_slug', $locale);

        if ($row === null && $locale !== Locale::fallback()) {
            $row = $this->translations->firstWhere('language_slug', Locale::fallback());
        }

        return $row;
    }

    /**
     * Transparently expose translatable fields off the base model
     * ($post->title → active translation's title).
     * Çevrilebilir alanları temel model üzerinden şeffafça sunar
     * ($post->title → aktif çevirinin title'ı).
     */
    public function getAttribute($key): mixed
    {
        if (in_array($key, $this->translatable, true)) {
            return $this->translation()?->{$key};
        }

        return parent::getAttribute($key);
    }

    /**
     * Find base records by a translated column in the given (or active) locale.
     * Verilen (veya aktif) locale'de çevrilmiş bir kolona göre temel kayıtları bulur.
     */
    public function scopeWhereTranslation(Builder $query, string $field, mixed $value, ?string $locale = null): Builder
    {
        $locale = $locale ?: Locale::get();

        return $query->whereHas('translations', function (Builder $q) use ($field, $value, $locale) {
            $q->where('language_slug', $locale)->where($field, $value);
        });
    }

    /**
     * Return the existing translation for a locale or a fresh (unsaved) one,
     * ready to be filled and saved — handy for admin forms.
     * Bir locale için var olan çeviriyi ya da doldurulup kaydedilmeye hazır yeni
     * (kaydedilmemiş) bir çeviri döndürür — admin formları için pratik.
     */
    public function translateOrNew(string $locale): Model
    {
        $row = $this->translations->firstWhere('language_slug', $locale);

        if ($row !== null) {
            return $row;
        }

        $model = $this->translationModel();
        /** @var Model $translation */
        $translation = new $model(['language_slug' => $locale]);

        return $translation;
    }
}
