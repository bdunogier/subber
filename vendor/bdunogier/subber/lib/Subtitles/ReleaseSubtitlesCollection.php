<?php
namespace BD\Subber\Subtitles;

use Exception;

/**
 * Contains subtitles for a release file.
 */
class ReleaseSubtitlesCollection
{
    /** @var Subtitle[] */
    private $incompatible;

    /** @var Subtitle[] */
    private $compatible;

    /**
     * @param Subtitle[] $acceptableSubtitles
     * @param Subtitle[] $unacceptableSubtitles
     */
    public function __construct( array $compatible, array $incompatible )
    {
        $this->compatible = $compatible;
        $this->incompatible = $incompatible;
    }

    /**
     * @return bool
     */
    public function hasBestSubtitle()
    {
        return count( $this->compatible ) > 0;
    }

    /**
     * @return Subtitle
     * @throws \Exception if there is no Best Subtitle
     */
    public function getBestSubtitle()
    {
        if ( !$this->hasBestSubtitle() )
        {
            throw new Exception( "No acceptable subtitles, no best subtitle" );
        }

        return $this->compatible[0];
    }

    /**
     * @return Subtitle[]
     */
    public function getCompatibleSubtitles()
    {
        return $this->compatible;
    }

    /**
     * @return Subtitle[]
     */
    public function getIncompatibleSubtitles()
    {
        return $this->incompatible;
    }
}
