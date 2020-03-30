<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        $rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();

        // Register file extensions
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',rsi';

        // Register the online media helper
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['rsi'] = \Saccas\Srgssr\Resource\OnlineMedia\Helpers\RsiHelper::class;

        // Register the mime type
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['rsi'] = 'video/rsi';

        // Register the renderer for the frontend
        $rendererRegistry->registerRendererClass(\Saccas\Srgssr\Resource\Rendering\RsiRenderer::class);

        // Same for rts
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',rts';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['rts'] = \Saccas\Srgssr\Resource\OnlineMedia\Helpers\RtsHelper::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['rts'] = 'video/rts';
        $rendererRegistry->registerRendererClass(\Saccas\Srgssr\Resource\Rendering\RtsRenderer::class);

        // Same for srf
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',srf';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['srf'] = \Saccas\Srgssr\Resource\OnlineMedia\Helpers\SrfHelper::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['srf'] = 'video/srf';
        $rendererRegistry->registerRendererClass(\Saccas\Srgssr\Resource\Rendering\SrfRenderer::class);
    },
    'srgssr'
);
