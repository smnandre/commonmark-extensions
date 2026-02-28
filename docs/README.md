# Alto CommonMark Extensions Documentation

This directory contains comprehensive documentation for each Alto CommonMark extension.

## Quick Start

If you're new to Alto CommonMark, start with the [main README](../README.md).

## Extension Guides

### [CodeBlockTitle](CodeBlockTitle.md)
**Render code block titles as figure captions**

Parses `title="..."` attributes from fenced code block info strings and renders them as `<figcaption>` elements inside `<figure>` tags.

- **Use Case**: Display filenames or descriptions above code blocks
- **Configuration**: None required (supports optional custom renderer)
- **Complexity**: Simple
- **Best For**: Technical documentation, tutorials

Example:
```markdown
```javascript title="app.js"
console.log("Hello");
```
```

---

### [ContentSlicer](ContentSlicer.md)
**Automatically create semantic sections**

Divides a document into nested `<section>` elements based on heading hierarchy, creating semantic document structure.

- **Use Case**: Organize content, improve accessibility
- **Configuration**: None required
- **Complexity**: Simple (Event-based)
- **Best For**: Long documents, knowledge bases, blogs

Example:
```markdown
# Main Topic
Content here.
## Subtopic
More content.
```

Renders nested `<section>` tags respecting heading hierarchy.

---

### [HeadingLevel](HeadingLevel.md)
**Adjust heading levels**

Transforms heading levels through shifting (h1→h2), explicit mapping, or custom callbacks.

- **Use Case**: Normalize heading hierarchies, compose documents
- **Configuration**: Shift, map, or callback strategies
- **Complexity**: Moderate
- **Best For**: Document composition, content inclusion, normalization

Example:
```php
new HeadingLevelExtension(['down' => 1])  // h1→h2, h2→h3, etc.
```

---

### [LinkRewriter](LinkRewriter.md)
**Rewrite URLs in links and images**

Transforms URLs in links and images through multiple strategies: base URI prepending, simple mapping, regex patterns, or custom callbacks.

- **Use Case**: CDN integration, URL transformation, link prefixing
- **Configuration**: Base URI, mapping, regex, or callbacks
- **Complexity**: Advanced (Composable rewriters)
- **Best For**: Static site generation, multi-domain setups, API documentation

Example:
```php
new LinkRewriterExtension([
    'base_uri' => 'https://docs.example.com'
])
```

---

## Choosing Extensions

### By Use Case

**Creating beautiful code blocks?**
→ [CodeBlockTitle](CodeBlockTitle.md)

**Organizing content hierarchically?**
→ [ContentSlicer](ContentSlicer.md)

**Including external markdown?**
→ [HeadingLevel](HeadingLevel.md)

**Publishing to multiple domains?**
→ [LinkRewriter](LinkRewriter.md)

### By Complexity

**No Configuration**
- [CodeBlockTitle](CodeBlockTitle.md)
- [ContentSlicer](ContentSlicer.md)

**Simple Configuration**
- [HeadingLevel](HeadingLevel.md) - Shift, map, or callback

**Advanced Configuration**
- [LinkRewriter](LinkRewriter.md) - Composable rewriters

## Common Combinations

### Documentation Site
```php
$environment->addExtension(new CodeBlockTitleExtension());
$environment->addExtension(new ContentSlicerExtension());
$environment->addExtension(new LinkRewriterExtension([
    'base_uri' => 'https://docs.example.com'
]));
```

### Blog Engine
```php
$environment->addExtension(new CodeBlockTitleExtension());
$environment->addExtension(new ContentSlicerExtension());
```

### Content Composition
```php
$environment->addExtension(new HeadingLevelExtension(['down' => 1]));
$environment->addExtension(new ContentSlicerExtension());
```

### Multi-domain Publishing
```php
$environment->addExtension(new LinkRewriterExtension([
    'base_uri' => 'https://example.com'
]));
// Use same markdown for multiple domains by changing config
```

## Pattern Reference

### Renderer-Based Extensions
- **CodeBlockTitle** - Decorates existing renderers with higher priority
- Pattern: Wrapper around default renderers

### Event-Based Extensions
- **ContentSlicer** - Listens to `DocumentParsedEvent`, modifies AST nodes
- **HeadingLevel** - Listens to `DocumentParsedEvent`, transforms heading levels
- **LinkRewriter** - Listens to `DocumentParsedEvent`, rewrites node URLs
- Pattern: Post-parsing tree transformation

## Configuration Patterns

### No Configuration
```php
$extension = new CodeBlockTitleExtension();
$extension = new ContentSlicerExtension();
```

### Simple Array Config
```php
$extension = new HeadingLevelExtension(['down' => 1]);
$extension = new LinkRewriterExtension(['base_uri' => 'https://example.com']);
```

### Composable Rewriters
```php
$extension = new LinkRewriterExtension([
    'base_uri' => 'https://docs.example.com',
    'map' => ['/old' => '/new'],
    'pattern' => ['pattern' => '/regex/', 'replacement' => 'repl'],
    'callback' => function($url) { return $url; }
]);
```

## Testing Extensions

Each extension has comprehensive tests:
- **Integration tests** in `tests/Integration/Extension/`
- **Unit tests** in `tests/Unit/Extension/`

Run tests:
```bash
php vendor/bin/phpunit
```

## Implementation Notes

- All extensions follow PSR-4 autoloading under `Alto\CommonMark\Extension\*`
- Strict type declarations in all files
- Maximum PHPStan strictness (level 10)
- PSR-12 + Symfony coding standards

## See Also

- [Main README](../README.md)
- [CLAUDE.md](../CLAUDE.md) - Development guide
- [league/commonmark documentation](https://commonmark.thephpleague.com/)
