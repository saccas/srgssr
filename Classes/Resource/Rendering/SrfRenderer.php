<?php
namespace Saccas\Srgssr\Resource\Rendering;

/***
 *
 * This file is part of the "Srgssr" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 https://www.sac-cas.ch
 *
 ***/

use TYPO3\CMS\Core\Resource\FileInterface;

class SrfRenderer extends AbstractSrgssrRenderer
{
    /** @var string */
    protected $channelName = 'srf';

    /** @var string */
    protected $extension = 'srf';

    /**
     * Check if given File(Reference) can be rendered
     *
     * @param FileInterface $file File of FileReference to render
     * @return bool
     */
    public function canRender(FileInterface $file)
    {
        return ($file->getMimeType() === 'video/srf' || $file->getExtension() === $this->extension) && $this->getOnlineMediaHelper($file) !== false;
    }
}
