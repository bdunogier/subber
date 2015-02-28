<?php
namespace BD\Subber\Release;

class Release
{
    /**
     * The release's name, complete
     * @var string
     */
    public $name;

    /**
     * dimension, killers, ...
     * @var string
     */
    public $group;

    /**
     * hdtv, web-dl, bluray
     * @var string
     */
    public $source;

    /**
     * 720p, 1080p
     * @var string
     */
    public $resolution;

    /**
     * x264
     * @var string
     */
    public $format;
}
