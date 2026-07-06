<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Console\Kernel;
use Tests\TestCase;

/**
 * Console Kernel isim üretimi testleri.
 * Console Kernel name-generation tests.
 *
 * studlyCase/snakeCase çıktıları dosya yoluna girer — path ayracı ve nokta gibi
 * karakterler süzülmeli ki "Blog/Post" veya "../../evil" gibi bir girdi hedef
 * dizinin dışına dosya yazamasın.
 * studlyCase/snakeCase output lands in file paths — path separators and dots must
 * be stripped so input like "Blog/Post" or "../../evil" cannot write outside the
 * target directory.
 */
class ConsoleKernelTest extends TestCase
{
    private function invoke(string $method, string $value): string
    {
        $kernel = new Kernel(BASE_PATH);
        $ref = new \ReflectionMethod(Kernel::class, $method);
        $ref->setAccessible(true);

        return $ref->invoke($kernel, $value);
    }

    // ── studlyCase ───────────────────────────────────────────────────────────

    public function test_studly_case_converts_kebab_and_snake(): void
    {
        $this->assertSame('UserProfile', $this->invoke('studlyCase', 'user-profile'));
        $this->assertSame('CreatePostsTable', $this->invoke('studlyCase', 'create_posts_table'));
    }

    public function test_studly_case_strips_path_separators(): void
    {
        $this->assertSame('BlogPost', $this->invoke('studlyCase', 'Blog/Post'));
        $this->assertSame('Evil', $this->invoke('studlyCase', '../../evil'));
        $this->assertSame('Evil', $this->invoke('studlyCase', '..\\..\\evil'));
    }

    public function test_studly_case_rejects_name_with_no_valid_characters(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->invoke('studlyCase', '../..');
    }

    // ── snakeCase ────────────────────────────────────────────────────────────

    public function test_snake_case_converts_studly(): void
    {
        $this->assertSame('create_posts_table', $this->invoke('snakeCase', 'CreatePostsTable'));
    }

    public function test_snake_case_strips_path_separators(): void
    {
        // Migration dosya adına girer — "../" gibi parçalar süzülmeli.
        // Lands in the migration file name — fragments like "../" must be stripped.
        $this->assertSame('weird_name', $this->invoke('snakeCase', '../weird name'));
        $this->assertStringNotContainsString('/', $this->invoke('snakeCase', 'a/b/c'));
    }
}
