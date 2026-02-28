# Alto CommonMark

Reusable `league/commonmark` extensions in a monorepo, installable as either:
- umbrella package: `alto/commonmark`, or
- standalone extension packages (one per extension).

## Installation

```bash
# Umbrella package (includes all extensions)
composer require alto/commonmark
```

```bash
# Standalone package example
composer require alto/commonmark-table-of-contents
```

`alto/commonmark` declares `replace` on standalone packages, so dependency resolution stays compatible.

## Extensions

| Extension | Description | Version |
|-----------|-------------|--------:|
| [CodeBlockTitle](src/Extension/CodeBlockTitle/README.md) | Render fenced code titles as figure captions. | `dev-main` |
| [ContentSlicer](src/Extension/ContentSlicer/README.md) | Wrap heading-scoped content in semantic sections. | `dev-main` |
| [HeadingLevel](src/Extension/HeadingLevel/README.md) | Transform heading levels by shift, map, or callback. | `dev-main` |
| [Import](src/Extension/Import/README.md) | Import external file content via `@import`. | `dev-main` |
| [Include](src/Extension/Include/README.md) | Include and parse markdown fragments inline. | `dev-main` |
| [LinkRewriter](src/Extension/LinkRewriter/README.md) | Rewrite link/image URLs post-parse. | `dev-main` |
| [Source](src/Extension/Source/README.md) | Render source files with ranges and highlighting. | `dev-main` |
| [TableOfContents](src/Extension/TableOfContents/README.md) | Generate TOC blocks from document headings. | `dev-main` |
| [Tabs](src/Extension/Tabs/README.md) | Render tabbed content from tab directives. | `dev-main` |

### CodeBlockTitle
Adds support for code-block titles in fenced info strings.

```php
use Alto\CommonMark\Extension\CodeBlockTitle\CodeBlockTitleExtension;
$environment->addExtension(new CodeBlockTitleExtension());
```

- Doc: [src/Extension/CodeBlockTitle/README.md](src/Extension/CodeBlockTitle/README.md)
- GitHub: [src/Extension/CodeBlockTitle](https://github.com/alto/commonmark/tree/main/src/Extension/CodeBlockTitle)
- Packagist: [alto/commonmark-code-block-title](https://packagist.org/packages/alto/commonmark-code-block-title)

### ContentSlicer
Wraps heading-based content segments in nested `<section>` blocks.

```php
use Alto\CommonMark\Extension\ContentSlicer\ContentSlicerExtension;
$environment->addExtension(new ContentSlicerExtension());
```

- Doc: [src/Extension/ContentSlicer/README.md](src/Extension/ContentSlicer/README.md)
- GitHub: [src/Extension/ContentSlicer](https://github.com/alto/commonmark/tree/main/src/Extension/ContentSlicer)
- Packagist: [alto/commonmark-content-slicer](https://packagist.org/packages/alto/commonmark-content-slicer)

### HeadingLevel
Adjusts heading levels during document processing.

```php
use Alto\CommonMark\Extension\HeadingLevel\HeadingLevelExtension;
$environment->addExtension(new HeadingLevelExtension(['down' => 1]));
```

- Doc: [src/Extension/HeadingLevel/README.md](src/Extension/HeadingLevel/README.md)
- GitHub: [src/Extension/HeadingLevel](https://github.com/alto/commonmark/tree/main/src/Extension/HeadingLevel)
- Packagist: [alto/commonmark-heading-level](https://packagist.org/packages/alto/commonmark-heading-level)

### Import
Imports file content with optional line range, language, and indentation options.

```php
use Alto\CommonMark\Extension\Import\ImportExtension;
$environment->addExtension(new ImportExtension(__DIR__ . '/docs'));
```

- Doc: [src/Extension/Import/README.md](src/Extension/Import/README.md)
- GitHub: [src/Extension/Import](https://github.com/alto/commonmark/tree/main/src/Extension/Import)
- Packagist: [alto/commonmark-import](https://packagist.org/packages/alto/commonmark-import)

### Include
Includes markdown files and parses them in the current document context.

```php
use Alto\CommonMark\Extension\Include\IncludeExtension;
$environment->addExtension(new IncludeExtension(__DIR__ . '/docs'));
```

- Doc: [src/Extension/Include/README.md](src/Extension/Include/README.md)
- GitHub: [src/Extension/Include](https://github.com/alto/commonmark/tree/main/src/Extension/Include)
- Packagist: [alto/commonmark-include](https://packagist.org/packages/alto/commonmark-include)

### LinkRewriter
Rewrites link and image URLs using configured rewrite rules.

```php
use Alto\CommonMark\Extension\LinkRewriter\LinkRewriterExtension;
$environment->addExtension(new LinkRewriterExtension(['base_uri' => 'https://docs.example.com']));
```

- Doc: [src/Extension/LinkRewriter/README.md](src/Extension/LinkRewriter/README.md)
- GitHub: [src/Extension/LinkRewriter](https://github.com/alto/commonmark/tree/main/src/Extension/LinkRewriter)
- Packagist: [alto/commonmark-link-rewriter](https://packagist.org/packages/alto/commonmark-link-rewriter)

### Source
Displays file content as source blocks with display options.

```php
use Alto\CommonMark\Extension\Source\SourceExtension;
$environment->addExtension(new SourceExtension(__DIR__));
```

- Doc: [src/Extension/Source/README.md](src/Extension/Source/README.md)
- GitHub: [src/Extension/Source](https://github.com/alto/commonmark/tree/main/src/Extension/Source)
- Packagist: [alto/commonmark-source](https://packagist.org/packages/alto/commonmark-source)

### TableOfContents
Generates table-of-contents output from `@toc` directives.

```php
use Alto\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
$environment->addExtension(new TableOfContentsExtension());
```

- Doc: [src/Extension/TableOfContents/README.md](src/Extension/TableOfContents/README.md)
- GitHub: [src/Extension/TableOfContents](https://github.com/alto/commonmark/tree/main/src/Extension/TableOfContents)
- Packagist: [alto/commonmark-table-of-contents](https://packagist.org/packages/alto/commonmark-table-of-contents)

### Tabs
Creates tabbed sections from tab-group markdown directives.

```php
use Alto\CommonMark\Extension\Tabs\TabsExtension;
$environment->addExtension(new TabsExtension());
```

- Doc: [src/Extension/Tabs/README.md](src/Extension/Tabs/README.md)
- GitHub: [src/Extension/Tabs](https://github.com/alto/commonmark/tree/main/src/Extension/Tabs)
- Packagist: [alto/commonmark-tabs](https://packagist.org/packages/alto/commonmark-tabs)

## Testing Layout

Current organization is intentionally kept as:
- `tests/Unit/Extension/...`
- `tests/Integration/Extension/...`

This keeps CI and discovery simple while extension coverage is expanded (notably for `Import`, `Include`, and `Tabs`).

## Development

```bash
composer install
vendor/bin/phpunit
```

## License

MIT. See [LICENSE](LICENSE).
