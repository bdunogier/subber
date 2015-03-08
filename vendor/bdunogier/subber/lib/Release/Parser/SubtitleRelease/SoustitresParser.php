<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Release\Parser\VideoReleaseParser;
use BD\Subber\Subtitles\Subtitle;

/**
 * Parses subtitle releases from sous-titres.eu
 */
class SoustitresParser implements ReleaseParser
{
    /** @var \BD\Subber\Release\Parser\ReleaseParser */
    private $episodeReleaseParser;

    public function __construct( ReleaseParser $episodeReleaseParser )
    {
        $this->episodeReleaseParser = $episodeReleaseParser;
    }

    /**
     * @param string $releaseName
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Subtitle( ['name' => $releaseName, 'author' => 'soustitres'] );
        $releaseName = strtolower( $releaseName );

        // ass/srt
        $extension = pathinfo( $releaseName, PATHINFO_EXTENSION );
        if ( in_array( $extension, ['srt', 'ass'] ) ) {
            $release->subtitleFormat = $extension;
            $releaseName = pathinfo( $releaseName, PATHINFO_FILENAME );
        }

        // episode release format (dvdrip group)
        if ( preg_match( '/^(.*)\-([a-z0-9]+)$/', $releaseName, $m ) ) {
            $episodeRelease = $this->episodeReleaseParser->parseReleaseName( $releaseName );
            $release->group = $episodeRelease->group;
            $release->source = $episodeRelease->source;
            $release->resolution = $episodeRelease->resolution;
            $release->format = $episodeRelease->format;
            return $release;
        }

        $releaseParts = explode( '.', $releaseName );

        // can be tag/notag or language
        $next = array_pop( $releaseParts );
        if ( in_array( $next, ['tag', 'notag' ] ) ) {
            if ( $next === 'tag' ) {
                $release->hasTags = true;
            }
            $next = array_pop( $releaseParts );
        }
        $release->language = $this->fixupLanguage( $next );

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

    private function fixupLanguage( $next )
    {
        return str_replace( [ 'vf', 'vo' ], [ 'fr', 'en' ], $next );
    }
}
