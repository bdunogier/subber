<?php
namespace BD\Subber\ReleaseSubtitles;

use Exception;

/**
 * Contains subtitles for a release file.
 */
class Index
{
    /** @var \BD\Subber\Subtitles\Subtitle[] */
    private $incompatible;

    /** @var \BD\Subber\Subtitles\Subtitle[] */
    private $compatible;

    /**
     * @param \BD\Subber\Subtitles\Subtitle[] $acceptableSubtitles
     * @param \BD\Subber\Subtitles\Subtitle[] $unacceptableSubtitles
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
     * @return \BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle
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
     * @return \BD\Subber\Subtitles\Subtitle[]
     */
    public function getCompatibleSubtitles()
    {
        return $this->compatible;
    }

    /**
     * @return \BD\Subber\Subtitles\Subtitle[]
     */
    public function getIncompatibleSubtitles()
    {
        return $this->incompatible;
    }
}
