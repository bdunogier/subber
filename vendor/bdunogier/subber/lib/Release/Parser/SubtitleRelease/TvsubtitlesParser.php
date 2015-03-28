<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;

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
        $release = new SubtitleObject( ['name' => $releaseName, 'author' => 'tvsubtitles'] );
        $releaseName = $this->normalize( $releaseName );
        $releaseParts = explode( '.', $releaseName );

        $release->setSubtitleFormat( array_pop( $releaseParts ) );
        $release->setLanguage( array_pop( $releaseParts ) );

        while ( $next = array_pop( $releaseParts ) ) {
            if (in_array( $next, ['720p', '1080p'] )) {
                $release->setResolution( $next );
            } else if ( $next == 'repack' ) {
                $release->setIsRepack( true );
            } else if ( $next == 'proper' ) {
                $release->setIsProper( true );
            } else if ( $next == 'hdtv' ) {
                $release->setSource ( 'hdtv' );
            } else {
                if ( $release->getGroup() === null ) {
                    if ( is_array( $release->getGroup() ) ) {
                        $groups = $release->getGroup();
                        $groups[] = $next;
                        $release->setGroup( $groups );
                    } else {
                        $release->setGroup( [$release->getGroup(), $next] );
                    }
                } else {
                    $release->setGroup( $next );
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
