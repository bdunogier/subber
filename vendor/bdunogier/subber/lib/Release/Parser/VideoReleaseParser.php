<?php
namespace BD\Subber\Release\Parser;

use BD\Subber\Release\Release;

/**
 * Parses a downloaded file's name into a DownloadedEpisode object
 */
class VideoReleaseParser implements ReleaseParser
{
    /**
     * @param string $releaseName
     * @return \BD\Subber\Release\Release
     */
    public function parseReleaseName( $releaseName )
    {
        $release = new Release();
        $releaseName = strtolower( $releaseName );
        $release->name = $releaseName;

        $releaseParts = explode( '-', $releaseName );
        $release->group = array_pop( $releaseParts );

        $release->format = $this->parseFormat( $releaseName );
        $release->source = $this->parseSource( $releaseName );
        $release->resolution = $this->parseResolution( $releaseName );
        $release->isRepack = $this->parseRepack( $releaseName );

        return $release;
    }

    public function parseFormat( $releaseName )
    {
        if ( strstr( $releaseName, 'x264' ) || strstr( $releaseName, 'h 264' ) ) {
            return 'x264';
        } elseif ( strstr( $releaseName, 'xvid' ) ) {
            return 'xvid';
        }
    }

    public function parseSource( $releaseName )
    {
        if (strstr( $releaseName, 'webrip' ) ) {
            return 'webrip';
        } elseif (strstr( $releaseName, 'web-dl' ) ) {
            return 'web-dl';
        } elseif ( strstr( $releaseName, 'hdtv' ) ) {
            return 'hdtv';
        } elseif ( strstr( $releaseName, 'bdrip' )) {
            return 'bdrip';
        } elseif ( strstr( $releaseName, 'dvdrip' ) ) {
            return 'dvdrip';
        }
    }

    public function parseResolution( $releaseName )
    {
        if ( strstr( $releaseName, '720p' ) ) {
            return '720p';
        } elseif ( strstr( $releaseName, '1080p' ) ) {
            return '1080p';
        }
    }

    public function parseRepack( $releaseName )
    {
        return strstr( $releaseName, 'repack' ) !== false;
    }
}
