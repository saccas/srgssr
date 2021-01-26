<?php
namespace Saccas\Srgssr\Resource\Rendering;

use TYPO3\CMS\Core\Resource\FileInterface;

class RsiRenderer extends AbstractSrgssrRenderer
{
    protected string $channelName = 'rsi';

    protected string $extension = 'rsi';

    /**
     * Check if given File(Reference) can be rendered
     *
     * @param FileInterface $file File of FileReference to render
     * @return bool
     */
    public function canRender(FileInterface $file): bool
    {
        return ($file->getMimeType() === 'video/rsi' || $file->getExtension() === $this->extension) && $this->getOnlineMediaHelper($file) !== false;
    }
}
