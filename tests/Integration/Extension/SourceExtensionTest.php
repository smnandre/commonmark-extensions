<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Integration\Extension;

use Alto\CommonMark\Extension\Source\SourceExtension;
use Alto\CommonMark\Tests\Utils\HtmlUtils;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SourceExtension::class)]
final class SourceExtensionTest extends TestCase
{
    /**
     * @return array<string, array<string>>
     */
    public static function dataset(): array
    {
        $base = __DIR__.'/../../Fixtures/Extension/Source';

        return [
            'basic' => ["$base/basic.md", "$base/basic.html"],
            'highlight' => ["$base/highlight.md", "$base/highlight.html"],
            'options-with-title' => ["$base/options-with-title.md", "$base/options-with-title.html"],
            'invalid-syntax' => ["$base/invalid-syntax.md", "$base/invalid-syntax.html"],
            'missing-file' => ["$base/missing-file.md", "$base/missing-file.html"],
        ];
    }

    #[DataProvider('dataset')]
    public function testFixtures(string $mdPath, string $expectedPath): void
    {
        $md = file_get_contents($mdPath);
        $expected = HtmlUtils::normalize(file_get_contents($expectedPath) ?: '');

        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new SourceExtension(__DIR__.'/../../Fixtures/Extension/Source'));
        $conv = new MarkdownConverter($env);

        $actual = HtmlUtils::normalize((string) $conv->convert($md));

        self::assertSame($expected, $actual, "Fixture failed for $mdPath");
    }
}
