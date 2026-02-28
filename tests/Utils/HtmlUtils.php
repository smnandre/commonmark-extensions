<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Utils;

final class HtmlUtils
{
    public static function normalize(string $html): string
    {
        $html = \preg_replace('/>\s+</', '><', $html) ?? $html;

        return \trim($html);
    }
}
