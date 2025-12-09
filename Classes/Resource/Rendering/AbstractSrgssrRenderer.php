<?php

namespace Saccas\Srgssr\Resource\Rendering;

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperInterface;
use TYPO3\CMS\Core\Resource\OnlineMedia\Helpers\OnlineMediaHelperRegistry;
use TYPO3\CMS\Core\Resource\Rendering\FileRendererInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

abstract class AbstractSrgssrRenderer implements FileRendererInterface
{
    protected null|bool|OnlineMediaHelperInterface $onlineMediaHelper = null;

    /**
     * Returns the priority of the renderer
     * This way it is possible to define/overrule a renderer
     * for a specific file type/context.
     * For example create a video renderer for a certain storage/driver type.
     * Should be between 1 and 100, 100 is more important than 1
     *
     * @return int
     */
    public function getPriority(): int
    {
        return 1;
    }

    /**
     * Get online media helper
     */
    protected function getOnlineMediaHelper(FileInterface $file): bool|OnlineMediaHelperInterface
    {
        if ($this->onlineMediaHelper === null) {
            $orgFile = $file;
            if ($orgFile instanceof FileReference) {
                $orgFile = $orgFile->getOriginalFile();
            }

            if ($orgFile instanceof File) {
                $this->onlineMediaHelper = GeneralUtility::makeInstance(OnlineMediaHelperRegistry::class)->getOnlineMediaHelper($orgFile);
            } else {
                $this->onlineMediaHelper = false;
            }
        }

        return $this->onlineMediaHelper;
    }

    /**
     * Render for given File(Reference) html output
     *
     * @param int|string $width TYPO3 known format; examples: 220, 200m or 200c
     * @param int|string $height TYPO3 known format; examples: 220, 200m or 200c
     */
    public function render(FileInterface $file, $width, $height, array $options = []): string
    {
        $options = $this->collectOptions($options, $file);
        $src = $this->createUrl($options, $file);
        $attributes = $this->collectIframeAttributes($width, $height, $options);

        return sprintf(
            '<iframe src="%s"%s></iframe>',
            $src,
            empty($attributes) ? '' : ' ' . implode(' ', $attributes)
        );
    }

    /**
     * @param array $options
     * @param FileInterface $file
     * @return array
     */
    protected function collectOptions(array $options, FileInterface $file): array
    {
        if (!isset($options['autoplay']) && $file instanceof FileReference) {
            $autoplay = $file->getProperty('autoplay');
            if ($autoplay !== null) {
                $options['autoplay'] = $autoplay;
            }
        }

        if (!isset($options['allow'])) {
            $options['allow'] = 'fullscreen';
            if (!empty($options['autoplay'])) {
                $options['allow'] = 'autoplay; fullscreen';
            }
        }

        return $options;
    }

    public function createUrl(array $options, FileInterface $file): ?string
    {
        $videoId = $this->getVideoIdFromFile($file);

        if (empty($videoId)) {
            return null;
        }

        $urlParams = [];
        if (!empty($options['autoplay'])) {
            $urlParams[] = 'autoplay=true';
        }

        return sprintf(
            '//tp.srgssr.ch/p/' . $this->channelName . '/embed?urn=urn:' . $this->channelName . ':video:%s&%s',
            $videoId,
            implode('&amp;', $urlParams)
        );
    }

    /**
     * @param FileInterface $file
     * @return string
     */
    protected function getVideoIdFromFile(FileInterface $file): string
    {
        if ($file instanceof FileReference) {
            $orgFile = $file->getOriginalFile();
        } else {
            $orgFile = $file;
        }

        return $this->getOnlineMediaHelper($file)->getOnlineMediaId($orgFile);
    }

    protected function collectIframeAttributes(int|string $width, int|string $height, array $options): array
    {
        $attributes = ['allowfullscreen'];
        if (isset($options['additionalAttributes']) && is_array($options['additionalAttributes'])) {
            $attributes[] = GeneralUtility::implodeAttributes($options['additionalAttributes'], true, true);
        }

        if (isset($options['data']) && is_array($options['data'])) {
            array_walk($options['data'], function (&$value, $key) {
                $value = 'data-' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
            });
            $attributes[] = implode(' ', $options['data']);
        }

        if ((int)$width > 0) {
            $attributes[] = 'width="' . (int)$width . '"';
        }

        if ((int)$height > 0) {
            $attributes[] = 'height="' . (int)$height . '"';
        }

        if ($this->shouldIncludeFrameBorderAttribute()) {
            $attributes['frameborder'] = 0;
        }

        foreach (['class', 'dir', 'id', 'lang', 'style', 'title', 'accesskey', 'tabindex', 'onclick', 'poster', 'preload', 'allow'] as $key) {
            if (!empty($options[$key])) {
                $attributes[$key] = $options[$key];
            }
        }

        return $attributes;
    }

    /**
     * HTML5 deprecated the "frameborder" attribute as everything should be done via styling.
     */
    protected function shouldIncludeFrameBorderAttribute(): bool
    {
        return GeneralUtility::makeInstance(PageRenderer::class)->getDocType()->shouldIncludeFrameBorderAttribute();
    }
}
