<?php

namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;

class CompatibilityMatcher
{
    /**
     * Matches $subtitles against Episode Release $release.
     *
     * @param \BD\Subber\Release\Release                   $release
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle[] $testedSubtitles
     *
     * @return \BD\Subber\ReleaseSubtitles\TestedSubtitle[]
     */
    public function match(Release $release, array $testedSubtitles)
    {
        if (!count($testedSubtitles)) {
            return array();
        }

        $releaseRequiresRepackedSubtitles = $this->releaseRequiresRepackedSubtitles($release, $testedSubtitles);

        foreach ($testedSubtitles as $subtitle) {
            if ($releaseRequiresRepackedSubtitles) {
                if ($subtitle->isProper() !== $release->isProper() || $subtitle->isRepack() !== $release->isRepack()) {
                    $subtitle->setIncompatible();
                    continue;
                }
            }

            if ($subtitle->getSource() !== null && $subtitle->getSource() != $release->getSource()) {
                $subtitle->setIncompatible();
                continue;
            }

            if ($subtitle->getGroup() !== null && $subtitle->getGroup() != $release->getGroup()) {
                // At this point, the source is different, but the source is the same.
                // we may test if the resolution is set on sub & release. If it ain't, they're probably compatible
                if ($subtitle->getResolution() !== null || $release->getResolution() !== null) {
                    $subtitle->setIncompatible();
                    continue;
                }
            }
        }

        return $testedSubtitles;
    }

    /**
     * Tests if $release requires repacked Subtitles.
     *
     * @param \BD\Subber\Release\Release      $release
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     *
     * @return bool
     */
    private function releaseRequiresRepackedSubtitles(Release $release, array $subtitles)
    {
        if (!$release->isRepack() && !$release->isProper()) {
            return false;
        }

        foreach ($subtitles as $subtitle) {
            if ($subtitle->isRepack() || $subtitle->isProper()) {
                return true;
            }
        }

        return false;
    }
}
