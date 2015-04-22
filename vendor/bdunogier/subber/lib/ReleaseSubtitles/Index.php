<?php
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use Exception;

/**
 * Contains subtitles for a release file.
 */
class Index
{
    /** @var \BD\Subber\Release\Release */
    private $release;

    /** @var \BD\Subber\ReleaseSubtitles\TestedSubtitle[] */
    private $incompatible;

    /** @var \BD\Subber\ReleaseSubtitles\TestedSubtitle[] */
    private $compatible;

    /**
     * @param \BD\Subber\Release\Release $release
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     */
    public function __construct( Release $release, array $subtitles = null )
    {
        if ( is_array( $subtitles ) ) {
            foreach ( $subtitles as $subtitle ) {
                $this->addSubtitle( $subtitle );
            }
        }

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
     * @return \BD\Subber\ReleaseSubtitles\TestedSubtitle
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

    public function addSubtitle( TestedSubtitle $subtitle )
    {
        if ( $subtitle->isCompatible() ) {
            $this->compatible[] = $subtitle;
            $this->sort( $this->compatible );
        } else {
            $this->incompatible[] = $subtitle;
            $this->sort( $this->incompatible);
        }
    }

    private function sortSubtitlesCallback( TestedSubtitle $a, TestedSubtitle $b )
    {
        if ( $a->getRating() > $b->getRating() )
            return -1;
        if ( $a->getRating() < $b->getRating() )
            return 1;
        return 0;
    }

    private function sort( &$array )
    {
        @usort( $array, [$this, 'sortSubtitlesCallback'] );
    }
}
