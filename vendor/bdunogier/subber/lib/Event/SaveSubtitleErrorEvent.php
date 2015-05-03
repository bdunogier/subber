<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Event;

use BD\Subber\Subtitles\Subtitle;
use Symfony\Component\EventDispatcher\Event;

class SaveSubtitleErrorEvent extends Event
{
    /**
     * @var \BD\Subber\Subtitles\Subtitle
     */
    private $subtitle;

    /**
     * @var string
     */
    private $forFile;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $toFile;

    public function __construct( Subtitle $subtitle, $forFile, $toFile, $error = '' )
    {

        $this->subtitle = $subtitle;
        $this->forFile = $forFile;
        $this->toFile = $toFile;
        $this->error = $error;
    }

    /**
     * @param string $error
     *
     * @return SaveSubtitleErrorEvent
     */
    public function setError( $error )
    {
        $this->error = $error;

        return $this;
}

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $forFile
     *
     * @return SaveSubtitleErrorEvent
     */
    public function setForFile( $forFile )
    {
        $this->forFile = $forFile;

        return $this;
}

    /**
     * @return string
     */
    public function getForFile()
    {
        return $this->forFile;
    }

    /**
     * @param Subtitle $subtitle
     *
     * @return SaveSubtitleErrorEvent
     */
    public function setSubtitle( $subtitle )
    {
        $this->subtitle = $subtitle;

        return $this;
}

    /**
     * @return Subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * @param string $toFile
     *
     * @return SaveSubtitleErrorEvent
     */
    public function setToFile( $toFile )
    {
        $this->toFile = $toFile;

        return $this;
}

    /**
     * @return string
     */
    public function getToFile()
    {
        return $this->toFile;
    }
}
