<?php
namespace BD\Subber\Release;

class ReleaseObject implements Release
{
    /**
     * The release's name, complete
     * @var string
     */
    protected $name;

    /**
     * dimension, killers, ...
     * @var string
     */
    protected $group;

    /**
     * hdtv, web-dl, bluray
     * @var string
     */
    protected $source;

    /**
     * 720p, 1080p
     * @var string
     */
    protected $resolution;

    /**
     * x264
     * @var string
     */
    protected $format;

    /**
     * @var bool
     */
    protected $isProper;

    /**
     * @var bool
     */
    protected $isRepack;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function __construct( array $properties = [] )
    {
        foreach ( $properties as $property => $value )
        {
            $this->$property = $value;
        }
    }

    /**
     * @param string $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isRepack()
    {
        return $this->isRepack;
    }

    /**
     * @param boolean $isRepack
     */
    public function setIsRepack( $isRepack )
    {
        $this->isRepack = $isRepack;
    }

    /**
     * @return boolean
     */
    public function isProper()
    {
        return $this->isProper;
    }

    /**
     * @param boolean $isProper
     */
    public function setIsProper( $isProper )
    {
        $this->isProper = $isProper;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource( $source )
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param string $resolution
     */
    public function setResolution( $resolution )
    {
        $this->resolution = $resolution;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat( $format )
    {
        $this->format = $format;
    }

    /**
     * @param string $group
     */
    public function setGroup( $group )
    {
        $this->group = $group;
    }

    public function toArray()
    {
        return get_object_vars( $this );
    }
}
