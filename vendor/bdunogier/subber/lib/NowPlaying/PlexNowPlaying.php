<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\NowPlaying;

class PlexNowPlaying implements NowPlaying
{
    /**
     * @var
     */
    private $plexUri;

    public function __construct( $plexUri )
    {
        $this->plexUri = $plexUri;
    }

    /**
     * @return string
     */
    public function getNowPlayingFilePath()
    {
        $xmlString = file_get_contents( $this->plexUri . '/status/sessions' );

        $xml = simplexml_load_string( $xmlString );
        $parts = $xml->xpath( '//Part' );
        if ( !isset( $parts[0] ) ) {
            return null;
        }
        foreach ($parts[0]->attributes() as $attributeName => $attributeValue) {
            if ($attributeName == 'file') {
                return $attributeValue;
            }
        }
        return null;
    }
}
