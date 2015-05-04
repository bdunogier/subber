<?php

namespace BD\Subber\Subtitles;

use BD\Subber\Release\ReleaseObject;

class SubtitleObject extends ReleaseObject implements Subtitle
{
    /**
     * The language (en, fr...).
     */
    protected $language;

    /**
     * Who created the subtitle (addic7ed, seriessub...).
     *
     * @var string
     */
    protected $author;

    /**
     * The URL where the subtitle can be downloaded.
     *
     * @var string
     */
    protected $url;

    /**
     * Wether or not this subtitle is a hearing impaired one.
     *
     * @var bool
     */
    protected $isHearingImpaired = false;

    /** @var string */
    protected $subtitleFormat = 'srt';

    /** @var bool */
    protected $hasTags = false;

    /** @var string */
    protected $compatibility;

    /** @var int */
    protected $rating;

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function isHearingImpaired()
    {
        return $this->isHearingImpaired;
    }

    public function setIsHearingImpaired($isHearingImpaired)
    {
        $this->isHearingImpaired = $isHearingImpaired;
    }

    public function getSubtitleFormat()
    {
        return $this->subtitleFormat;
    }

    public function setSubtitleFormat($subtitleFormat)
    {
        $this->subtitleFormat = $subtitleFormat;
    }

    public function hasTags()
    {
        return $this->hasTags;
    }

    public function setHasTags($hasTags)
    {
        $this->hasTags = $hasTags;
    }
}
