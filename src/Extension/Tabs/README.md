# Tabs Extension

## Summary
Creates tabbed content blocks from `@tabs` / `@tab` / `@endtabs` markers and renders accessible tab/panel HTML.

## Status
| Item | Value |
|------|-------|
| Extension | `Tabs` |
| Namespace | `Alto\\CommonMark\\Extension\\Tabs\\TabsExtension` |
| Version | `dev-main` |

## Installation
```bash
composer require alto/commonmark
```

## Registration
```php
use Alto\CommonMark\Extension\Tabs\TabsExtension;
use League\CommonMark\Environment\Environment;

$environment = new Environment();
$environment->addExtension(new TabsExtension());
```

## Syntax
````markdown
@tabs
@tab "PHP"
```php
<?php echo 'Hello';
```

@tab "JavaScript"
```js
console.log('Hello');
```
@endtabs
````

## Configuration
- Constructor accepts keys: `container_class`, `tabs_class`, `tab_class`, `panel_class`, `active_class`, `generate_ids`.
- Default output includes ARIA roles (`tablist`, `tab`, `tabpanel`).

## Minimal Example
```markdown
@tabs
@tab "Overview"
Project overview.
@tab "Install"
composer require vendor/package
@endtabs
```

## Monorepo Note
This extension is currently distributed from the monorepo root package (`alto/commonmark`).
