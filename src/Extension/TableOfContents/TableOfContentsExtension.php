<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\TableOfContents;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockStart;
use League\CommonMark\Parser\Block\BlockStartParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Parser\MarkdownParserStateInterface;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class TableOfContentsExtension implements ExtensionInterface
{
    /** @var array<string, mixed> */
    private readonly array $config;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge([
            'min_level' => 1,
            'max_level' => 6,
            'style' => 'bullet',
            'class' => 'table-of-contents',
            'id' => 'toc',
            'title' => null,
            'marker' => '@toc',
        ], $config);
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addBlockStartParser(new TocBlockParser($this->config), 200)
            ->addRenderer(TocBlock::class, new TocRenderer($this->config))
            ->addEventListener(DocumentParsedEvent::class, new TocProcessor($this->config));
    }
}

final class TocBlock extends AbstractBlock
{
    /**
     * @param array<string, mixed>        $options
     * @param array<array<string, mixed>> $headings
     */
    public function __construct(
        public readonly array $options = [],
        public array $headings = [],
    ) {
        parent::__construct();
    }
}

final class TocBlockParser implements BlockStartParserInterface
{
    /**
     * @param array<string, mixed> $config
     *
     * @phpstan-param array<string, mixed> $config
     */
    public function __construct(
        /** @phpstan-ignore-next-line property.onlyWritten */
        private readonly array $config,
    ) {
    }

    public function tryStart(Cursor $cursor, MarkdownParserStateInterface $parserState): ?BlockStart
    {
        $indent = $cursor->getIndent();

        if ($indent > 3) {
            return BlockStart::none();
        }

        $line = trim($cursor->getRemainder());

        if (!preg_match('/^@toc(?:\s*\{([^}]+)\})?$/', $line, $matches)) {
            return BlockStart::none();
        }

        $cursor->advanceToEnd();

        /**
         * @var array<string, mixed> $options
         */
        $options = [];
        if (isset($matches[1])) {
            $options = $this->parseOptions($matches[1]);
        }

        $block = new TocBlock($options);

        return BlockStart::of(new TocBlockContinueParser($block));
    }

    /**
     * @return array<string, mixed>
     */
    private function parseOptions(string $optionsStr): array
    {
        /**
         * @var array<string, mixed> $options
         */
        $options = [];
        $pairs = preg_split('/,\s*/', $optionsStr);

        if (false === $pairs) {
            return $options;
        }

        foreach ($pairs as $pair) {
            if (preg_match('/^(\w+):\s*(.+)$/', trim($pair), $matches)) {
                $key = $matches[1];
                $value = trim($matches[2], '"\'');

                $options[$key] = match ($key) {
                    'min', 'max' => (int) $value,
                    'ordered' => 'true' === $value,
                    default => $value,
                };
            }
        }

        return $options;
    }
}

final class TocBlockContinueParser extends AbstractBlockContinueParser
{
    public function __construct(
        private readonly TocBlock $block,
    ) {
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function tryContinue(
        Cursor $cursor,
        \League\CommonMark\Parser\Block\BlockContinueParserInterface $activeBlockParser,
    ): ?\League\CommonMark\Parser\Block\BlockContinue {
        return \League\CommonMark\Parser\Block\BlockContinue::none();
    }
}

final class TocProcessor
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        private readonly array $config,
    ) {
    }

    public function __invoke(DocumentParsedEvent $event): void
    {
        $document = $event->getDocument();
        $headings = [];
        /** @var array<TocBlock> */
        $tocBlocks = [];

        // First pass: collect all headings and TOC blocks
        $walker = $document->walker();
        while ($event = $walker->next()) {
            $node = $event->getNode();

            if ($node instanceof Heading && $event->isEntering()) {
                $id = $this->generateId($node);
                $node->data->set('id', $id);

                $headings[] = [
                    'level' => $node->getLevel(),
                    'text' => $this->getHeadingText($node),
                    'id' => $id,
                ];
            }

            if ($node instanceof TocBlock && $event->isEntering()) {
                $tocBlocks[] = $node;
            }
        }

        // Second pass: populate TOC blocks with headings
        foreach ($tocBlocks as $tocBlock) {
            $tocBlock->headings = $this->filterHeadings($headings, $tocBlock->options);
        }
    }

    private function getHeadingText(Heading $heading): string
    {
        $text = '';
        foreach ($heading->children() as $child) {
            if ($child instanceof Text) {
                $text .= $child->getLiteral();
            }
        }

        return $text;
    }

    private function generateId(Heading $heading): string
    {
        $text = $this->getHeadingText($heading);

        return $this->slugify($text);
    }

    private function slugify(string $text): string
    {
        $text = mb_strtolower($text);
        $text = (string) preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim($text, '-');
    }

    /**
     * @param array<array<string, mixed>> $headings
     * @param array<string, mixed>        $options
     *
     * @return array<array<string, mixed>>
     */
    private function filterHeadings(array $headings, array $options): array
    {
        $minLevel = $options['min'] ?? $this->config['min_level'];
        $maxLevel = $options['max'] ?? $this->config['max_level'];

        return array_filter(
            $headings,
            static fn (array $heading) => $heading['level'] >= $minLevel && $heading['level'] <= $maxLevel
        );
    }
}

final class TocRenderer implements NodeRendererInterface
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        private readonly array $config,
    ) {
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        assert($node instanceof TocBlock);

        if (empty($node->headings)) {
            return '';
        }

        /** @var array<string, mixed> $options */
        $options = array_merge($this->config, $node->options);

        /** @var string $class */
        $class = $options['class'];
        /** @var string $id */
        $id = $options['id'];
        $html = '<div class="'.htmlspecialchars($class).'" id="'.htmlspecialchars($id).'">';

        // Add title if specified
        if ($options['title'] ?? null) {
            /** @var string $title */
            $title = $options['title'];
            $html .= '<h2>'.htmlspecialchars($title).'</h2>';
        }

        // Generate the TOC list
        $listType = ('ordered' === $options['style'] || ($options['ordered'] ?? false)) ? 'ol' : 'ul';

        $html .= '<'.$listType.'>';

        /** @var int $currentLevel */
        $currentLevel = $node->headings[0]['level'] ?? 1;
        $baseLevel = $currentLevel;

        foreach ($node->headings as $heading) {
            /** @var int $level */
            $level = $heading['level'];

            // Close deeper levels
            while ($currentLevel > $level) {
                $html .= '</'.$listType.'></li>';
                --$currentLevel;
            }

            // Open new levels
            while ($currentLevel < $level) {
                $html .= '<li><'.$listType.'>';
                ++$currentLevel;
            }

            /** @var string $headingId */
            $headingId = $heading['id'];
            /** @var string $text */
            $text = $heading['text'];
            $html .= '<li><a href="#'.htmlspecialchars($headingId).'">'.htmlspecialchars($text).'</a></li>';
        }

        // Close remaining open levels
        while ($currentLevel > $baseLevel) {
            $html .= '</'.$listType.'></li>';
            --$currentLevel;
        }

        $html .= '</'.$listType.'></div>';

        return $html;
    }
}
