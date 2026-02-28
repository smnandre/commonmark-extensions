# ContentSlicer Extension

## Summary
Wraps heading-scoped content into semantic `<section>` blocks according to heading hierarchy.

## Status
| Item | Value |
|------|-------|
| Extension | `ContentSlicer` |
| Namespace | `Alto\\CommonMark\\Extension\\ContentSlicer\\ContentSlicerExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\ContentSlicer\ContentSlicerExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new ContentSlicerExtension());
```

## Syntax
No custom markdown directive is required; the extension processes standard headings after parse.

## Configuration
- Constructor: `new ContentSlicerExtension(int $minSectionLevel = 1)`.
- `0` wraps from `h1`, `1` wraps from `h2`, `2` wraps from `h3`, etc.

## Minimal Example
```markdown
# Title
Intro
## Section
Body
### Subsection
Details
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
