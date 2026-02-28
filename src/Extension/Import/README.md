# Import Extension

## Summary
Imports external file content using `@import` directives with optional line-range selection, indentation, and language hinting.

## Status
| Item | Value |
|------|-------|
| Extension | `Import` |
| Namespace | `Alto\\CommonMark\\Extension\\Import\\ImportExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\Import\ImportExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new ImportExtension(__DIR__ . '/docs'));
```

## Syntax
```markdown
@import "path/to/file.md"
@import "src/Handler.php" {lines: 10-40, lang: php, indent: 2}
```

## Configuration
- Constructor: `new ImportExtension(string $basePath = '.', int $maxDepth = 10)`.
- Guards against circular imports and excessive nesting depth.

## Minimal Example
```markdown
# API

@import "snippets/auth.md"
@import "src/Auth.php" {lines: 1-30, lang: php}
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
