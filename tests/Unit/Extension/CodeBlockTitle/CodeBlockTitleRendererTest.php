<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Unit\Extension\CodeBlockTitle;

use Alto\CommonMark\Extension\CodeBlockTitle\CodeBlockTitleExtension;
use Alto\CommonMark\Extension\CodeBlockTitle\CodeBlockTitleRenderer;
use Alto\CommonMark\Tests\Utils\HtmlUtils;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CodeBlockTitleRenderer::class)]
final class CodeBlockTitleRendererTest extends TestCase
{
    private function convert(string $md): string
    {
        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new CodeBlockTitleExtension());
        $conv = new MarkdownConverter($env);

        return HtmlUtils::normalize((string) $conv->convert($md));
    }

    public function testWithoutTitleIsPlainCodeblock(): void
    {
        $html = $this->convert("```php\n<?php echo 1;\n```");
        self::assertStringContainsString('<pre><code class="language-php">', $html);
        self::assertStringNotContainsString('<figure', $html);
    }

    public function testWithTitleWrapsInFigureAndFigcaption(): void
    {
        $html = $this->convert("```php title=\"foo.php\"\n<?php echo 1;\n```");
        self::assertStringContainsString('<figure class="code-block has-title"', $html);
        self::assertStringContainsString('<figcaption class="code-title">foo.php</figcaption>', $html);
    }
}
