<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\LinkRewriter;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Node\Node;

final class LinkRewriterProcessor
{
    /** @var callable(string, Node): string */
    private $rewriter;

    /**
     * @param callable(string, Node): string $rewriter
     */
    public function __construct(callable $rewriter)
    {
        $this->rewriter = $rewriter;
    }

    public function onDocumentParsed(DocumentParsedEvent $event): void
    {
        $walker = $event->getDocument()->walker();

        while ($walk = $walker->next()) {
            if (!$walk->isEntering()) {
                continue;
            }

            $node = $walk->getNode();

            if ($node instanceof Link) {
                $node->setUrl(($this->rewriter)($node->getUrl(), $node));
            } elseif ($node instanceof Image) {
                $node->setUrl(($this->rewriter)($node->getUrl(), $node));
            }
        }
    }
}
