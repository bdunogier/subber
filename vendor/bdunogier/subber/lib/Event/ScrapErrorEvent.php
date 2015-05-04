<?php

namespace BD\Subber\Event;

use Symfony\Component\EventDispatcher\Event;
use Exception;

class ScrapErrorEvent extends Event
{
    /** @var string */
    private $releaseName;

    /** @var string */
    private $message;

    /** @var \Exception */
    private $exception;

    public function __construct($releaseName, $message, Exception $e = null)
    {
        $this->releaseName = $releaseName;
        $this->message = $message;
        $this->exception = $e;
    }

    /**
     * @return string
     */
    public function getReleaseName()
    {
        return $this->releaseName;
    }

    /**
     * @return \BD\Subber\Subtitles\Subtitle[]
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return Exception
     */
    public function getException()
    {
        return $this->exception;
    }
}
