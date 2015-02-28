<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

/**
 * Matches a Subtitle against a downloaded file.
 *
 * A subtitle matches if it seems that it would fit the file in terms of source, quality, ...
 */
class Matcher
{
    /**
     * @return bool
     */
    public function matches( Subtitle $subtitle, $filename )
    {
        $filename = strtolower( $filename );
        $subtitleFilename = strtolower( $subtitle->filename );

        if ( $this->contains( $filename, 'web-dl' ) && !$this->contains( $subtitleFilename, 'web-dl' ) )
            return false;

        if ( $this->containsOneOf( $filename, ['hdtv', '720p'] ) && !$this->containsOneOf( $subtitleFilename, ['hdtv', '720p'] ) )
            return false;

        if ( $this->contains( $filename, 'dimension' ) && !$this->contains( $subtitleFilename, 'dimension' ) )
            return false;

        if ( $this->contains( $filename, 'lol' ) && !$this->contains( $subtitleFilename, 'lol' ) )
            return false;


        if ( $this->contains( $filename, '1080p' ) && !$this->contains( $subtitleFilename, '1080p' ) )
            return false;

        return true;
    }

    private function contains( $string, $substring )
    {
        return strstr( $string, $substring ) !== false;
    }

    private function containsOneOf( $string, array $substringArray )
    {
        foreach ( $substringArray as $substring )
        {
            if ( strstr( $string, $substring ) !== false )
                return true;
        }
        return false;
    }
}
