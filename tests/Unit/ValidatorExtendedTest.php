<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Validator;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

/**
 * Validator sistemi genişletilmiş testler.
 *
 * Mevcut ValidatorTest'e ek olarak yeni kurallar:
 * sometimes, confirmed, same, in, not_in, alpha, alpha_num,
 * url, regex, digits, digits_between, date, before, after
 * ve array input koruması test edilir.
 */
class ValidatorExtendedTest extends TestCase
{
    // ── sometimes kuralı ────────────────────────────────────────────────────

    public function test_sometimes_skips_missing_field(): void
    {
        $v = Validator::make(
            [], // alan gönderilmemiş
            ['email' => 'sometimes|email']
        );
        $this->assertTrue($v->passes());
    }

    public function test_sometimes_validates_present_field(): void
    {
        $v = Validator::make(
            ['email' => 'gecersiz'],
            ['email' => 'sometimes|email']
        );
        $this->assertTrue($v->fails());
    }

    // ── confirmed kuralı ────────────────────────────────────────────────────

    public function test_confirmed_passes_when_matches(): void
    {
        $v = Validator::make(
            ['password' => 'secret123', 'password_confirmation' => 'secret123'],
            ['password' => 'confirmed']
        );
        $this->assertTrue($v->passes());
    }

    public function test_confirmed_fails_when_mismatch(): void
    {
        $v = Validator::make(
            ['password' => 'secret123', 'password_confirmation' => 'different'],
            ['password' => 'confirmed']
        );
        $this->assertTrue($v->fails());
    }

    // ── same kuralı ─────────────────────────────────────────────────────────

    public function test_same_passes_when_fields_match(): void
    {
        $v = Validator::make(
            ['a' => 'hello', 'b' => 'hello'],
            ['a' => 'same:b']
        );
        $this->assertTrue($v->passes());
    }

    public function test_same_fails_when_fields_differ(): void
    {
        $v = Validator::make(
            ['a' => 'hello', 'b' => 'world'],
            ['a' => 'same:b']
        );
        $this->assertTrue($v->fails());
    }

    // ── in kuralı ───────────────────────────────────────────────────────────

    public function test_in_passes_for_valid_value(): void
    {
        $v = Validator::make(
            ['role' => 'admin'],
            ['role' => 'in:admin,member,editor']
        );
        $this->assertTrue($v->passes());
    }

    public function test_in_fails_for_invalid_value(): void
    {
        $v = Validator::make(
            ['role' => 'superadmin'],
            ['role' => 'in:admin,member,editor']
        );
        $this->assertTrue($v->fails());
    }

    // ── not_in kuralı ───────────────────────────────────────────────────────

    public function test_not_in_passes_for_allowed_value(): void
    {
        $v = Validator::make(
            ['status' => 'active'],
            ['status' => 'not_in:banned,deleted']
        );
        $this->assertTrue($v->passes());
    }

    public function test_not_in_fails_for_forbidden_value(): void
    {
        $v = Validator::make(
            ['status' => 'banned'],
            ['status' => 'not_in:banned,deleted']
        );
        $this->assertTrue($v->fails());
    }

    // ── alpha kuralı ────────────────────────────────────────────────────────

    public function test_alpha_passes_for_letters(): void
    {
        $v = Validator::make(['name' => 'Ahmet Şahin'], ['name' => 'alpha']);
        $this->assertTrue($v->passes());
    }

    public function test_alpha_fails_for_numbers(): void
    {
        $v = Validator::make(['name' => 'Ahmet123'], ['name' => 'alpha']);
        $this->assertTrue($v->fails());
    }

    // ── alpha_num kuralı ────────────────────────────────────────────────────

    public function test_alpha_num_passes_for_letters_and_numbers(): void
    {
        $v = Validator::make(['username' => 'user123'], ['username' => 'alpha_num']);
        $this->assertTrue($v->passes());
    }

    public function test_alpha_num_fails_for_special_chars(): void
    {
        $v = Validator::make(['username' => 'user@123'], ['username' => 'alpha_num']);
        $this->assertTrue($v->fails());
    }

    // ── url kuralı ──────────────────────────────────────────────────────────

    public function test_url_passes_for_valid_url(): void
    {
        $v = Validator::make(['site' => 'https://malisahin.com'], ['site' => 'url']);
        $this->assertTrue($v->passes());
    }

    public function test_url_fails_for_invalid_url(): void
    {
        $v = Validator::make(['site' => 'not-a-url'], ['site' => 'url']);
        $this->assertTrue($v->fails());
    }

    // ── regex kuralı ────────────────────────────────────────────────────────

    public function test_regex_passes_for_matching_pattern(): void
    {
        $v = Validator::make(['code' => 'ABC-123'], ['code' => 'regex:/^[A-Z]{3}-\\d{3}$/']);
        $this->assertTrue($v->passes());
    }

    public function test_regex_fails_for_non_matching_pattern(): void
    {
        $v = Validator::make(['code' => 'abc123'], ['code' => 'regex:/^[A-Z]{3}-\\d{3}$/']);
        $this->assertTrue($v->fails());
    }

    // ── digits kuralı ───────────────────────────────────────────────────────

    public function test_digits_passes_for_exact_count(): void
    {
        $v = Validator::make(['pin' => '1234'], ['pin' => 'digits:4']);
        $this->assertTrue($v->passes());
    }

    public function test_digits_fails_for_wrong_count(): void
    {
        $v = Validator::make(['pin' => '12345'], ['pin' => 'digits:4']);
        $this->assertTrue($v->fails());
    }

    // ── digits_between kuralı ───────────────────────────────────────────────

    public function test_digits_between_passes_within_range(): void
    {
        $v = Validator::make(['code' => '12345'], ['code' => 'digits_between:4,6']);
        $this->assertTrue($v->passes());
    }

    public function test_digits_between_fails_outside_range(): void
    {
        $v = Validator::make(['code' => '12'], ['code' => 'digits_between:4,6']);
        $this->assertTrue($v->fails());
    }

    // ── date kuralı ─────────────────────────────────────────────────────────

    public function test_date_passes_for_valid_date(): void
    {
        $v = Validator::make(['birthday' => '2000-01-15'], ['birthday' => 'date']);
        $this->assertTrue($v->passes());
    }

    public function test_date_fails_for_invalid_date(): void
    {
        $v = Validator::make(['birthday' => 'not-a-date'], ['birthday' => 'date']);
        $this->assertTrue($v->fails());
    }

    // ── before kuralı ───────────────────────────────────────────────────────

    public function test_before_passes_for_earlier_date(): void
    {
        $v = Validator::make(['start' => '2020-01-01'], ['start' => 'before:2025-01-01']);
        $this->assertTrue($v->passes());
    }

    public function test_before_fails_for_later_date(): void
    {
        $v = Validator::make(['start' => '2030-01-01'], ['start' => 'before:2025-01-01']);
        $this->assertTrue($v->fails());
    }

    // ── after kuralı ────────────────────────────────────────────────────────

    public function test_after_passes_for_later_date(): void
    {
        $v = Validator::make(['end' => '2030-01-01'], ['end' => 'after:2020-01-01']);
        $this->assertTrue($v->passes());
    }

    public function test_after_fails_for_earlier_date(): void
    {
        $v = Validator::make(['end' => '2010-01-01'], ['end' => 'after:2020-01-01']);
        $this->assertTrue($v->fails());
    }

    // ── before/after: geçersiz tarih değeri kuraldan GEÇEMEZ ─────────────────
    // ── before/after: an invalid date value must NOT pass the rule ───────────

    public function test_before_fails_for_invalid_date_value(): void
    {
        // Eski davranışta strtotime() false → 0 gibi karşılaştırılıp yanıltıcı
        // sonuç veriyordu. Geçersiz değer artık her zaman hata üretir.
        // Previously strtotime() false compared like 0 and gave misleading results.
        // An invalid value now always produces an error.
        $v = Validator::make(['start' => 'not-a-date'], ['start' => 'before:2025-01-01']);
        $this->assertTrue($v->fails());
    }

    public function test_after_fails_for_invalid_date_value(): void
    {
        $v = Validator::make(['end' => 'not-a-date'], ['end' => 'after:2020-01-01']);
        $this->assertTrue($v->fails());
    }

    public function test_before_skips_when_rule_parameter_invalid(): void
    {
        // Kural parametresi (yapılandırma hatası) geçersizse kural atlanır —
        // kullanıcı verisi yüzünden değil, geliştirici hatası yüzünden patlamamalı.
        // An invalid rule parameter (a config mistake) skips the rule — it must not
        // blame user input for a developer error.
        $v = Validator::make(['start' => '2020-01-01'], ['start' => 'before:not-a-date']);
        $this->assertTrue($v->passes());
    }

    // ── Array input koruması ────────────────────────────────────────────────

    public function test_array_value_rejected_with_type_error(): void
    {
        $v = Validator::make(
            ['name' => ['injected', 'array']],
            ['name' => 'required|min:2']
        );
        $this->assertTrue($v->fails());
    }

    // ── Özel hata mesajları ─────────────────────────────────────────────────

    public function test_custom_error_messages(): void
    {
        $v = Validator::make(
            ['email' => ''],
            ['email' => 'required'],
            ['email.required' => 'E-posta alanı boş bırakılamaz!']
        );

        $this->assertTrue($v->fails());
        $errors = $v->errors();
        $this->assertSame('E-posta alanı boş bırakılamaz!', $errors['email'][0]);
    }

    // ── Boş değer — required dışında skip ───────────────────────────────────

    public function test_empty_value_skips_non_required_rules(): void
    {
        $v = Validator::make(
            ['email' => ''],
            ['email' => 'email'] // required yok, boş olabilir
        );
        $this->assertTrue($v->passes());
    }

    // ── Birden fazla kural birlikte ─────────────────────────────────────────

    public function test_multiple_rules_combined(): void
    {
        $v = Validator::make(
            ['password' => 'ab'], // min:6'ya uymuyor
            ['password' => 'required|min:6|max:50']
        );
        $this->assertTrue($v->fails());

        $errors = $v->errors();
        $this->assertArrayHasKey('password', $errors);
    }

    // ── errors() ve fails() / passes() ──────────────────────────────────────

    public function test_passes_returns_true_for_valid_data(): void
    {
        $v = Validator::make(
            ['name' => 'Umay', 'email' => 'test@example.com'],
            ['name' => 'required|min:2', 'email' => 'required|email']
        );

        $this->assertTrue($v->passes());
        $this->assertFalse($v->fails());
        $this->assertEmpty($v->errors());
    }

    // ── Array kuralları ─────────────────────────────────────────────────────

    public function test_rules_as_array_instead_of_string(): void
    {
        $v = Validator::make(
            ['name' => 'U'],
            ['name' => ['required', 'min:3']]
        );

        $this->assertTrue($v->fails());
    }

    // ── unique:table (kolonsuz) alan adını kullanmalı ───────────────────────

    public function test_unique_without_column_checks_field_name(): void
    {
        $schema = DB::schema();
        if (! $schema->hasTable('validator_emails')) {
            $schema->create('validator_emails', function (Blueprint $t) {
                $t->increments('id');
                $t->string('email')->nullable();
                $t->timestamps();
            });
        }
        DB::table('validator_emails')->delete();
        DB::table('validator_emails')->insert([
            'email' => 'taken@example.com',
            'created_at' => '2026-01-01 00:00:00',
            'updated_at' => '2026-01-01 00:00:00',
        ]);

        $dupe = Validator::make(
            ['email' => 'taken@example.com'],
            ['email' => 'required|email|unique:validator_emails']
        );
        $this->assertTrue($dupe->fails());

        $fresh = Validator::make(
            ['email' => 'new@example.com'],
            ['email' => 'required|email|unique:validator_emails']
        );
        $this->assertTrue($fresh->passes());
    }

    // ── numeric/integer min/max değer karşılaştırması ───────────────────────

    public function test_numeric_min_max_compare_values_not_length(): void
    {
        $v = Validator::make(['age' => '100'], ['age' => 'integer|min:18']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['age' => '17'], ['age' => 'integer|min:18']);
        $this->assertTrue($v->fails());

        $v = Validator::make(['price' => '5000'], ['price' => 'numeric|min:1000|max:9999']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['price' => '12000'], ['price' => 'numeric|max:9999']);
        $this->assertTrue($v->fails());
    }

    public function test_string_min_max_still_length_based(): void
    {
        $v = Validator::make(['pass' => 'abcdefgh'], ['pass' => 'required|min:8']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['name' => 'x'], ['name' => 'required|min:2']);
        $this->assertTrue($v->fails());
    }

    // ── Desteklenmeyen kural fail-closed ────────────────────────────────────

    public function test_unsupported_rule_throws_logic_exception(): void
    {
        $this->expectException(\LogicException::class);

        Validator::make(['email' => 'a@b.com'], ['email' => 'uniqe:users']);
    }

    // ── nullable ve array kuralları ─────────────────────────────────────────

    public function test_nullable_skips_empty_value(): void
    {
        $v = Validator::make(['email' => ''], ['email' => 'nullable|email']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['email' => 'not-an-email'], ['email' => 'nullable|email']);
        $this->assertTrue($v->fails());
    }

    public function test_required_array_emptiness_check(): void
    {
        $v = Validator::make(['tags' => []], ['tags' => 'required|array']);
        $this->assertTrue($v->fails());

        $v = Validator::make(['tags' => ['a', 'b']], ['tags' => 'required|array']);
        $this->assertTrue($v->passes());

        $v = Validator::make(['tags' => 'nope'], ['tags' => 'array']);
        $this->assertTrue($v->fails());
    }
}
