<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Release\Episode;

class XbmcNfoParser implements EpisodeMetadataFileParser
{
    public function __construct()
    {
        // phpspec needs this...
    }

    public function parseFromEpisodeFilePath( $episodeFilePath )
    {
        $metadataFilePath = $this->replaceExtension( $episodeFilePath );

        if ( !file_exists( $metadataFilePath ) ) {
            throw new \InvalidArgumentException( "The XBMC metadata file $metadataFilePath does not exist" );
        }

        libxml_use_internal_errors( true );
        $xmlString = file_get_contents( $metadataFilePath );
        if ( ( $xml = simplexml_load_string( $xmlString ) )=== false ) {
            throw new \InvalidArgumentException( "The XBMC metadata file $metadataFilePath does not seem to be valid XML" );
        }

        $episodeRelease = new EpisodeRelease(
            [
                'showTitle' => (string)$xml->showtitle,
                'episodeTitle' => (string)$xml->title,
                'seasonNumber' => (int)$xml->season,
                'episodeNumber' => (int)$xml->episode,
                'plot' => (string)$xml->plot,
                'episodeThumb' => $this->checkEpisodeThumb( $episodeFilePath ),
                'showPoster' => $this->checkShowPoster( $episodeFilePath )
            ]
        );

        return $episodeRelease;
    }

    private function replaceExtension( $episodeFilePath, $extension = 'nfo' )
    {
        return dirname( $episodeFilePath ) . '/' . pathinfo( $episodeFilePath, PATHINFO_FILENAME ) . '.' . $extension;
    }

    private function checkEpisodeThumb( $episodeFilePath )
    {
        $thumbFilePath = $this->replaceExtension( $episodeFilePath, 'tbn' );
        return file_exists( $thumbFilePath ) ? $thumbFilePath : null;
    }

    private function checkShowPoster( $episodeFilePath )
    {
        $showDirectory = dirname( $episodeFilePath );
        $showName = pathinfo( $showDirectory, PATHINFO_FILENAME );
        $posterPath = dirname( $episodeFilePath ) . '/' . $showName . '.tbn';

        return file_exists( $posterPath ) ? $posterPath : null;
    }
}
