<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Subtitle;

/**
 * Parses subtitle releases from sous-titres.eu
 */
class SoustitresParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Subtitle( ['name' => $releaseName, 'author' => 'soustitres'] );
        $releaseParts = explode( '.', strtolower( $releaseName ) );

        // ass/srt
        $release->subtitleFormat = array_pop( $releaseParts );

        // can be tag/notag or language
        $next = array_pop( $releaseParts );
        if ( in_array( $next, ['tag', 'notag' ] ) ) {
            if ( $next === 'tag' ) {
                $release->hasTags = true;
            }
            $next = array_pop( $releaseParts );
        }
        $release->language = $next;

        $next = array_pop( $releaseParts );
        if ( $next == 'web-dl' ) {
            $release->source = 'web-dl';
        } else {
            $release->group = $next;
        }

        do
        {
            $next = array_pop( $releaseParts );
            if ( !in_array( $next, ['720p', '1080p'] ) ) {
                break;
            }
            if ( $release->resolution === null ) {
                $release->resolution = $next;
            } elseif ( is_string( $release->resolution ) ) {
                $release->resolution = [$release->resolution, $next];
            } else {
                $release->resolution[] = $next;
            }
        } while ( true );

        // resolve source if not given
        if ( $release->source === null ) {
            $release->source = 'hdtv';
        }

        return $release;
    }

    /**
     * Most useful method ever
     */
    private function processLanguage( $string )
    {
        switch ( $string ) {
            case 'en': return 'en'; break;
            case 'fr': return 'fr'; break;
            default: return null;
        }
    }
}
