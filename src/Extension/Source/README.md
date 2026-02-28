# Source Extension

## Summary
Renders source files via `@source` directives with syntax class detection, ranges, line numbers, and line highlighting.

## Status
| Item | Value |
|------|-------|
| Extension | `Source` |
| Namespace | `Alto\\CommonMark\\Extension\\Source\\SourceExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\Source\SourceExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new SourceExtension(__DIR__));
```

## Syntax
```markdown
@source "src/Service.php"
@source "src/Service.php" {lines: 10-30, numbers: true, highlight: "14,18-20"}
```

## Configuration
- Constructor: `new SourceExtension(string $basePath='.', array $allowedExtensions=[], bool $escapeHtml=true, int $maxFileSize=1048576)`.
- Supports extension allowlists and max-size protections.

## Minimal Example
```markdown
# Service excerpt

@source "src/Service.php" {lines: 1-25, numbers: true}
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
