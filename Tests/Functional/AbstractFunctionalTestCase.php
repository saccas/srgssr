<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional;

use Codappix\Typo3PhpDatasets\TestingFramework;
use PHPUnit\Framework\MockObject\Stub;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class AbstractFunctionalTestCase extends FunctionalTestCase
{
    use TestingFramework;

    protected function setUp(): void
    {
        $this->coreExtensionsToLoad = [
            'typo3/cms-filelist',
            'typo3/cms-filemetadata',
        ];

        $this->testExtensionsToLoad = [
            'saccas/srgssr',
        ];

        parent::setUp();

        GuzzleClientFaker::registerClient();

        $this->importPHPDataSet($this->getFixtureFolder() . 'BaseDatabase.php');

        GeneralUtility::mkdir_deep($this->getInstancePath() . '/fileadmin/uploads/');
    }

    protected function tearDown(): void
    {
        GuzzleClientFaker::tearDown();
        GeneralUtility::rmdir($this->getInstancePath() . '/fileadmin/uploads/', true);
        GeneralUtility::rmdir($this->getInstancePath() . '/typo3temp/assets/', true);

        parent::tearDown();
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/Fixtures/';
    }

    /**
     * @return File&Stub
     */
    protected function createFile(string $extension): Stub
    {
        $file = self::createStub(File::class);
        $file->method('getExtension')->willReturn($extension);
        $file->method('getSize')->willReturn(2048);
        $file->method('getContents')->willReturn('051820c8-731f-4b46-b5d0-0f2111a55e72');
        $file->method('getUid')->willReturn(1);

        return $file;
    }
}
