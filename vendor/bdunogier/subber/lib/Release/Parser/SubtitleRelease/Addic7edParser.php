<?php
namespace BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\ReleaseParser;
use BD\Subber\Release\Parser\ReleaseParserException;
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
        $release = new Subtitle( ['name' => $releaseName, 'author' => 'addic7ed'] );
        $releaseParts = explode( '.', strtolower( $releaseName ) );

        if ( in_array( $releaseParts[count($releaseParts) - 1], ['srt', 'ass'] ) ) {
            $release->subtitleFormat = array_pop( $releaseParts );
        }

        // addic7ed.com
        if ( array_pop( $releaseParts ) != 'com' || array_pop( $releaseParts ) != 'addic7ed' )
            throw new ReleaseParserException( $releaseName, "addic7ed.com string not found" );

        // orig or updated
        $status = array_pop( $releaseParts );
        if ( !in_array( $status, ['orig', 'updated'] ) ) {
            throw new ReleaseParserException( $releaseName, "$status isn't a valid status" );
        }

        // C thing in the release name
        $next = array_pop( $releaseParts );
        if ( $next == 'c' )
        {
            $next = array_pop( $releaseParts );
        }

        // can be hearing impaired, or language
        if ( $next == 'hi' )
        {
            $release->isHearingImpaired = true;
            $next = array_pop( $releaseParts );
        }

        // language
        if ( in_array( $next, ['english', 'french'] ) ) {
            $release->language = $this->getLanguageCode( $next );
            $next = array_pop( $releaseParts );
        }

        // strings separated by dashes (possibly)
        $next = str_replace( 'web-dl', 'webdl', $next );
        $parts = explode( '-', $next );
        foreach ($parts as $part) {
            if ( $part == 'webdl' ) {
                $release->source = 'web-dl';
            } else if ( $part == 'repack' ) {
                $release->isRepack = true;
            } else if ($part == 'proper') {
                $release->isProper = true;
            } else if ($part == 'translate') {
                // we don't care
            } else {
                if ( isset( $release->group ) ) {
                    $release->group = [$release->group, $part];
                } else {
                    $release->group = $part;
                }
            }
        }

        return $release;
    }

    private function getLanguageCode( $languageString )
    {
        $map = ['english' => 'en', 'french' => 'fr'];
        return isset( $map[$languageString] ) ? $map[$languageString] : null;
    }
}
