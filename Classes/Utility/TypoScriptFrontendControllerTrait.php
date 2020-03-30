<?php

namespace Saccas\Srgssr\Utility;

use TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController;

trait TypoScriptFrontendControllerTrait
{
    protected function getTypoScriptFrontendController(): ?TypoScriptFrontendController
    {
        return $GLOBALS['TSFE'] ?? null;
    }
}
