<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional;

use PHPUnit\Framework\MockObject\Stub;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileReference;

abstract class AbstractFunctionalWithFileExtensionTestCase extends AbstractFunctionalTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->getConnectionPool()
            ->getConnectionForTable('sys_file')
            ->update(
                'sys_file',
                ['extension' => $this->getExtension()],
                ['uid' => 1]
            );
    }

    /**
     * @return File&Stub
     */
    protected function createFile(?string $extension = null): Stub
    {
        return parent::createFile($extension ?? $this->getExtension());
    }

    /**
     * @return FileReference&Stub
     */
    protected function createFileReference(): Stub
    {
        $file = self::createStub(FileReference::class);
        $file->method('getExtension')->willReturn($this->getExtension());
        $file->method('getSize')->willReturn(2048);
        $file->method('getContents')->willReturn('051820c8-731f-4b46-b5d0-0f2111a55e72');
        $file->method('getUid')->willReturn(1);
        $file->method('getOriginalFile')->willReturn($this->createFile());

        return $file;
    }

    abstract protected function getExtension(): string;
}
