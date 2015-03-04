<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Event;

use Symfony\Component\EventDispatcher\Event;

class SaveSubtitleEvent extends Event
{
    /**
     * Path/URI the subtitle is saved from
     * @var string
     */
    private $from;

    /**
     * Path/URI the subtitle is saved to
     * @var string
     */
    private $to;

    public function __construct( $from, $to )
    {
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom( $from )
    {
        $this->from = $from;
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
