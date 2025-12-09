<?php

use Saccas\Srgssr\Resource\OnlineMedia\Helpers\RsiHelper;
use Saccas\Srgssr\Resource\OnlineMedia\Helpers\RtrHelper;
use Saccas\Srgssr\Resource\OnlineMedia\Helpers\RtsHelper;
use Saccas\Srgssr\Resource\OnlineMedia\Helpers\SrfHelper;
use Saccas\Srgssr\Resource\Rendering\RsiRenderer;
use Saccas\Srgssr\Resource\Rendering\RtrRenderer;
use Saccas\Srgssr\Resource\Rendering\RtsRenderer;
use Saccas\Srgssr\Resource\Rendering\SrfRenderer;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;

(static function () {
    $rendererRegistry = GeneralUtility::makeInstance(RendererRegistry::class);

    $rendererRegistry->registerRendererClass(RsiRenderer::class);
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',rsi';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['rsi'] = RsiHelper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['rsi'] = 'video/rsi';

    $rendererRegistry->registerRendererClass(RtrRenderer::class);
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',rtr';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['rtr'] = RtrHelper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['rtr'] = 'video/rtr';

    $rendererRegistry->registerRendererClass(RtsRenderer::class);
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',rts';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['rts'] = RtsHelper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['rts'] = 'video/rts';

    $rendererRegistry->registerRendererClass(SrfRenderer::class);
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',srf';
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['srf'] = SrfHelper::class;
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['srf'] = 'video/srf';
})();
