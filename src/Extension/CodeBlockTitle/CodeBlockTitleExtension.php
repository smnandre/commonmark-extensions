<?php

declare(strict_types=1);

namespace Alto\CommonMark\Extension\CodeBlockTitle;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Renderer\Block\FencedCodeRenderer;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

final class CodeBlockTitleExtension implements ExtensionInterface
{
    public function __construct(
        private readonly ?NodeRendererInterface $baseRenderer = null,
    ) {
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $base = $this->baseRenderer ?? new FencedCodeRenderer();

        $environment->addRenderer(
            FencedCode::class,
            new CodeBlockTitleRenderer($base),
            // higher priority than default so we run first
            10

            // TODO if/check herer: 'return'
        );

        return;
    }
}
