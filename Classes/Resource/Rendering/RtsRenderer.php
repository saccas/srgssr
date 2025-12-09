<?php

namespace Saccas\Srgssr\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;

class RtsRenderer extends AbstractSrgssrRenderer
{
    protected string $channelName = 'rts';

    protected string $extension = 'rts';

    protected string $mimeType = 'video/rts';

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
