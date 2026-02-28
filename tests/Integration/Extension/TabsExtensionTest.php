<?php

declare(strict_types=1);

namespace Alto\CommonMark\Tests\Integration\Extension;

use Alto\CommonMark\Extension\Tabs\TabsExtension;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TabsExtension::class)]
final class TabsExtensionTest extends TestCase
{
    public function testRendersTabListTabsAndPanels(): void
    {
        $this->bootstrapTabsClasses();

        $html = $this->renderTabGroup([
            new \Alto\CommonMark\Extension\Tabs\TabItem('Overview', 'Overview content', true),
            new \Alto\CommonMark\Extension\Tabs\TabItem('Install', 'Install content', false),
        ]);

        self::assertStringContainsString('<div class="tabs-list" role="tablist">', $html);
        self::assertSame(2, preg_match_all('/<button[^>]+role="tab"/', $html));
        self::assertSame(2, preg_match_all('/<div[^>]+role="tabpanel"/', $html));
        self::assertStringContainsString('id="tabs-test-tab-0"', $html);
        self::assertStringContainsString('aria-controls="tabs-test-panel-0"', $html);
        self::assertStringContainsString('id="tabs-test-panel-0"', $html);
        self::assertStringContainsString('aria-labelledby="tabs-test-tab-0"', $html);
        self::assertStringContainsString('class="tab active"', $html);
        self::assertStringContainsString('class="tab-panel active"', $html);
        self::assertStringContainsString('data-panel-index="1" hidden', $html);
    }

    public function testRendersBasicTabContent(): void
    {
        $this->bootstrapTabsClasses();

        $html = $this->renderTabGroup([
            new \Alto\CommonMark\Extension\Tabs\TabItem('Overview', "Line 1\nLine <strong>2</strong> & more", true),
        ]);

        self::assertStringContainsString('<div>Line 1<br />', $html);
        self::assertStringContainsString('Line &lt;strong&gt;2&lt;/strong&gt; &amp; more', $html);
        self::assertStringNotContainsString('Line <strong>2</strong> & more', $html);
    }

    public function testReturnsEmptyMarkupWhenNoTabsExist(): void
    {
        $this->bootstrapTabsClasses();

        $html = $this->renderTabGroup([]);

        self::assertSame('', $html);
    }

    public function testEscapesTabTitles(): void
    {
        $this->bootstrapTabsClasses();

        $html = $this->renderTabGroup([
            new \Alto\CommonMark\Extension\Tabs\TabItem('<script>alert(1)</script>', 'Safe content', true),
        ]);

        self::assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        self::assertStringNotContainsString('<script>alert(1)</script>', $html);
    }

    public function testDoesNotParseIndentedTabsDirective(): void
    {
        $conv = $this->createConverter();
        $html = (string) $conv->convert("    @tabs\n    @tab \"Overview\"\n    Content\n    @endtabs");

        self::assertStringContainsString('<pre><code>@tabs', $html);
        self::assertStringContainsString('@tab &quot;Overview&quot;', $html);
    }

    /**
     * @param array<int, \Alto\CommonMark\Extension\Tabs\TabItem> $tabs
     */
    private function renderTabGroup(array $tabs): string
    {
        $group = new \Alto\CommonMark\Extension\Tabs\TabGroup('tabs-test');
        $group->tabs = $tabs;

        $renderer = new \Alto\CommonMark\Extension\Tabs\TabGroupRenderer([
            'container_class' => 'tabs-container',
            'tabs_class' => 'tabs-list',
            'tab_class' => 'tab',
            'panel_class' => 'tab-panel',
            'active_class' => 'active',
            'generate_ids' => true,
        ]);

        return $renderer->render($group, $this->createStub(ChildNodeRendererInterface::class));
    }

    private function bootstrapTabsClasses(): void
    {
        new TabsExtension();
    }

    private function createConverter(): MarkdownConverter
    {
        $env = new Environment();
        $env->addExtension(new CommonMarkCoreExtension());
        $env->addExtension(new TabsExtension());

        return new MarkdownConverter($env);
    }
}
