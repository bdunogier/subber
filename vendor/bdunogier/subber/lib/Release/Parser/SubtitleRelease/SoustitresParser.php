<?php

namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;

/**
 * Parses subtitle releases from sous-titres.eu.
 */
class SoustitresParser implements ReleaseParser
{
    /** @var \BD\Subber\Release\Parser\ReleaseParser */
    private $episodeReleaseParser;

    public function __construct(ReleaseParser $episodeReleaseParser)
    {
        $this->episodeReleaseParser = $episodeReleaseParser;
    }

    /**
     * @param string $releaseName
     *
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName($releaseName)
    {
        $release = new SubtitleObject(['name' => $releaseName, 'author' => 'soustitres']);
        $releaseName = strtolower($releaseName);

        // ass/srt
        $extension = pathinfo($releaseName, PATHINFO_EXTENSION);
        if (in_array($extension, ['srt', 'ass'])) {
            $release->setSubtitleFormat($extension);
            $releaseName = pathinfo($releaseName, PATHINFO_FILENAME);
        }

        // episode release format (dvdrip group)
        if (preg_match('/^(.*)\-([a-z0-9]+)$/', $releaseName, $m)) {
            $episodeRelease = $this->episodeReleaseParser->parseReleaseName($releaseName);
            $release->setGroup($episodeRelease->getGroup());
            $release->setSource($episodeRelease->getSource());
            $release->setResolution($episodeRelease->getResolution());
            $release->setFormat($episodeRelease->getFormat());

            return $release;
        }

        $releaseParts = explode('.', $releaseName);

        // can be tag/notag or language
        $next = array_pop($releaseParts);
        if (in_array($next, ['tag', 'notag'])) {
            if ($next === 'tag') {
                $release->setHasTags(true);
            }
            $next = array_pop($releaseParts);
        }
        $release->setLanguage($this->fixupLanguage($next));

        $next = array_pop($releaseParts);
        if ($next == 'web-dl') {
            $release->setSource('web-dl');
        } else {
            $release->setGroup($next);
        }

        do {
            $next = array_pop($releaseParts);
            if (!in_array($next, ['720p', '1080p'])) {
                break;
            }
            if ($release->getResolution() === null) {
                $release->setResolution($next);
            } elseif (is_string($release->getResolution())) {
                $release->setResolution([$release->getResolution(), $next]);
            } else {
                $resolutions = $release->getResolution();
                $resolutions[] = $next;
                $release->setResolution($next);
            }
        } while (true);

        // resolve source if not given
        if ($release->getSource() === null) {
            $release->setSource('hdtv');
        }

        return $release;
    }

    private function fixupLanguage($next)
    {
        return str_replace(['vf', 'vo'], ['fr', 'en'], $next);
    }
}
