<?php
namespace BD\Subber\Subtitles;

use Exception;

/**
 * Collection of Subtitles for an Episode
 */
class ReleaseSubtitlesCollection
{
    /** @var Subtitle[] */
    private $acceptableSubtitles;

    /** @var Subtitle[] */
    private $unacceptableSubtitles;

    /**
     * @param Subtitle[] $acceptableSubtitles
     * @param Subtitle[] $unacceptableSubtitles
     */
    public function __construct( array $acceptableSubtitles, array $unacceptableSubtitles )
    {
        $this->acceptableSubtitles = $acceptableSubtitles;
        $this->unacceptableSubtitles = $unacceptableSubtitles;
    }

    /**
     * @return bool
     */
    public function hasBestSubtitle()
    {
        return ( count( $this->acceptableSubtitles ) > 0 );
    }

    /**
     * @return Subtitle
     */
    public function getBestSubtitle()
    {
        if ( !count( $this->acceptableSubtitles ) > 0 )
        {
            throw new Exception( "No acceptable subtitles, no best subtitle" );
        }

        return $this->acceptableSubtitles[0];
    }

    /**
     * @return Subtitle[]
     */
    public function getAcceptableSubtitles()
    {
        return $this->acceptableSubtitles;
    }

    /**
     * @return Subtitle[]
     */
    public function getUnacceptableSubtitles()
    {
        return $this->unacceptableSubtitles;
    }
}
