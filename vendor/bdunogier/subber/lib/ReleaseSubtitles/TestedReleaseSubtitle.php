<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\Subtitles\Subtitle;

/**
 * A Subtitle tested for Compatibility against a Release
 */
class TestedReleaseSubtitle extends Subtitle
{
    /** @var \BD\Subber\Release\Release */
    protected $release;

    /** @var string */
    protected $compatibility;

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

    public function setIncompatible()
    {
        $this->compatibility = self::INCOMPATIBLE;
    }

    public function isIncompatible()
    {
        return $this->compatibility === self::INCOMPATIBLE;
    }
}
