<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Integration\Extension;

use Alto\CommonMark\Extension\Include\IncludeExtension;
use Alto\CommonMark\Tests\Utils\HtmlUtils;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(IncludeExtension::class)]
final class IncludeExtensionTest extends TestCase
{
    /**
     * @return array<string, array<string>>
     */
    public static function fixtureDataset(): array
    {
        $base = __DIR__.'/../../Fixtures/Extension/Include';

        return [
            'basic' => ["$base/basic.md", "$base/basic.html"],
            'line-range-context' => ["$base/line-range-context.md", "$base/line-range-context.html"],
            'quoted-line-range-context' => ["$base/quoted-line-range-context.md", "$base/quoted-line-range-context.html"],
            'indented-directive' => ["$base/indented-directive.md", "$base/indented-directive.html"],
        ];
    }

    /**
     * @return array<string, array<string>>
     */
    public static function errorDataset(): array
    {
        return [
            'invalid-syntax' => [
                '@include fragments/intro.md',
                '<div class="include-error">Include error: Invalid include syntax</div>',
            ],
            'missing-file' => [
                '@include "missing.md"',
                '<div class="include-error">Include error: File not found: missing.md</div>',
            ],
            'disallowed-extension' => [
                '@include "fragments/intro.txt"',
                '<div class="include-error">Include error: File type not allowed: .txt</div>',
            ],
        ];
    }

    #[DataProvider('fixtureDataset')]
    public function testFixtures(string $mdPath, string $expectedPath): void
    {
        $md = file_get_contents($mdPath);
        $expected = HtmlUtils::normalize(file_get_contents($expectedPath) ?: '');

        $conv = $this->createConverter();
        $actual = HtmlUtils::normalize((string) $conv->convert($md));

        self::assertSame($expected, $actual, "Fixture failed for $mdPath");
    }

    #[DataProvider('errorDataset')]
    public function testIncludeErrors(string $markdown, string $expected): void
    {
        $conv = $this->createConverter();
        $actual = HtmlUtils::normalize((string) $conv->convert($markdown));

        self::assertSame($expected, $actual);
    }

    public function testFileTooLargeError(): void
    {
        $conv = $this->createConverter(maxFileSize: 10);
        $actual = HtmlUtils::normalize((string) $conv->convert('@include "fragments/intro.md"'));

        self::assertSame(
            '<div class="include-error">Include error: File too large: 0MB (max: 0MB)</div>',
            $actual
        );
    }

    private function createConverter(int $maxFileSize = 1048576): MarkdownConverter
    {
        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new IncludeExtension(__DIR__.'/../../Fixtures/Extension/Include', maxFileSize: $maxFileSize));

        return new MarkdownConverter($env);
    }
}
