<?php
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\Subtitles\Subtitle;

/**
 * Matches Subtitles to Releases
 */
interface Matcher
{
    /**
     * Tests if $subtitle is a match for $release
     *
     * @param \BD\Subber\Subtitles\Subtitle $subtitle
     * @param \BD\Subber\Release\Release $release
     *
     * @return mixed
     */
    public function matches( Subtitle $subtitle, Release $release );
}
