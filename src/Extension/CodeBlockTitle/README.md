# CodeBlockTitle Extension

## Summary
Adds support for `title="..."` in fenced code info strings and renders titled code blocks inside a `<figure>` with `<figcaption>`.

## Status
| Item | Value |
|------|-------|
| Extension | `CodeBlockTitle` |
| Namespace | `Alto\\CommonMark\\Extension\\CodeBlockTitle\\CodeBlockTitleExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\CodeBlockTitle\CodeBlockTitleExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new CodeBlockTitleExtension());
```

## Syntax
````markdown
```php title="src/App.php"
<?php

echo "Hello";
```
````

## Configuration
- `new CodeBlockTitleExtension()` uses the default CommonMark fenced-code renderer.
- You can inject a custom base renderer via the constructor when needed.

## Minimal Example
````markdown
```javascript title="app.js"
console.log('Hello');
```
````

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
