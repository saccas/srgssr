<?php

declare(strict_types=1);

namespace Saccas\Srgssr\Tests\Functional\Resource\OnlineMedia\Helpers;

use Saccas\Srgssr\Tests\Functional\AbstractFunctionalWithFileExtensionTestCase;
use Saccas\Srgssr\Tests\Functional\GuzzleClientFaker;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\ResourceFactory;

abstract class AbstractFunctionalTestCase extends AbstractFunctionalWithFileExtensionTestCase
{
    protected function getUploadFolder(): Folder
    {
        $folder = $this->get(ResourceFactory::class)
            ->getObjectFromCombinedIdentifier('1:uploads/');

        self::assertInstanceOf(Folder::class, $folder);

        return $folder;
    }

    /**
     * @param mixed[] $metaData
     */
    protected function mockMetaDataResponse(array $metaData): void
    {
        GuzzleClientFaker::appendResponseFromContent(
            json_encode($metaData, JSON_THROW_ON_ERROR)
        );
    }

    protected function getSubject(File $file): OnlineMediaHelperInterface
    {
        $subject = $this->get(OnlineMediaHelperRegistry::class)
            ->getOnlineMediaHelper($file);

        self::assertInstanceOf(OnlineMediaHelperInterface::class, $subject);

        return $subject;
    }
}
