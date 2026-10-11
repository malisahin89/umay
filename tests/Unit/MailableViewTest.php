<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\Mail\Mailable;
use Tests\TestCase;

/**
 * Mailable::renderView() path-containment (traversal / null byte) testleri.
 * Mailable::renderView() path-containment (traversal / null byte) tests.
 *
 * Bir template adı views/ dışına çıkamaz: '..' veya null byte içeren girdi
 * dosya sistemine dokunulmadan reddedilir ve boş gövde döner.
 *
 * A template name cannot escape views/: input containing '..' or a null byte is
 * rejected before touching the filesystem and yields an empty body.
 */
class MailableViewTest extends TestCase
{
    private string $viewFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->viewFile = (defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2))
            .'/views/__mailable_probe.php';
        file_put_contents($this->viewFile, 'Hello <?= $name ?>!');
    }

    protected function tearDown(): void
    {
        if (is_file($this->viewFile)) {
            unlink($this->viewFile);
        }

        parent::tearDown();
    }

    private function renderTemplate(string $template): string
    {
        $mailable = new class($template) extends Mailable
        {
            public function __construct(private string $template) {}

            public function build(): static
            {
                return $this->view($this->template, ['name' => 'World']);
            }
        };

        $mailable->build();

        return $mailable->getHtmlBody();
    }

    public function test_valid_view_renders_with_data(): void
    {
        $this->assertSame('Hello World!', $this->renderTemplate('__mailable_probe'));
    }

    public function test_parent_traversal_template_is_rejected(): void
    {
        $this->assertSame('', $this->renderTemplate('../.env'));
    }

    public function test_nested_traversal_template_is_rejected(): void
    {
        $this->assertSame('', $this->renderTemplate('emails/../../.env'));
    }

    public function test_null_byte_template_is_rejected(): void
    {
        $this->assertSame('', $this->renderTemplate("__mailable_probe\0"));
    }

    public function test_absolute_path_style_template_is_rejected(): void
    {
        // Leading slash is stripped, then it resolves inside views/ and misses.
        // Baştaki slash kırpılır, sonra views/ içinde çözülür ve bulunamaz.
        $this->assertSame('', $this->renderTemplate('/etc/passwd'));
    }

    public function test_missing_view_yields_empty_body(): void
    {
        $this->assertSame('', $this->renderTemplate('__definitely_missing_view__'));
    }
}
