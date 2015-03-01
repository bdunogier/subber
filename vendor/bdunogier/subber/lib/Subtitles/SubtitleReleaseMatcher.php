<?php
namespace BD\Subber\Subtitles;

use BD\Subber\Release\Release;

class SubtitleReleaseMatcher implements SubtitleReleaseMatcherInterface
{
    /**
     * Tests if $subtitle is a match for $release
     *
     * @param \BD\Subber\Subtitles\Subtitle $subtitle
     * @param \BD\Subber\Release\Release $release
     *
     * @return mixed
     */
    public function matches( Subtitle $subtitle, Release $release )
    {
        if ($subtitle->group != $release->group) {
            return false;
        }

        if ($subtitle->source != $release->source) {
            return false;
        }

        return true;
    }
}
