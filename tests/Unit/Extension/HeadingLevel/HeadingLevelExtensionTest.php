<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Unit\Extension\HeadingLevel;

use Alto\CommonMark\Extension\HeadingLevel\HeadingLevelExtension;
use Alto\CommonMark\Testing\CommonMarkExtensionTestCase;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HeadingLevelExtension::class)]
final class HeadingLevelExtensionTest extends CommonMarkExtensionTestCase
{
    protected function getExtension(): ExtensionInterface
    {
        return new HeadingLevelExtension([]);
    }

    public function testMapConfigurationTransformsMappedLevelsOnly(): void
    {
        $markdown = '# Title'."\n\n".'## Keep'."\n\n".'### Move'."\n\n".'Paragraph';
        $expected = '<h2>Title</h2>'."\n".'<h2>Keep</h2>'."\n".'<h4>Move</h4>'."\n".'<p>Paragraph</p>';
        $actual = $this->convertWithConfig(['map' => [1 => 2, 3 => 4]], $markdown);

        self::assertSame($expected, $actual);
    }

    public function testDownConfigurationShiftsAllHeadingLevels(): void
    {
        $markdown = '# One'."\n\n".'### Three';
        $expected = '<h3>One</h3>'."\n".'<h5>Three</h5>';
        $actual = $this->convertWithConfig(['down' => 2], $markdown);

        self::assertSame($expected, $actual);
    }

    public function testCallbackConfigurationCanSkipSpecificLevels(): void
    {
        $markdown = '# One'."\n\n".'## Two'."\n\n".'### Three';
        $expected = '<h2>One</h2>'."\n".'<h2>Two</h2>'."\n".'<h4>Three</h4>';
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new HeadingLevelExtension([
            'callback' => static fn (int $level): ?int => 2 === $level ? null : $level + 1,
        ]));
        $converter = new MarkdownConverter($environment);

        $actual = trim($converter->convert($markdown)->getContent());

        self::assertSame($expected, $actual);
    }

    public function testMapConfigurationTakesPriorityOverDownAndCallback(): void
    {
        $markdown = '# One'."\n\n".'## Two';
        $expected = '<h3>One</h3>'."\n".'<h2>Two</h2>';
        $actual = $this->convertWithConfig([
            'map' => [1 => 3],
            'down' => 1,
            'callback' => static fn (int $level): int => $level + 2,
        ], $markdown);

        self::assertSame($expected, $actual);
    }

    /**
     * @param array<string, mixed> $config
     */
    private function convertWithConfig(array $config, string $markdown): string
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new HeadingLevelExtension($config));
        $converter = new MarkdownConverter($environment);

        return trim($converter->convert($markdown)->getContent());
    }
}
