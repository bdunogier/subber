<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Episode;

class Episode
{
    public function __construct( array $properties = [] )
    {
        foreach ( $properties as $property => $value )
        {
            if ( !property_exists( $this, $property ) )
                throw new \InvalidArgumentException( "Unknown property $property in class " . __CLASS__ );
            $this->$property = $value;
        }
    }

    public $showTitle;

    public $seasonNumber;

    public $episodeNumber;

    public $episodeName;

    public $episodeTitle;

    public $plot;

    public $showPoster;

    public $showBanner;

    public $episodeThumb;
}
