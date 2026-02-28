# Include Extension

## Summary
Includes markdown fragments with `@include` and parses them inline to support modular documentation.

## Status
| Item | Value |
|------|-------|
| Extension | `Include` |
| Namespace | `Alto\\CommonMark\\Extension\\Include\\IncludeExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\Include\IncludeExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new IncludeExtension(__DIR__ . '/docs'));
```

## Syntax
```markdown
@include "sections/intro.md"
@include "sections/features.md" {lines: 5-20}
```

## Configuration
- Constructor: `new IncludeExtension(string $basePath='.', int $maxDepth=10, array $allowedExtensions=['md','markdown'], int $maxFileSize=1048576)`.
- Validates extension, file size, and readable path constraints.

## Minimal Example
```markdown
# Guide

@include "parts/overview.md"
@include "parts/setup.md"
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
