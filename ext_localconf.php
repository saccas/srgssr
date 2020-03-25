<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // Register file extension "srgssr"
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext'] .= ',srf';

        // Register the online media helper
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['fal']['onlineMediaHelpers']['srf']
            = \Saccas\Srf\Resource\OnlineMedia\Helpers\SrfHelper::class;

        // Register the mime type
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['FileInfo']['fileExtensionToMimeType']['srf'] = 'video/srf';

        // Register the renderer for the frontend
        $rendererRegistry = \TYPO3\CMS\Core\Resource\Rendering\RendererRegistry::getInstance();
        $rendererRegistry->registerRendererClass(\Saccas\Srf\Resource\Rendering\SrfRenderer::class);
    },
    'srgssr'
);
