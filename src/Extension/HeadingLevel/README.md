# HeadingLevel Extension

## Summary
Transforms heading levels after parsing using either fixed shifting, explicit level mapping, or a custom callback.

## Status
| Item | Value |
|------|-------|
| Extension | `HeadingLevel` |
| Namespace | `Alto\\CommonMark\\Extension\\HeadingLevel\\HeadingLevelExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\HeadingLevel\HeadingLevelExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new HeadingLevelExtension(['down' => 1]));
```

## Syntax
No custom markdown directive is required; the extension processes standard headings after parse.

## Configuration
- `['down' => 1]`: shifts heading level up numerically (`h1 -> h2`, etc.).
- `['map' => [1 => 2, 2 => 3]]`: explicit per-level mapping.
- `['callback' => fn(int $level): int => ...]`: custom transformation logic.

## Minimal Example
```markdown
# Original H1
## Original H2
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
