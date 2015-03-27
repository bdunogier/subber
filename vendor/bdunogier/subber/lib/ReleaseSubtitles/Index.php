<?php
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use Exception;

/**
 * Contains subtitles for a release file.
 */
class Index
{
    /** @var Release */
    private $release;

    /** @var \BD\Subber\Subtitles\Subtitle[] */
    private $incompatible;

    /** @var \BD\Subber\Subtitles\Subtitle[] */
    private $compatible;

    /**
     * @param \BD\Subber\Release\Release $release
     * @param \BD\Subber\Subtitles\Subtitle[] $acceptableSubtitles
     * @param \BD\Subber\Subtitles\Subtitle[] $unacceptableSubtitles
     */
    public function __construct( Release $release, array $compatible, array $incompatible )
    {
        $this->compatible = $compatible;
        $this->incompatible = $incompatible;
        $this->release = $release;
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

    /**
     * @return Release
     */
    public function getRelease()
    {
        return $this->release;
    }
}
