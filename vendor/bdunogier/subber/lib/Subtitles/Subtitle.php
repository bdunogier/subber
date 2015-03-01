<?php
namespace BD\Subber\Subtitles;

use BD\Subber\Release\Release;

class Subtitle extends Release
{
    /**
     * The language (en, fr...)
     */
    public $language;

    /**
     * Who created the subtitle (addic7ed, seriessub...)
     * @var string
     */
    public $author;

    /**
     * The URL where the subtitle can be downloaded
     * @var string
     */
    public $url;

    /**
     * Wether or not this subtitle is a hearing impaired one
     * @var bool
     */
    public $isHearingImpaired = false;

    /** @var string */
    public $subtitleFormat = 'srt';

    /** @var bool */
    public $hasTags = false;
}
