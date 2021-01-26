<?php
namespace Saccas\Srgssr\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;

class RtrRenderer extends AbstractSrgssrRenderer
{
    protected string $channelName = 'rtr';

    protected string $extension = 'rtr';

    /**
     * Check if given File(Reference) can be rendered
     *
     * @param FileInterface $file File of FileReference to render
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        return ($file->getMimeType() === 'video/rtr' || $file->getExtension() === $this->extension) && $this->getOnlineMediaHelper($file) !== false;
    }
}
