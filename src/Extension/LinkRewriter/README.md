# LinkRewriter Extension

## Summary
Rewrites link and image URLs after parsing using base URI prepending, direct maps, regex rules, and/or custom callbacks.

## Status
| Item | Value |
|------|-------|
| Extension | `LinkRewriter` |
| Namespace | `Alto\\CommonMark\\Extension\\LinkRewriter\\LinkRewriterExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\LinkRewriter\LinkRewriterExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new LinkRewriterExtension([
    'base_uri' => 'https://docs.example.com',
]));
```

## Syntax
No custom markdown directive is required; standard links/images are rewritten during document processing.

## Configuration
- `base_uri`: prepends a base URL to relative links.
- `map`: exact URL mapping array.
- `pattern`: regex replacement pair (`pattern` + `replacement`).
- `callback`: custom callable for advanced rewriting.

## Minimal Example
```markdown
[Guide](/getting-started)
![Logo](/assets/logo.svg)
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
