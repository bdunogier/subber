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
        $release = new Subtitle( ['name' => $releaseName] );
        $release->author = 'addic7ed';

        if ( !preg_match( '/([^\.]+)\.(english|french)\.c\.(updated|orig)\.addic7ed\.com$/i', strtolower( $releaseName ), $matches ) )
            throw new \InvalidArgumentException( "Unable to parse $releaseName" );

        switch ( $matches[2] )
        {
            case 'english': $release->language = 'en'; break;
            case 'french': $release->language = 'fr'; break;
        }

        if ( $matches[1] == 'killers' || $matches[1] == 'killers-translate')
        {
            $release->group = 'killers';
        }

        if ( $matches[1] == 'web-dl' )
        {
            $release->source = 'web-dl';
        }

        if ( $matches[1] == 'web-dl-bs' )
        {
            $release->source = 'web-dl';
            $release->group = 'bs';
        }

        return $release;
    }
}
