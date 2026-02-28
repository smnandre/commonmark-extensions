<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\CodeBlockTitle;

final class InfoStringParser
{
    /** @return array{lang: string|null, attrs: array<string,string>} */
    public static function parse(string $info): array
    {
        $parts = preg_split('/\s+/', trim($info)) ?: [];
        $lang = $parts[0] ?? null;

        $attrs = [];
        foreach (array_slice($parts, 1) as $chunk) {
            if (preg_match('/^([a-zA-Z0-9_-]+)="([^"]*)"$/', $chunk, $m)) {
                $attrs[$m[1]] = $m[2];
            }
        }

        return ['lang' => $lang, 'attrs' => $attrs];
    }
}
