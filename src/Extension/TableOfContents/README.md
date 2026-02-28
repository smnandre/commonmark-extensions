# TableOfContents Extension

## Summary
Generates a heading-based table of contents from `@toc` directives with filtering and output-style options.

## Status
| Item | Value |
|------|-------|
| Extension | `TableOfContents` |
| Namespace | `Alto\\CommonMark\\Extension\\TableOfContents\\TableOfContentsExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new TableOfContentsExtension());
```

## Syntax
```markdown
@toc
@toc {min: 2, max: 3, title: "Contents", style: ordered}
```

## Configuration
- `min_level` / `max_level`: heading level bounds.
- `style`: `bullet` or `ordered`.
- `title`, `class`, `id`, `marker`: output/customization options.

## Minimal Example
```markdown
# Guide
@toc {min: 2}

## Intro
## Setup
### Advanced Setup
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
