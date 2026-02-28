<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Integration\Extension;

use Alto\CommonMark\Extension\Import\ImportExtension;
use Alto\CommonMark\Tests\Utils\HtmlUtils;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ImportExtension::class)]
final class ImportExtensionTest extends TestCase
{
    /**
     * @return array<string, array<string>>
     */
    public static function dataset(): array
    {
        $base = __DIR__.'/../../Fixtures/Extension/Import';

        return [
            'basic' => ["$base/basic.md", "$base/basic.html"],
            'code-options' => ["$base/code-options.md", "$base/code-options.html"],
            'invalid-syntax' => ["$base/invalid-syntax.md", "$base/invalid-syntax.html"],
            'single-line-range' => ["$base/single-line-range.md", "$base/single-line-range.html"],
            'duplicate-imports' => ["$base/duplicate-imports.md", "$base/duplicate-imports.html"],
            'indented-directive' => ["$base/indented-directive.md", "$base/indented-directive.html"],
        ];
    }

    #[DataProvider('dataset')]
    public function testFixtures(string $mdPath, string $expectedPath): void
    {
        $md = file_get_contents($mdPath);
        $expected = HtmlUtils::normalize(file_get_contents($expectedPath) ?: '');

        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new ImportExtension(__DIR__.'/../../Fixtures/Extension/Import'));
        $conv = new MarkdownConverter($env);

        $actual = HtmlUtils::normalize((string) $conv->convert($md));

        self::assertSame($expected, $actual, "Fixture failed for $mdPath");
    }

    public function testMissingFileShowsError(): void
    {
        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new ImportExtension(__DIR__.'/../../Fixtures/Extension/Import'));
        $conv = new MarkdownConverter($env);

        $actual = HtmlUtils::normalize((string) $conv->convert('@import "missing.md"'));

        self::assertStringContainsString('Import error: File not found:', $actual);
        self::assertStringContainsString('missing.md', $actual);
    }
}
