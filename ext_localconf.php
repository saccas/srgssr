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

        // Same for rtr
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',rtr';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['rtr'] = \Saccas\Srgssr\Resource\OnlineMedia\Helpers\RtrHelper::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['rtr'] = 'video/rtr';
        $rendererRegistry->registerRendererClass(\Saccas\Srgssr\Resource\Rendering\RtrRenderer::class);

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

        // Same for swi
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',swi';
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['swi'] = \Saccas\Srgssr\Resource\OnlineMedia\Helpers\SwiHelper::class;
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['swi'] = 'video/swi';
        $rendererRegistry->registerRendererClass(\Saccas\Srgssr\Resource\Rendering\SwiRenderer::class);
    },
    'srgssr'
);
