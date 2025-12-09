<?php

namespace Saccas\Srgssr\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;

class SrfRenderer extends AbstractSrgssrRenderer
{
    protected string $channelName = 'srf';

    protected string $extension = 'srf';

    protected string $mimeType = 'video/srf';

    /**
     * Check if given File(Reference) can be rendered
     *
     * @param FileInterface $file File of FileReference to render
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        return ($file->getMimeType() === $this->mimeType || $file->getExtension() === $this->extension) && $this->getOnlineMediaHelper($file) !== false;
    }
}
