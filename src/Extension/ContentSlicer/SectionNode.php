<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\ContentSlicer;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

final class SectionNode extends AbstractBlock implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable
    {
        self::assertInstanceOf($node);

        return new HtmlElement('section', [], $childRenderer->renderNodes($node->children()));
    }
}
