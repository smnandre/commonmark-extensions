<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\LinkRewriter;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;

final class LinkRewriterExtension implements ExtensionInterface
{
    /** @var callable(string, Node): string */
    private $rewriter;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config)
    {
        /** @var list<callable(string, Node): string> $rewriters */
        $rewriters = [];

        if (isset($config['base_uri'])) {
            if (!is_string($config['base_uri'])) {
                throw new \TypeError('Link rewriter config "base_uri" must be a string.');
            }

            $baseUriRewriter = Rewriter::baseUri($config['base_uri']);
            $rewriters[] = static fn (string $url, Node $node): string => $baseUriRewriter($url);
        }

        if (isset($config['map'])) {
            if (!is_array($config['map'])) {
                throw new \TypeError('Link rewriter config "map" must be an array.');
            }

            $map = [];
            foreach ($config['map'] as $from => $to) {
                if (!is_string($from) || !is_string($to)) {
                    throw new \TypeError('Link rewriter config "map" must contain only string keys and string values.');
                }

                $map[$from] = $to;
            }

            $mapRewriter = Rewriter::map($map);
            $rewriters[] = static fn (string $url, Node $node): string => $mapRewriter($url);
        }

        if (isset($config['pattern'])) {
            if (!is_array($config['pattern'])) {
                throw new \TypeError('Link rewriter config "pattern" must be an array.');
            }

            $pattern = $config['pattern']['pattern'] ?? null;
            $replacement = $config['pattern']['replacement'] ?? null;
            if (!is_string($pattern) || !is_string($replacement)) {
                throw new \TypeError('Link rewriter config "pattern" must define string keys "pattern" and "replacement".');
            }

            $patternRewriter = Rewriter::pattern($pattern, $replacement);
            $rewriters[] = static fn (string $url, Node $node): string => $patternRewriter($url);
        }

        if (isset($config['callback'])) {
            if (!is_callable($config['callback'])) {
                throw new \TypeError('Link rewriter config "callback" must be callable.');
            }

            /** @var callable(string, Node=): string $callback */
            $callback = $config['callback'];
            $rewriters[] = Rewriter::callback($callback);
        }

        $this->rewriter = static function (string $url, Node $node) use ($rewriters): string {
            foreach ($rewriters as $rewriter) {
                $url = $rewriter($url, $node);
            }

            return $url;
        };
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $processor = new LinkRewriterProcessor($this->rewriter);
        $environment->addEventListener(DocumentParsedEvent::class, [$processor, 'onDocumentParsed']);
    }
}
