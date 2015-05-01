<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;

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
        $release = new SubtitleObject( ['name' => $releaseName, 'author' => 'betaseries', 'language' => 'fr'] );
        $releaseParts = explode( '.', strtolower( $releaseName ) );

        $release->setResolution( array_pop( $releaseParts ) );

        $next = array_pop( $releaseParts );
        if ( in_array( $next, ['720p', '1080p'] ) )
        {
            $release->setResolution( $next );
            $next = array_pop( $releaseParts );
        }

        if ( $next === 'web-dl' ) {
            $release->setSource( 'web-dl' );
            $next = array_pop( $releaseParts );
        } else {
            $release->setGroup( 'lol' );
        }

        if ( $next === 'lol' ) {
            $release->setSource( 'hdtv' );
        }

        if ( $release->getGroup() === 'lol' ) {
            $release->setSource( 'hdtv' );
        }

        return $release;
    }
}
