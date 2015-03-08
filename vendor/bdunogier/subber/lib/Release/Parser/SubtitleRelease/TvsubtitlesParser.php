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
        $releaseName = $this->normalize( $releaseName );
        $releaseParts = explode( '.', $releaseName );

        $release->subtitleFormat = array_pop( $releaseParts );
        $release->language = array_pop( $releaseParts );

        while ( $next = array_pop( $releaseParts ) ) {
            if (in_array( $next, ['720p', '1080p'] )) {
                $release->resolution = $next;
            } else if ( $next == 'hdtv' ) {
                $release->source = 'hdtv';
            } else {
                if ( isset( $release->group ) ) {
                    $release->group = [$release->group, $next];
                } else {
                    $release->group = $next;
                }
            }
        }

        return $release;
    }

    private function normalize( $releaseName )
    {
        $releaseName = strtolower( $releaseName );
        $releaseName = $this->cutReleaseName( $releaseName );
        return str_replace( [' ', '+'], '.', $releaseName );
    }

    private function cutReleaseName( $releaseName )
    {
        $releaseNamePiece = '';
        foreach ( ['720p', 'hdtv'] as $cutWord ) {
            $newReleaseNamePiece = strstr( $releaseName, $cutWord );
            if (strlen($newReleaseNamePiece) > strlen($releaseNamePiece)) {
                $releaseNamePiece = $newReleaseNamePiece;
            }
        }

        return $releaseNamePiece ?: $releaseName;
    }
}
