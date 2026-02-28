<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Integration\Extension;

use Alto\CommonMark\Extension\ContentSlicer\ContentSlicerExtension;
use Alto\CommonMark\Tests\Utils\HtmlUtils;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ContentSlicerExtension::class)]
final class ContentSlicerExtensionTest extends TestCase
{
    #[DataProvider('dataProviderForNesting')]
    public function testNesting(string $markdown, string $expected): void
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new ContentSlicerExtension());

        $converter = new MarkdownConverter($environment);
        $actual = HtmlUtils::normalize((string) $converter->convert($markdown));
        $expected = HtmlUtils::normalize($expected);

        self::assertSame($expected, $actual);
    }

    public function testWithMinLevelZero(): void
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new ContentSlicerExtension(minSectionLevel: 0));

        $converter = new MarkdownConverter($environment);
        $markdown = "# H1\n## H2";
        $actual = HtmlUtils::normalize((string) $converter->convert($markdown));
        // With minSectionLevel 0, even h1 should be wrapped
        $expected = HtmlUtils::normalize('<section><h1>H1</h1><section><h2>H2</h2></section></section>');

        self::assertSame($expected, $actual);
    }

    public function testWithMinLevelTwo(): void
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new ContentSlicerExtension(minSectionLevel: 2));

        $converter = new MarkdownConverter($environment);
        $markdown = "# H1\n## H2\n### H3";
        $actual = HtmlUtils::normalize((string) $converter->convert($markdown));
        // With minSectionLevel 2, only h3+ gets wrapped
        $expected = HtmlUtils::normalize('<h1>H1</h1><h2>H2</h2><section><h3>H3</h3></section>');

        self::assertSame($expected, $actual);
    }

    public static function dataProviderForNesting(): \Generator
    {
        yield 'h2 after h1' => [
            'markdown' => "# H1\n## H2",
            'expected' => '<h1>H1</h1><section><h2>H2</h2></section>',
        ];

        yield 'h3 after h2' => [
            'markdown' => "## H2\n### H3",
            'expected' => '<section><h2>H2</h2><section><h3>H3</h3></section></section>',
        ];

        yield 'h2 after h2' => [
            'markdown' => "## H2\n## H2",
            'expected' => '<section><h2>H2</h2></section><section><h2>H2</h2></section>',
        ];

        yield 'h1, h2, h3' => [
            'markdown' => "# H1\n## H2\n### H3",
            'expected' => '<h1>H1</h1><section><h2>H2</h2><section><h3>H3</h3></section></section>',
        ];

        yield 'complex nesting' => [
            'markdown' => "# H1\n## H2a\n### H3a\n## H2b\n### H3b\n#### H4",
            'expected' => '<h1>H1</h1><section><h2>H2a</h2><section><h3>H3a</h3></section></section><section><h2>H2b</h2><section><h3>H3b</h3><section><h4>H4</h4></section></section></section>',
        ];
    }
}
