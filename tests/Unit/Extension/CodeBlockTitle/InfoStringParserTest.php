<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Unit\Extension\CodeBlockTitle;

use Alto\CommonMark\Extension\CodeBlockTitle\InfoStringParser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InfoStringParser::class)]
final class InfoStringParserTest extends TestCase
{
    public function testParseWithTitle(): void
    {
        $res = InfoStringParser::parse('python title="test.py"');
        self::assertSame('python', $res['lang']);
        self::assertSame('test.py', $res['attrs']['title'] ?? null);
    }

    public function testParseWithoutAttrs(): void
    {
        $res = InfoStringParser::parse('php');
        self::assertSame('php', $res['lang']);
        self::assertArrayNotHasKey('title', $res['attrs']);
    }
}
