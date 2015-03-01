<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Subtitles\Subtitle;

/**
 * Parses subtitles names from Addic7ed
 *
 * Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com.srt
 * Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com.srt
 * Allegiance - 01x04 - Chasing Ghosts.LOL.French.C.updated.Addic7ed.com
 */
class Addic7edParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Subtitle( $releaseName );
        $release->source = 'web-dl';
        $release->language = 'en';
        $release->author = 'addic7ed';
        preg_match( '//', $releaseName, $matches );

        return $release;
    }
}
