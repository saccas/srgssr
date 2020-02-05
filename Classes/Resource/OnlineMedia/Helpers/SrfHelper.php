<?php
namespace Saccas\Srf\Resource\OnlineMedia\Helpers;

/***
 *
 * This file is part of the "Srf" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 https://www.sac-cas.ch
 *
 ***/

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOEmbedHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Srf helper class
 */
class SrfHelper extends AbstractOEmbedHelper
{

    /** @var string */
    protected $extension = 'srf';

    /**
     * Get public url
     * Return NULL if you want to use core default behaviour
     *
     * @param File $file
     * @param bool $relativeToCurrentScript
     * @return string|null
     */
    public function getPublicUrl(File $file, $relativeToCurrentScript = false)
    {
        $videoId = $this->getOnlineMediaId($file);
        //https://www.srf.ch/play/tv/hoch-hinaus---das-expeditionsteam/video/schnee-und-eisregen-staffel-4-14?id=7ae924e9-de8f-4b40-bccc-92955ba0d769
        return sprintf('https://www.srf.ch/play/tv//video//?id=%s', $videoId);
    }

    /**
     * Get local absolute file path to preview image
     *
     * @param File $file
     * @return string
     */
    public function getPreviewImage(File $file)
    {
        $videoId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . 'srf_' . md5($videoId) . '.jpg';

        if (!file_exists($temporaryFileName)) {
            $oEmbedData = $this->getOEmbedData($videoId);
            $previewImage = GeneralUtility::getUrl($oEmbedData['thumbnail_url']);

            if ($previewImage !== false) {
                file_put_contents($temporaryFileName, $previewImage);
                GeneralUtility::fixPermissions($temporaryFileName);
            }
        }
        return $temporaryFileName;
    }

    /**
     * Try to transform given URL to a File
     *
     * @param string $url
     * @param Folder $targetFolder
     * @return File|null
     */
    public function transformUrlToFile($url, Folder $targetFolder)
    {
        $videoId = null;
        if (preg_match('/srf\.ch\/play\/tv\/\S+\/video\/\S+\/?id=?([\w\-.]{36})/i', $url, $matches)
        ) {
            $videoId = $matches[1];
        }

        if (empty($videoId)) {
            return null;
        }

        return $this->transformMediaIdToFile($videoId, $targetFolder, $this->extension);
    }

    /**
     * Get oEmbed data url
     *
     * @param string $mediaId
     * @param string $format
     * @return string
     */
    protected function getOEmbedUrl($mediaId, $format = 'json')
    {
        return 'https://v.srf.com/v_show/id_' . $mediaId . '.html';
    }

    /**
     * Get OEmbed data
     *
     * Apparently srf.ch does not provide an oEmbed API, but TYPO3 requires one.
     * So we have to rely on this ugly solution to "fake" an oEmbed response.
     *
     * @param string $mediaId
     * @return array|null
     */
    protected function getOEmbedData($mediaId)
    {
        $html = GeneralUtility::getUrl(
            $this->getOEmbedUrl($mediaId)
        );

        $doc = new \DOMDocument();
        $doc->loadHTML($html);

        $title = 'Srf Video'; // Default value
        $image = '';

        $metas = $doc->getElementsByTagName('meta');
        for ($i = 0; $i < $metas->length; $i++) {
            $meta = $metas->item($i);
            if ($meta->getAttribute('property') == 'og:title') {
                $title = $meta->getAttribute('content');
            }
            if ($meta->getAttribute('property') == 'og:image') {
                $image = $meta->getAttribute('content');
            }
        }

        return [
            'title' => $title,
            'width' => 480,
            'height' => 270,
            'author_name' => 'Srf',
            'thumbnail_url' => $image,
            'type' => 'video'
        ];
    }
}
