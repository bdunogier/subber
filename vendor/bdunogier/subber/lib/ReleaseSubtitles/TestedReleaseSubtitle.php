<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\Subtitles\Subtitle;

/**
 * A Rated Subtitle tested for Compatibility against a Release
 */
class TestedReleaseSubtitle extends Subtitle
{
    /** @var \BD\Subber\Release\Release */
    protected $release;

    /** @var string */
    protected $compatibility;

    /** @var int */
    protected $rating;

    /**
     * Compatiblity constants
     */
    const COMPATIBLE = 'compatible';
    const INCOMPATIBLE = 'incompatible';
    const UNDETERMINED = 'undetermined';

    public function __construct( Release $release, Subtitle $subtitle, $compatibility = self::UNDETERMINED )
    {
        $this->release = $release;
        $this->compatibility = $compatibility;

        parent::__construct( get_object_vars( $subtitle ) );
    }

    /**
     * @return Release
     */
    public function getRelease()
    {
        return $this->release;
    }

    /**
     * @param Release $release
     */
    public function setRelease( $release )
    {
        $this->release = $release;
    }

    /**
     * @return string
     */
    public function getCompatibility()
    {
        return $this->compatibility;
    }

    /**
     * @param string $compatibility
     */
    public function setCompatibility( $compatibility )
    {
        $this->compatibility = $compatibility;
    }

    public function setCompatible()
    {
        $this->compatibility = self::COMPATIBLE;
    }

    public function setIncompatible()
    {
        $this->compatibility = self::INCOMPATIBLE;
    }

    public function isCompatible()
    {
        return $this->compatibility === self::COMPATIBLE || $this->compatibility === self::UNDETERMINED;
    }

    /**
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating( $rating )
    {
        $this->rating = $rating;
    }
}
