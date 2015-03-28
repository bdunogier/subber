<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;
use InvalidArgumentException;

/**
 * A Rated Subtitle tested for Compatibility against a Release
 */
class TestedSubtitleObject extends SubtitleObject implements TestedSubtitle
{
    /** @var string */
    protected $compatibility;

    /** @var int */
    protected $rating;

    public function __construct( array $properties = array() )
    {
        parent::__construct( $properties );
    }

    public function getCompatibility()
    {
        return $this->compatibility;
    }

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
        return $this->compatibility === TestedSubtitle::COMPATIBLE || $this->compatibility === TestedSubtitle::UNDETERMINED;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating( $rating )
    {
        $this->rating = $rating;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle( $subtitle )
    {
        $this->subtitle = $subtitle;
    }

    public function __call( $methodName, $arguments )
    {
        if ( method_exists( $this->subtitle, $methodName ) ) {
            return $this->subtitle->$methodName();
        }
        throw new InvalidArgumentException(
            "No such method $methodName on class " . __CLASS__ . ' or ' . get_class( $this->subtitle )
        );
    }
}
