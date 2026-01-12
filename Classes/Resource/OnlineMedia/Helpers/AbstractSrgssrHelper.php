<?php

namespace Saccas\Srgssr\Resource\OnlineMedia\Helpers;

use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\AbstractOnlineMediaHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractSrgssrHelper extends AbstractOnlineMediaHelper
{
    protected string $channelName = '';

    protected string $siteHostname = '';

    protected $extension = '';

    public function getPublicUrl(File $file): string
    {
        $videoId = $this->getOnlineMediaId($file);
        return sprintf('https://' . $this->siteHostname . '/play/tv/video/?id=%s', $videoId);
    }

    /**
     * Get the local absolute file path to preview image
     */
    public function getPreviewImage(File $file): string
    {
        $videoId = $this->getOnlineMediaId($file);
        $temporaryFileName = $this->getTempFolderPath() . $this->channelName . '_' . md5($videoId) . '.jpg';

        if (!file_exists($temporaryFileName)) {
            $mediaData = $this->getMediaMetadata($videoId);

            $previewImage = false;
            if (isset($mediaData['chapterList'][0]['imageUrl'])) {
                $previewImage = GeneralUtility::getUrl($mediaData['chapterList'][0]['imageUrl'] . '/scale/width/1024');
            }

            if ($previewImage === false && isset($mediaData['episode']['imageUrl'])) {
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
     */
    public function transformUrlToFile($url, Folder $targetFolder): ?File
    {
        $host = parse_url($url, PHP_URL_HOST);
        if ($host !== $this->siteHostname) {
            return null;
        }

        $urlQueryParams = parse_url($url, PHP_URL_QUERY);
        if ($urlQueryParams === null) {
            return null;
        }

        parse_str($urlQueryParams, $params);
        if (!empty($params['urn'])) {
            $mediaId = substr($params['urn'], strrpos($params['urn'], ':') + 1);
            return $this->transformMediaIdToFile($mediaId, $targetFolder, $this->extension);
        }

        return null;
    }

    /**
     * Transform mediaId to File
     * Taken from typo3/sysext/core/Classes/Resource/OnlineMedia/Helpers/AbstractOEmbedHelper.php
     */
    protected function transformMediaIdToFile(string $mediaId, Folder $targetFolder, string $fileExtension): File
    {
        $file = $this->findExistingFileByOnlineMediaId($mediaId, $targetFolder, $fileExtension);
        if ($file === null) {
            $fileName = $mediaId . '.' . $fileExtension;

            $mediaData = $this->getMediaMetadata($mediaId);
            if (!empty($mediaData && !empty($mediaData['chapterList'][0]['title']))) {
                $fileName = $mediaData['chapterList'][0]['title'] . '.' . $fileExtension;
            }
            $file = $this->createNewFile($targetFolder, $fileName, $mediaId);
        }

        return $file;
    }

    /**
     * Get oEmbed data url
     */
    protected function getMediaMetadataUrl(string $mediaId, string $format = 'json'): string
    {
        return sprintf('https://il.srgssr.ch/integrationlayer/2.0/mediaComposition/byUrn/urn:' . $this->channelName . ':video:%s.' . $format, $mediaId);
    }

    /**
     * Get MediaMetadata data
     */
    protected function getMediaMetadata(string $mediaId): ?array
    {
        $mediaMetaData = GeneralUtility::getUrl($this->getMediaMetadataUrl($mediaId));
        if (is_string($mediaMetaData)) {
            return json_decode($mediaMetaData, true);
        }

        return null;
    }

    /**
     * Get meta data for OnlineMedia item
     *
     * @return ?array{title: string, description: string, width: int, height: int, thumbnail_url: string, type: string}
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
                'type' => 'video',
            ];
        }

        return $metadata;
    }
}
