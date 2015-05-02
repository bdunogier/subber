<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Event;

use BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Component\EventDispatcher\Event;

class SaveSubtitleEvent extends Event
{
    /**
     * Subtitle object that is being saved
     * @var string
     */
    private $subtitle;

    /**
     * Path/URI the subtitle is saved to
     * @var string
     */
    private $to;

    public function __construct( Subtitle $subtitle, $to )
    {
        $this->subtitle = $subtitle;
        $this->to = $to;
    }

    /**
     * @return Subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle( Subtitle $subtitle )
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $to
     */
    public function setTo( $to )
    {
        $this->to = $to;
    }
}
