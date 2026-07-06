<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Validator;
use Tests\TestCase;

class ValidatorTest extends TestCase
{
    public function test_required_rule_fails_on_empty(): void
    {
        $v = Validator::make(['name' => ''], ['name' => 'required']);
        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('name', $v->errors());
    }

    public function test_required_rule_passes_with_value(): void
    {
        $v = Validator::make(['name' => 'Ali'], ['name' => 'required']);
        $this->assertFalse($v->fails());
        $this->assertEmpty($v->errors());
    }

    public function test_email_rule_fails_on_invalid(): void
    {
        $v = Validator::make(['email' => 'not-an-email'], ['email' => 'email']);
        $this->assertTrue($v->fails());
    }

    public function test_email_rule_passes_on_valid(): void
    {
        $v = Validator::make(['email' => 'user@example.com'], ['email' => 'email']);
        $this->assertFalse($v->fails());
    }

    public function test_min_rule_fails_when_too_short(): void
    {
        $v = Validator::make(['pass' => 'abc'], ['pass' => 'min:8']);
        $this->assertTrue($v->fails());
    }

    public function test_min_rule_passes_when_long_enough(): void
    {
        $v = Validator::make(['pass' => 'abcdefgh'], ['pass' => 'min:8']);
        $this->assertFalse($v->fails());
    }

    public function test_confirmed_rule_fails_when_mismatch(): void
    {
        $v = Validator::make(
            ['password' => 'secret123', 'password_confirmation' => 'different'],
            ['password' => 'confirmed']
        );
        $this->assertTrue($v->fails());
    }

    public function test_confirmed_rule_passes_when_match(): void
    {
        $v = Validator::make(
            ['password' => 'secret123', 'password_confirmation' => 'secret123'],
            ['password' => 'confirmed']
        );
        $this->assertFalse($v->fails());
    }

    public function test_custom_error_messages(): void
    {
        $v = Validator::make(
            ['email' => ''],
            ['email' => 'required'],
            ['email.required' => 'E-posta zorunludur.']
        );
        $errors = $v->errors();
        $this->assertEquals('E-posta zorunludur.', $errors['email'][0]);
    }

    public function test_multiple_rules_collect_all_errors(): void
    {
        $v = Validator::make(
            ['email' => '', 'name' => ''],
            ['email' => 'required|email', 'name' => 'required|min:3']
        );
        $errors = $v->errors();
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('name', $errors);
    }

    public function test_in_rule(): void
    {
        $v = Validator::make(['role' => 'superuser'], ['role' => 'in:admin,member']);
        $this->assertTrue($v->fails());

        $v2 = Validator::make(['role' => 'admin'], ['role' => 'in:admin,member']);
        $this->assertFalse($v2->fails());
    }

    public function test_numeric_rule(): void
    {
        $v = Validator::make(['age' => 'abc'], ['age' => 'numeric']);
        $this->assertTrue($v->fails());

        $v2 = Validator::make(['age' => '25'], ['age' => 'numeric']);
        $this->assertFalse($v2->fails());
    }
}
