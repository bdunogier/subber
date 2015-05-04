<?php

namespace BD\Subber\Release\Parser;

use BD\Subber\Release\ReleaseObject;

/**
 * Parses a downloaded file's name into a DownloadedEpisode object.
 */
class VideoReleaseParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     *
     * @return \BD\Subber\Release\Release
     */
    public function parseReleaseName($releaseName)
    {
        $properties = [];

        $releaseName = strtolower($releaseName);
        $properties['name'] = $releaseName;

        $releaseParts = explode('-', $releaseName);
        $properties['group'] = array_pop($releaseParts);

        $properties += [
            'format' => $this->parseFormat($releaseName),
            'source' => $this->parseSource($releaseName),
            'resolution' => $this->parseResolution($releaseName),
            'isProper' => $this->parseProper($releaseName),
            'isRepack' => $this->parseRepack($releaseName),
        ];

        return new ReleaseObject($properties);
    }

    public function parseFormat($releaseName)
    {
        if (strstr($releaseName, 'x264') || strstr($releaseName, 'h 264') || strstr($releaseName, 'h.264')) {
            return 'x264';
        } elseif (strstr($releaseName, 'xvid')) {
            return 'xvid';
        }
    }

    public function parseSource($releaseName)
    {
        if (strstr($releaseName, 'webrip')) {
            return 'webrip';
        } elseif (strstr($releaseName, 'web-dl')) {
            return 'web-dl';
        } elseif (strstr($releaseName, 'hdtv')) {
            return 'hdtv';
        } elseif (strstr($releaseName, 'bdrip')) {
            return 'bdrip';
        } elseif (strstr($releaseName, 'dvdrip')) {
            return 'dvdrip';
        }
    }

    public function parseResolution($releaseName)
    {
        if (strstr($releaseName, '720p')) {
            return '720p';
        } elseif (strstr($releaseName, '1080p')) {
            return '1080p';
        }
    }

    public function parseProper($releaseName)
    {
        return strstr($releaseName, 'proper') !== false;
    }

    public function parseRepack($releaseName)
    {
        return strstr($releaseName, 'repack') !== false;
    }
}
