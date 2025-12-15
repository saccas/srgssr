<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional\Integration;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Saccas\Srgssr\Resource\Rendering\RsiRenderer;
use Saccas\Srgssr\Resource\Rendering\RtrRenderer;
use Saccas\Srgssr\Resource\Rendering\RtsRenderer;
use Saccas\Srgssr\Resource\Rendering\SrfRenderer;
use Saccas\Srgssr\Tests\Functional\AbstractFunctionalTestCase;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;

#[CoversClass(RsiRenderer::class)]
#[CoversClass(RtrRenderer::class)]
#[CoversClass(RtsRenderer::class)]
#[CoversClass(SrfRenderer::class)]
final class RendererRegistryTest extends AbstractFunctionalTestCase
{
    #[Test]
    public function registryReturnsRsiRenderer(): void
    {
        $subject = $this->get(RendererRegistry::class)
            ->getRenderer($this->createFile('rsi'));

        self::assertInstanceOf(RsiRenderer::class, $subject);
    }

    #[Test]
    public function registryReturnsRtrRenderer(): void
    {
        $subject = $this->get(RendererRegistry::class)
            ->getRenderer($this->createFile('rtr'));

        self::assertInstanceOf(RtrRenderer::class, $subject);
    }

    #[Test]
    public function registryReturnsRtsRenderer(): void
    {
        $subject = $this->get(RendererRegistry::class)
            ->getRenderer($this->createFile('rts'));

        self::assertInstanceOf(RtsRenderer::class, $subject);
    }

    #[Test]
    public function registryReturnsSrfRenderer(): void
    {
        $subject = $this->get(RendererRegistry::class)
            ->getRenderer($this->createFile('srf'));

        self::assertInstanceOf(SrfRenderer::class, $subject);
    }
}
