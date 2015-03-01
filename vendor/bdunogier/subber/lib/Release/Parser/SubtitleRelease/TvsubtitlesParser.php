<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Subtitle;

/**
 * Parses subtitle releases from tvsubtitles.net
 *
 *
 */
class TvsubtitlesParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Subtitle( ['name' => $releaseName, 'author' => 'tvsubtitles'] );
        $releaseParts = explode( '.', strtolower( $releaseName ) );

        $release->subtitleFormat = array_pop( $releaseParts );
        $release->language = array_pop( $releaseParts );

        $release->group = array_pop( $releaseParts );
        if ( ( $next = array_pop( $releaseParts ) ) === 'lol+720p' ) {
            $release->group = [$release->group, 'lol'];
            $next = array_pop( $releaseParts );
        }

        $release->source = $next;

        return $release;
    }
}
