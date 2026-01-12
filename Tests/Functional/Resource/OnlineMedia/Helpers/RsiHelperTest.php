<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional\Resource\OnlineMedia\Helpers;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Saccas\Srgssr\Resource\OnlineMedia\Helpers\RsiHelper;
use Saccas\Srgssr\Tests\Functional\GuzzleClientFaker;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;

#[CoversClass(RsiHelper::class)]
final class RsiHelperTest extends AbstractFunctionalTestCase
{
    #[Test]
    public function returnsOnlineMediaId(): void
    {
        $file = $this->createFile();

        $subject = $this->getSubject($file);

        self::assertSame(
            '051820c8-731f-4b46-b5d0-0f2111a55e72',
            $subject->getOnlineMediaId($file)
        );
    }

    #[Test]
    public function returnsPublicUrl(): void
    {
        $file = $this->createFile();

        $subject = $this->getSubject($file);

        self::assertSame(
            'https://www.rsi.ch/play/tv/video/?id=051820c8-731f-4b46-b5d0-0f2111a55e72',
            $subject->getPublicUrl($file)
        );
    }

    #[Test]
    public function returnsPreviewImageForChapter(): void
    {
        $this->mockMetaDataResponse([
            'chapterList' => [
                [
                    'imageUrl' => 'https://example.com/image/preview',
                ],
            ],
        ]);
        GuzzleClientFaker::appendResponseFromFile($this->getFixtureFolder() . 'PreviewImages/001.jpg');

        $file = $this->createFile();

        $subject = $this->getSubject($file);
        $result = $subject->getPreviewImage($file);

        self::assertSame($this->instancePath . '/typo3temp/assets/online_media/rsi_6c96f01771abd561078ce103532b124f.jpg', $result);
        self::assertFileEquals($this->getFixtureFolder() . 'PreviewImages/001.jpg', $result);
    }

    #[Test]
    public function returnsPreviewImageForEpisode(): void
    {
        $this->mockMetaDataResponse([
            'episode' => [
                'imageUrl' => 'https://example.com/image/preview',
            ],
        ]);
        GuzzleClientFaker::appendResponseFromFile($this->getFixtureFolder() . 'PreviewImages/001.jpg');

        $file = $this->createFile();

        $subject = $this->getSubject($file);
        $result = $subject->getPreviewImage($file);

        self::assertSame($this->instancePath . '/typo3temp/assets/online_media/rsi_6c96f01771abd561078ce103532b124f.jpg', $result);
        self::assertFileEquals($this->getFixtureFolder() . 'PreviewImages/001.jpg', $result);
    }

    #[Test]
    public function transformsUrlToFileWithWrongHost(): void
    {
        $subject = new RsiHelper('rsi');

        $result = $subject->transformUrlToFile('https://example.com', $this->getUploadFolder());

        self::assertNull($result);
    }

    #[Test]
    public function transformsUrlToFileWithMissingQuery(): void
    {
        $subject = new RsiHelper('rsi');

        $result = $subject->transformUrlToFile('https://www.rsi.ch', $this->getUploadFolder());

        self::assertNull($result);
    }

    #[Test]
    public function transformsUrlToFileWithMissingUrn(): void
    {
        $subject = new RsiHelper('rsi');

        $result = $subject->transformUrlToFile('https://www.rsi.ch?other=1', $this->getUploadFolder());

        self::assertNull($result);
    }

    #[Test]
    public function transformsUrlToFileWhenFileExists(): void
    {
        $result = $this->get(OnlineMediaHelperRegistry::class)->transformUrlToFile(
            'https://www.rsi.ch/play/tv/abstimmungen/video/praesidentenrunde-zur-abstimmung-vom-30-11-2025?urn=urn:rsi:video:3186a7cb-1cba-4e73-b764-e65e4826e194',
            $this->getUploadFolder(),
            ['rsi']
        );

        self::assertInstanceOf(File::class, $result);
        self::assertSame(1, $result->getUid());
        self::assertSame('7a146ba180118e76af17acb9cb828bd60ea0ce91', $result->getSha1());
    }

    #[Test]
    public function transformsUrlToFileWhenFileDoesNotExist(): void
    {
        $this->mockMetaDataResponse([
            'chapterList' => [
                [
                    'title' => 'Präsidentenrunde zur Abstimmung vom 30.11.2025',
                    'description' => 'Am Sonntag, 30. November 2025, entscheidet das Schweizer Stimmvolk an der Urne über zwei Eidgenössische Vorlagen: die Service-citoyen-Initiative und die Erbschaftssteuer-Initiative.',
                    'imageUrl' => 'https://example.com/image/preview',
                ],
            ],
        ]);
        $this->mockMetaDataResponse([
            'chapterList' => [
                [
                    'title' => 'Präsidentenrunde zur Abstimmung vom 30.11.2025',
                    'description' => 'Am Sonntag, 30. November 2025, entscheidet das Schweizer Stimmvolk an der Urne über zwei Eidgenössische Vorlagen: die Service-citoyen-Initiative und die Erbschaftssteuer-Initiative.',
                    'imageUrl' => 'https://example.com/image/preview',
                ],
            ],
        ]);

        $result = $this->get(OnlineMediaHelperRegistry::class)->transformUrlToFile(
            'https://www.rsi.ch/play/tv/abstimmungen/video/praesidentenrunde-zur-abstimmung-vom-30-11-2025?urn=urn:rsi:video:3186a7cb-1cba-4e73-b764-e65e4826e195',
            $this->getUploadFolder(),
            ['rsi']
        );

        self::assertInstanceOf(File::class, $result);
        self::assertSame('0ebd40d5ad876875b66a33eadc63c97f7f2ecb76', $result->getSha1());
        self::assertSame('Präsidentenrunde_zur_Abstimmung_vom_30.11.2025.rsi', $result->getName());
        self::assertSame('rsi', $result->getExtension());
        self::assertSame('3186a7cb-1cba-4e73-b764-e65e4826e195', $result->getContents());
        self::assertFileExists($this->getInstancePath() . '/fileadmin/uploads/' . $result->getName());
    }

    #[Test]
    public function returnsMetaData(): void
    {
        $metaData = [
            'chapterList' => [
                [
                    'title' => 'Präsidentenrunde zur Abstimmung vom 30.11.2025',
                    'description' => 'Am Sonntag, 30. November 2025, entscheidet das Schweizer Stimmvolk an der Urne über zwei Eidgenössische Vorlagen: die Service-citoyen-Initiative und die Erbschaftssteuer-Initiative.',
                    'imageUrl' => 'https://example.com/image/preview',
                ],
            ],
        ];

        $this->mockMetaDataResponse($metaData);

        $file = $this->createFile();

        $subject = $this->getSubject($file);

        self::assertSame([
            'title' => 'Präsidentenrunde zur Abstimmung vom 30.11.2025',
            'description' => 'Am Sonntag, 30. November 2025, entscheidet das Schweizer Stimmvolk an der Urne über zwei Eidgenössische Vorlagen: die Service-citoyen-Initiative und die Erbschaftssteuer-Initiative.',
            'width' => 1024,
            'height' => 576,
            'thumbnail_url' => 'https://example.com/image/preview/scale/width/1024',
            'type' => 'video',
        ], $subject->getMetaData($file));
    }

    protected function getExtension(): string
    {
        return 'rsi';
    }
}
