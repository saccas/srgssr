<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\ValueObject\PhpVersion;
use Ssch\TYPO3Rector\Configuration\Typo3Option;
use Ssch\TYPO3Rector\Set\Typo3LevelSetList;
use Ssch\TYPO3Rector\Set\Typo3SetList;
use Ssch\TYPO3Rector\TYPO310\v4\UseFileGetContentsForGetUrlRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/../',
    ])
    ->withSkip([
        __DIR__ . '/../.Build',
        __DIR__ . '/../config',
        __DIR__ . '/../var',
        __DIR__ . '/.phprector.cache',

        UseFileGetContentsForGetUrlRector::class,
    ])
    ->withImportNames(false, true, false, true)
    ->withPhpVersion(PhpVersion::PHP_83)
    ->withSets([
        Typo3SetList::CODE_QUALITY,
        Typo3SetList::GENERAL,
        Typo3LevelSetList::UP_TO_TYPO3_13,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ])
    ->withPHPStanConfigs([
        Typo3Option::PHPSTAN_FOR_RECTOR_PATH,
    ])
    ->withCache('./.phprector.cache', FileCacheStorage::class);
