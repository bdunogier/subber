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
        // we should test the group, but they can be compatible...
        if ($subtitle->source != $release->source) {
            return false;
        }

        return true;
    }
}
