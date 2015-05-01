<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Subtitles\Subtitle;


/**
 * A Rated Subtitle tested for Compatibility against a Release
 */
interface TestedSubtitle extends Subtitle
{
    /** Compatibility constants */
    const COMPATIBLE = 'compatible';
    const INCOMPATIBLE = 'incompatible';
    const UNDETERMINED = 'undetermined';

    /**
     * @return string
     */
    public function getCompatibility();

    /**
     * @param string $compatibility
     */
    public function setCompatibility( $compatibility );

    public function setCompatible();

    public function setIncompatible();

    /**
     * @return bool
     */
    public function isCompatible();

    /**
     * @return int
     */
    public function getRating();

    /**
     * @param int $rating
     */
    public function setRating( $rating );
}
