<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional\Resource\OnlineMedia\Rendering;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Saccas\Srgssr\Resource\Rendering\SrfRenderer;
use Saccas\Srgssr\Tests\Functional\AbstractFunctionalWithFileExtensionTestCase;

#[CoversClass(SrfRenderer::class)]
final class SrfRendererTest extends AbstractFunctionalWithFileExtensionTestCase
{
    #[Test]
    public function returnsTrueForCanRenderIfFileMatches(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        self::assertTrue($subject->canRender($file));
    }

    #[Test]
    public function returnsPriority(): void
    {
        $subject = new SrfRenderer();

        self::assertSame(1, $subject->getPriority());
    }

    #[Test]
    public function canCreateUrlWithoutOptions(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $url = $subject->createUrl([], $file);

        self::assertSame(
            '//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&',
            $url
        );
    }

    #[Test]
    public function canCreateUrlWithAutoplayOption(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $url = $subject->createUrl([
            'autoplay' => 1,
        ], $file);

        self::assertSame(
            '//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&autoplay=true',
            $url
        );
    }

    #[Test]
    public function canCreateUrlWithUnsupportedOption(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $url = $subject->createUrl([
            'unsupported' => 1,
        ], $file);

        self::assertSame(
            '//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&',
            $url
        );
    }

    #[Test]
    public function canCreateUrlWithFileReference(): void
    {
        $fileReference = $this->createFileReference();

        $subject = new SrfRenderer();

        $url = $subject->createUrl([], $fileReference);

        self::assertSame(
            '//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&',
            $url
        );
    }

    #[Test]
    public function rendersFileWithMinimumOfOptions(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $rendered = $subject->render($file, '200m', '300c');

        self::assertSame(
            '<iframe src="//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&" allowfullscreen width="200" height="300" fullscreen></iframe>',
            $rendered
        );
    }

    #[Test]
    public function rendersFileWithAutoplayActiveFromFileReference(): void
    {
        $fileReference = $this->createFileReference();
        $fileReference->method('getProperty')->willReturnMap([
            ['autoplay', 1],
        ]);

        $subject = new SrfRenderer();

        $rendered = $subject->render($fileReference, '200m', '300c');

        self::assertSame(
            '<iframe src="//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&autoplay=true" allowfullscreen width="200" height="300" autoplay; fullscreen></iframe>',
            $rendered
        );
    }

    #[Test]
    public function rendersFileWithAutoplayActiveFromOptions(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $rendered = $subject->render($file, '200m', '300c', ['autoplay' => 1]);

        self::assertSame(
            '<iframe src="//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&autoplay=true" allowfullscreen width="200" height="300" autoplay; fullscreen></iframe>',
            $rendered
        );
    }

    #[Test]
    public function rendersFileWithAllowFromOptions(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $rendered = $subject->render($file, '200m', '300c', ['allow' => 'autoplay; fullscreen; custom;']);

        self::assertSame(
            '<iframe src="//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&" allowfullscreen width="200" height="300" autoplay; fullscreen; custom;></iframe>',
            $rendered
        );
    }

    #[Test]
    public function rendersFileWithAdditionalAttributes(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $rendered = $subject->render($file, '200m', '300c', ['additionalAttributes' => [
            'attribute' => 'value',
        ]]);

        self::assertSame(
            '<iframe src="//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&" allowfullscreen attribute="value" width="200" height="300" fullscreen></iframe>',
            $rendered
        );
    }

    #[Test]
    public function rendersFileWithDataAttributes(): void
    {
        $file = $this->createFile();

        $subject = new SrfRenderer();

        $rendered = $subject->render($file, '200m', '300c', ['data' => [
            'attribute' => 'value',
        ]]);

        self::assertSame(
            '<iframe src="//tp.srgssr.ch/p/srf/embed?urn=urn:srf:video:051820c8-731f-4b46-b5d0-0f2111a55e72&" allowfullscreen data-attribute="value" width="200" height="300" fullscreen></iframe>',
            $rendered
        );
    }

    protected function getExtension(): string
    {
        return 'srf';
    }
}
