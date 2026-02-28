<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\HeadingLevel;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Node\Query;

/** @phpstan-import-type HeadingLevelConfig from HeadingLevelExtension */
final class HeadingLevelProcessor
{
    /**
     * @param HeadingLevelConfig $config
     */
    public function __construct(
        private readonly array $config = [],
    ) {
    }

    public function __invoke(DocumentParsedEvent $event): void
    {
        $document = $event->getDocument();

        /** @var iterable<Heading> $headings */
        $headings = (new Query())
            ->where(Query::type(Heading::class))
            ->findAll($document);

        foreach ($headings as $heading) {
            $level = $heading->getLevel();

            if (isset($this->config['map'])) {
                $newLevel = $this->config['map'][$level] ?? null;
            } elseif (isset($this->config['down'])) {
                $newLevel = $level + $this->config['down'];
            } elseif (isset($this->config['callback'])) {
                $newLevel = ($this->config['callback'])($level);
            } else {
                $newLevel = null;
            }

            if (null !== $newLevel) {
                $heading->setLevel($newLevel);
            }
        }
    }
}
