<?php
namespace Saccas\Srgssr\Resource\OnlineMedia\Helpers;

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

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOnlineMediaHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractSrgssrHelper extends AbstractOnlineMediaHelper
{
    /**
     * @var string
     */
    protected $channelName = '';

    /**
     * @var string
     */
    protected $siteHostname = '';

    /**
     * @var $extension = '';
     */
    protected $extension = '';

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
        return sprintf('https://' . $this->siteHostname . '/play/tv//video/?id=%s', $videoId);
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
        $temporaryFileName = $this->getTempFolderPath() . $this->channelName . '_' . md5($videoId) . '.jpg';

        if (!file_exists($temporaryFileName)) {
            $mediaData = $this->getMediaMetadata($videoId);
            $previewImage = GeneralUtility::getUrl($mediaData['chapterList'][0]['imageUrl'] . '/scale/width/1024');

            if ($previewImage === false) {
                $previewImage = GeneralUtility::getUrl($mediaData['episode']['imageUrl'] . '/scale/width/1024');
            }

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
        $host = parse_url($url, PHP_URL_HOST);
        if ($host !== $this->siteHostname) {
            return null;
        }

        $urlQueryParams = parse_url($url, PHP_URL_QUERY);
        parse_str($urlQueryParams, $params);

        if (empty($params['id'])) {
            return null;
        }

        $videoId = $params['id'];

        return $this->transformMediaIdToFile($videoId, $targetFolder, $this->extension);
    }

    /**
     * Transform mediaId to File
     * Taken from typo3/sysext/core/Classes/Resource/OnlineMedia/Helpers/AbstractOEmbedHelper.php
     *
     * @param string $mediaId
     * @param Folder $targetFolder
     * @param string $fileExtension
     * @return File
     */
    protected function transformMediaIdToFile($mediaId, Folder $targetFolder, $fileExtension): File
    {
        $file = $this->findExistingFileByOnlineMediaId($mediaId, $targetFolder, $fileExtension);

        // no existing file create new
        if ($file === null) {
            $mediaData = $this->getMediaMetadata($mediaId);
            if (!empty($mediaData && !empty($mediaData['chapterList'][0]['title']))) {
                $fileName = $mediaData['chapterList'][0]['title'] . '.' . $fileExtension;
            } else {
                $fileName = $mediaId . '.' . $fileExtension;
            }
            $file = $this->createNewFile($targetFolder, $fileName, $mediaId);
        }
        return $file;
    }

    /**
     * Get oEmbed data url
     *
     * @param string $mediaId
     * @param string $format
     * @return string
     */
    protected function getMediaMetadataUrl($mediaId, $format = 'json'): string
    {
        return sprintf('https://il.srgssr.ch/integrationlayer/2.0/mediaComposition/byUrn/urn:' . $this->channelName . ':video:%s.' . $format, $mediaId);
    }

    /**
     * Get MediaMetadata data
     *
     * @param string $mediaId
     * @return array|null
     */
    protected function getMediaMetadata(string $mediaId): ?array
    {
        $mediaMetaData = GeneralUtility::getUrl(
            $this->getMediaMetadataUrl($mediaId)
        );
        if (is_string($mediaMetaData)) {
            return json_decode($mediaMetaData, true);
        }
        return null;
    }

    /**
     * Get meta data for OnlineMedia item
     *
     * @param File $file
     * @return array with metadata
     */
    public function getMetaData(File $file): ?array
    {
        $metadata = [];
        $onlineMediaId = $this->getOnlineMediaId($file);
        if (empty($onlineMediaId)) {
            return null;
        }
        $mediaData = $this->getMediaMetadata($onlineMediaId);
        if ($mediaData) {
            $metadata = [
                'title' => $mediaData['chapterList'][0]['title'],
                'description' => $mediaData['chapterList'][0]['description'],
                'width' => 1024,
                'height' => 576,
                'thumbnail_url' => $mediaData['chapterList'][0]['imageUrl'] . '/scale/width/1024',
                'type' => 'video'
            ];
        }
        return $metadata;
    }
}
