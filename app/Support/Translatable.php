<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Contract fulfilled by the HasTranslations trait. Lets generic code (e.g. the
 * admin ResourceController) type-hint translatable models without binding to a
 * concrete class.
 * HasTranslations trait'inin karşıladığı sözleşme. Generic kodun (örn. admin
 * ResourceController) somut bir sınıfa bağlanmadan çevrilebilir modelleri
 * type-hint etmesini sağlar.
 */
interface Translatable
{
    public function translations(): HasMany;

    public function translation(?string $locale = null): ?Model;

    public function translationModel(): string;

    public function translationForeignKey(): string;
}
