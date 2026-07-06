<?php

declare(strict_types=1);

namespace Core;

use Illuminate\Database\Capsule\Manager as DB;

/**
 * Standalone validator — used by validate() helper and FormRequest.
 * Standalone validator — validate() helper ve FormRequest tarafından kullanılır.
 *
 * Supported rules / Desteklenen kurallar:
 *   required, sometimes, min:N, max:N, email, numeric, integer,
 *   confirmed, same:field, in:a,b,c, not_in:a,b, alpha, alpha_num,
 *   url, regex:/pattern/, digits:N, digits_between:min,max,
 *   date, before:date, after:date, unique:table,col,ignoreId, exists:table,col
 */
class Validator
{
    private array $errors = [];

    private function __construct(
        private readonly array $data,
        private readonly array $rules,
        private readonly array $messages = []
    ) {}

    // ── Factory ─────────────────────────────────────────────────────────────

    public static function make(array $data, array $rules, array $messages = []): static
    {
        $instance = new self($data, $rules, $messages);
        $instance->run();

        return $instance;
    }

    // ── Results ──────────────────────────────────────────────────────────────

    public function errors(): array
    {
        return $this->errors;
    }

    public function fails(): bool
    {
        return ! empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    // ── Core ─────────────────────────────────────────────────────────────────

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleString) {
            $ruleList = is_array($ruleString)
                ? $ruleString
                : explode('|', $ruleString);

            // sometimes — only validate if the field is present
            // sometimes — yalnızca alan gönderildiyse doğrula
            if (in_array('sometimes', $ruleList) && ! array_key_exists($field, $this->data)) {
                continue;
            }

            $value = $this->data[$field] ?? null;

            foreach ($ruleList as $rule) {
                if ($rule === 'sometimes') {
                    continue;
                }
                $this->applyRule($field, $value, $rule);
            }
        }
    }

    private function addError(string $field, string $ruleName, string $default): void
    {
        // Priority: "field.rule" → "field" → default
        // Öncelik: "field.rule" → "field" → default
        $msg = $this->messages["{$field}.{$ruleName}"]
            ?? $this->messages[$field]
            ?? $default;

        $this->errors[$field][] = $msg;
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        // Reject array values early — prevents PHP "Array to string conversion" warning
        // Dizi değerleri erken reddet — PHP "Array to string conversion" uyarısını önler
        if (is_array($value) && $rule !== 'sometimes') {
            $this->addError($field, 'type', "$field format is invalid. // $field formatı geçersiz.");

            return;
        }

        // $raw preserves the exact submitted value; $str is the trimmed view used by
        // most rules. Length and equality rules (min/max/confirmed/same) MUST use $raw
        // so a password like " secret " is measured/compared as-is — validating the
        // trimmed value while STORING the raw one would let confirmation mismatch slip
        // through and miscount length.
        // $raw gönderilen değeri birebir korur; $str çoğu kuralın kullandığı trim'li
        // görünümdür. Uzunluk ve eşitlik kuralları (min/max/confirmed/same) $raw
        // KULLANMALI; böylece " secret " gibi bir parola olduğu gibi ölçülür/karşılaştırılır
        // — trim'li değeri doğrulayıp ham değeri SAKLAMAK, eşleşme uyuşmazlığını kaçırır
        // ve uzunluğu yanlış sayar.
        $raw = (string) ($value ?? '');
        $str = trim($raw);
        [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

        match ($name) {
            'required' => $this->ruleRequired($field, $str),
            'min' => $this->ruleMin($field, $raw, (int) $param),
            'max' => $this->ruleMax($field, $raw, (int) $param),
            'email' => $this->ruleEmail($field, $str),
            'numeric' => $this->ruleNumeric($field, $str),
            'integer' => $this->ruleInteger($field, $str),
            'confirmed' => $this->ruleConfirmed($field, $raw),
            'same' => $this->ruleSame($field, $raw, (string) $param),
            'in' => $this->ruleIn($field, $str, (string) $param),
            'not_in' => $this->ruleNotIn($field, $str, (string) $param),
            'alpha' => $this->ruleAlpha($field, $str),
            'alpha_num',
            'alphanumeric' => $this->ruleAlphaNum($field, $str, $name),
            'url' => $this->ruleUrl($field, $str),
            'regex' => $this->ruleRegex($field, $str, (string) $param),
            'digits' => $this->ruleDigits($field, $str, (int) $param),
            'digits_between' => $this->ruleDigitsBetween($field, $str, (string) $param),
            'date' => $this->ruleDate($field, $str),
            'before' => $this->ruleBefore($field, $str, (string) $param),
            'after' => $this->ruleAfter($field, $str, (string) $param),
            'unique' => $this->ruleUnique($field, $str, (string) $param),
            'exists' => $this->ruleExists($field, $str, (string) $param),
            default => null,
        };
    }

    // ── Rules ─────────────────────────────────────────────────────────────────

    private function ruleRequired(string $f, string $v): void
    {
        if ($v === '') {
            $this->addError($f, 'required', "$f field is required. // $f alanı zorunludur.");
        }
    }

    private function ruleMin(string $f, string $v, int $n): void
    {
        if ($v !== '' && mb_strlen($v) < $n) {
            $this->addError($f, 'min', "$f must be at least $n characters. // $f en az $n karakter olmalı.");
        }
    }

    private function ruleMax(string $f, string $v, int $n): void
    {
        if ($v !== '' && mb_strlen($v) > $n) {
            $this->addError($f, 'max', "$f may not be greater than $n characters. // $f en fazla $n karakter olabilir.");
        }
    }

    private function ruleEmail(string $f, string $v): void
    {
        if ($v !== '' && ! filter_var($v, FILTER_VALIDATE_EMAIL)) {
            $this->addError($f, 'email', "$f must be a valid email address. // $f geçerli bir e-posta olmalı.");
        }
    }

    private function ruleNumeric(string $f, string $v): void
    {
        if ($v !== '' && ! is_numeric($v)) {
            $this->addError($f, 'numeric', "$f must be a number. // $f sadece sayı içermeli.");
        }
    }

    private function ruleInteger(string $f, string $v): void
    {
        if ($v !== '' && filter_var($v, FILTER_VALIDATE_INT) === false) {
            $this->addError($f, 'integer', "$f must be an integer. // $f geçerli bir tam sayı olmalı.");
        }
    }

    private function ruleConfirmed(string $f, string $v): void
    {
        // Compare RAW values (no trim) — the confirmation must match the stored value
        // byte-for-byte, otherwise " secret " and "secret" would falsely confirm.
        // Ham değerleri karşılaştır (trim yok) — onay, saklanan değerle birebir
        // eşleşmeli; aksi halde " secret " ile "secret" yanlışlıkla onaylanırdı.
        $confirm = (string) ($this->data["{$f}_confirmation"] ?? '');
        if ($v !== $confirm) {
            $this->addError($f, 'confirmed', "$f confirmation does not match. // $f ile {$f}_confirmation eşleşmiyor.");
        }
    }

    private function ruleSame(string $f, string $v, string $other): void
    {
        // Compare RAW values (no trim) — see ruleConfirmed().
        // Ham değerleri karşılaştır (trim yok) — bkz. ruleConfirmed().
        $otherVal = (string) ($this->data[$other] ?? '');
        if ($v !== $otherVal) {
            $this->addError($f, 'same', "$f and $other must match. // $f ile $other eşleşmiyor.");
        }
    }

    private function ruleIn(string $f, string $v, string $param): void
    {
        if ($v !== '' && ! in_array($v, explode(',', $param), true)) {
            $this->addError($f, 'in', "The selected $f is invalid. // $f geçersiz bir değer içeriyor.");
        }
    }

    private function ruleNotIn(string $f, string $v, string $param): void
    {
        // Boş değeri atla — ruleIn ile tutarlı; boşluk zorunluluğu 'required' kuralının işi.
        // Skip empty values — consistent with ruleIn; requiring presence is 'required''s job.
        if ($v !== '' && in_array($v, explode(',', $param), true)) {
            $this->addError($f, 'not_in', "The selected $f is invalid. // $f geçersiz bir değer içeriyor.");
        }
    }

    private function ruleAlpha(string $f, string $v): void
    {
        if ($v !== '' && ! preg_match('/^[a-zA-ZÇçĞğİıÖöŞşÜü\s]+$/u', $v)) {
            $this->addError($f, 'alpha', "$f may only contain letters. // $f sadece harf içermelidir.");
        }
    }

    private function ruleAlphaNum(string $f, string $v, string $ruleName): void
    {
        if ($v !== '' && ! preg_match('/^[a-zA-Z0-9ÇçĞğİıÖöŞşÜü]+$/u', $v)) {
            $this->addError($f, $ruleName, "$f may only contain letters and numbers. // $f sadece harf ve rakam içermelidir.");
        }
    }

    private function ruleUrl(string $f, string $v): void
    {
        if ($v !== '' && ! filter_var($v, FILTER_VALIDATE_URL)) {
            $this->addError($f, 'url', "$f format is invalid. // $f geçerli bir URL olmalı.");
        }
    }

    private function ruleRegex(string $f, string $v, string $pattern): void
    {
        if ($v === '') {
            return;
        }

        // Bozuk pattern (yapılandırma hatası) PHP warning üretir — custom error
        // handler'da exception'a dönüşebilirdi. before/after ile tutarlı: geçersiz
        // kural parametresi kuralı atlar, kullanıcıyı cezalandırmaz.
        // A broken pattern (a config mistake) emits a PHP warning — which a custom
        // error handler could turn into an exception. Consistent with before/after:
        // an invalid rule parameter skips the rule instead of punishing the user.
        $result = @preg_match($pattern, $v);
        if ($result === false) {
            return;
        }

        if ($result !== 1) {
            $this->addError($f, 'regex', "$f format is invalid. // $f geçersiz bir format içeriyor.");
        }
    }

    private function ruleDigits(string $f, string $v, int $n): void
    {
        if ($v !== '' && (! ctype_digit($v) || mb_strlen($v) !== $n)) {
            $this->addError($f, 'digits', "$f must be $n digits. // $f tam olarak $n rakam içermelidir.");
        }
    }

    private function ruleDigitsBetween(string $f, string $v, string $param): void
    {
        // Eksik max (örn. "digits_between:5") sınırsız sayılır — 0'a düşmek her
        // değeri reddederdi. Missing max (e.g. "digits_between:5") means unbounded —
        // defaulting to 0 would reject every value.
        [$min, $max] = array_pad(array_map('intval', explode(',', $param)), 2, PHP_INT_MAX);
        $len = mb_strlen($v);
        if ($v !== '' && (! ctype_digit($v) || $len < $min || $len > $max)) {
            $this->addError($f, 'digits_between', "$f must be between $min and $max digits. // $f $min ile $max rakam arasında olmalıdır.");
        }
    }

    private function ruleDate(string $f, string $v): void
    {
        if ($v !== '' && strtotime($v) === false) {
            $this->addError($f, 'date', "$f is not a valid date. // $f geçerli bir tarih olmalı.");
        }
    }

    private function ruleBefore(string $f, string $v, string $date): void
    {
        if ($v === '') {
            return;
        }

        // strtotime() false döner (geçersiz tarih) → false, 0 gibi karşılaştırılıp
        // yanıltıcı sonuç verirdi. Geçersiz değer kuralı geçemez; geçersiz kural
        // parametresi (yapılandırma hatası) ise kuralı atlar.
        // strtotime() returns false on an invalid date → false compared like 0 and
        // gave misleading results. An invalid value fails the rule; an invalid rule
        // parameter (a config mistake) skips the rule.
        $value = strtotime($v);
        $limit = strtotime($date);

        if ($value === false || ($limit !== false && $value >= $limit)) {
            $this->addError($f, 'before', "$f must be a date before $date. // $f $date tarihinden önce olmalı.");
        }
    }

    private function ruleAfter(string $f, string $v, string $date): void
    {
        if ($v === '') {
            return;
        }

        // Bkz. ruleBefore() — aynı strtotime(false) koruması.
        // See ruleBefore() — same strtotime(false) guard.
        $value = strtotime($v);
        $limit = strtotime($date);

        if ($value === false || ($limit !== false && $value <= $limit)) {
            $this->addError($f, 'after', "$f must be a date after $date. // $f $date tarihinden sonra olmalı.");
        }
    }

    private function ruleUnique(string $f, string $v, string $param): void
    {
        // unique:table,column,ignoreId
        [$table, $column, $ignoreId] = array_pad(explode(',', $param), 3, null);

        if ($v === '' || ! $table || ! $column) {
            return;
        }

        // Sanitize table and column names (only alphanumeric and underscore)
        // Tablo ve kolon adlarını sanitize et (sadece alfanumerik ve alt çizgi)
        if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)
            || ! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            return;
        }

        $q = DB::table($table)->where($column, $v);
        // ignoreId string/UUID primary key'leri de destekler — değer query builder
        // tarafından bind edilir (SQL injection riski yok); numeric zorunluluğu
        // UUID'li tablolarda ignore'u sessizce devre dışı bırakıyordu.
        // ignoreId also supports string/UUID primary keys — the value is bound by the
        // query builder (no SQL injection risk); requiring numeric silently disabled
        // ignore on UUID-keyed tables.
        if ($ignoreId !== null && $ignoreId !== '') {
            $q->where('id', '!=', is_numeric($ignoreId) ? (int) $ignoreId : $ignoreId);
        }

        if ($q->count() > 0) {
            $this->addError($f, 'unique', "$f has already been taken. // $f zaten kullanılmış.");
        }
    }

    private function ruleExists(string $f, string $v, string $param): void
    {
        // exists:table,column
        [$table, $column] = array_pad(explode(',', $param), 2, $f);

        if ($v === '' || ! $table) {
            return;
        }

        // Sanitize table and column names
        // Tablo ve kolon adlarını sanitize et
        if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table)
            || ! preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            return;
        }

        if (DB::table($table)->where($column, $v)->count() === 0) {
            $this->addError($f, 'exists', "The selected $f is invalid. // $f geçerli bir değer içermiyor.");
        }
    }
}
