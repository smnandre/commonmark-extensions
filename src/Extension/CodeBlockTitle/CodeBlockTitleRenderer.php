<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\CodeBlockTitle;

use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

final class CodeBlockTitleRenderer implements NodeRendererInterface
{
    public function __construct(
        private readonly NodeRendererInterface $base,
    ) {
    }

    /**
     * @return \Stringable|string|null
     */
    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (!$node instanceof FencedCode) {
            return $this->base->render($node, $childRenderer);
        }

        $info = InfoStringParser::parse($node->getInfo() ?? '');
        $title = $info['attrs']['title'] ?? $info['attrs']['filename'] ?? null;

        // Let the default renderer build <pre><code ...>…</code></pre>
        $inner = $this->base->render($node, $childRenderer);

        if (null === $title) {
            return $inner;
        }

        $escapedTitle = Xml::escape($title);
        $caption = new HtmlElement('figcaption', ['class' => 'code-title'], $escapedTitle);

        return new HtmlElement(
            'figure',
            ['class' => 'code-block has-title', 'data-title' => $title],
            $caption.$inner
        );
    }
}
