<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Subtitle;

/**
 * Parses subtitles names from betaseries (as a subtitles source, not a scrapped site)
 */
class BetaseriesParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Subtitle( ['name' => $releaseName, 'author' => 'betaseries', 'language' => 'fr'] );
        $releaseParts = explode( '.', strtolower( $releaseName ) );

        $release->resolution = array_pop( $releaseParts );

        $next = array_pop( $releaseParts );
        if ( in_array( $next, ['720p', '1080p'] ) )
        {
            $release->resolution = $next;
            $next = array_pop( $releaseParts );
        }

        if ( $next === 'web-dl' ) {
            $release->source = 'web-dl';
            $next = array_pop( $releaseParts );
        } else {
            $release->group = 'lol';
        }

        if ( $next === 'lol' ) {
            $release->source = 'hdtv';
        }

        if ( $release->group === 'lol' ) {
            $release->source = 'hdtv';
        }

        return $release;
    }
}
