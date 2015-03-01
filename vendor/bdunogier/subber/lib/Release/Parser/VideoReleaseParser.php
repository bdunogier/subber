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

        list( $releaseName, $release->group ) = explode( '-', $releaseName );
        $releaseNameParts = explode( '.', str_replace( ' ', '.', $releaseName ) );

        $release->format = array_pop( $releaseNameParts );
        $release->source = array_pop( $releaseNameParts );
        $release->resolution = array_pop( $releaseNameParts );

        return $release;
    }
}
