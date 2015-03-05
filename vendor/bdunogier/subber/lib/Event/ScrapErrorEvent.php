<?php
namespace BD\Subber\Event;

use BD\Subber\Subtitles\Subtitle;
use Symfony\Component\EventDispatcher\Event;

class ScrapErrorEvent extends Event
{
    /** @var string */
    private $releaseName;

    /** @var \BD\Subber\Subtitles\Subtitle[] */
    private $message;

    public function __construct( $releaseName, $message )
    {
        $this->releaseName = $releaseName;
        $this->message = $message;
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
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \BD\Subber\Subtitles\Subtitle[] $message
     */
    public function setMessage( $message )
    {
        $this->message = $message;
    }
}
