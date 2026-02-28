<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\HeadingLevel;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;

/**
 * @phpstan-type HeadingMap array<int, int>
 * @phpstan-type HeadingLevelConfig array{
 *     map?: HeadingMap,
 *     down?: int,
 *     callback?: callable(int): ?int
 * }
 */
final class HeadingLevelExtension implements ExtensionInterface
{
    /**
     * @param HeadingLevelConfig $config
     */
    public function __construct(
        private readonly array $config = [],
    ) {
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, new HeadingLevelProcessor($this->config));
    }
}
