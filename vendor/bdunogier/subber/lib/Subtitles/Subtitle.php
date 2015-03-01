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
}
