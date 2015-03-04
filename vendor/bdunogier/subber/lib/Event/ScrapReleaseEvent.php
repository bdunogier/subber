<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Event;

use BD\Subber\Subtitles\Subtitle;
use Symfony\Component\EventDispatcher\Event;

class ScrapReleaseEvent extends Event
{
    /** @var string */
    private $releaseName;

    /** @var \BD\Subber\Subtitles\Subtitle[] */
    private $subtitles;

    public function __construct( $releaseName, $subtitles = null )
    {
        $this->releaseName = $releaseName;
        $this->subtitles = $subtitles;
    }

    /**
     * @return string
     */
    public function getReleaseName()
    {
        return $this->releaseName;
    }

    /**
     * @param string $releaseName
     */
    public function setReleaseName( $releaseName )
    {
        $this->releaseName = $releaseName;
    }

    /**
     * @return \BD\Subber\Subtitles\Subtitle[]
     */
    public function getSubtitles()
    {
        return $this->subtitles;
    }

    /**
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     */
    public function setSubtitles( $subtitles )
    {
        $this->subtitles = $subtitles;
    }
}
