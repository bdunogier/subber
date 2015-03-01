<?php
namespace BD\Subber\Subtitles;

use BD\Subber\Release\Release;

interface SubtitleReleaseMatcherInterface
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
