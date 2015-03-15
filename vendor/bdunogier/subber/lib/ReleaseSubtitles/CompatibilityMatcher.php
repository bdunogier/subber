<?php

namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle;

class CompatibilityMatcher
{
    /**
     * Matches $subtitles against Episode Release $release
     *
     * @param \BD\Subber\Release\Release $release
     * @param \BD\Subber\Subtitles\Subtitle[] $testedSubtitles
     *
     * @return \BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle[]
     */
    public function match( Release $release, array $testedSubtitles )
    {
        if ( !count( $testedSubtitles ) ) {
            return array();
        }
        $testedSubtitles = $this->makeSubtitlesTestable( $release, $testedSubtitles );

        $releaseRequiresRepackedSubtitles = $this->releaseRequiresRepackedSubtitles( $release, $testedSubtitles );

        foreach ( $testedSubtitles as $subtitle ) {
            if ( $releaseRequiresRepackedSubtitles )
            {
                if ( $subtitle->isProper !== $release->isProper || $subtitle->isRepack !== $release->isRepack ) {
                    $subtitle->setIncompatible();
                }
            }

            if ( isset( $subtitle->source ) && $subtitle->source != $release->source ) {
                $subtitle->setIncompatible();
                continue;
            }
            if ( isset( $subtitle->group ) && $subtitle->group != $release->group ) {
                $subtitle->setIncompatible();
                continue;
            }
        }

        return $testedSubtitles;
    }

    /**
     * Tests if $release requires repacked Subtitles.
     *
     * @param \BD\Subber\Release\Release $release
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     *
     * @return bool
     */
    private function releaseRequiresRepackedSubtitles( Release $release, array $subtitles )
    {
        if (!$release->isRepack && !$release->isProper) {
            return false;
        }

        foreach ( $subtitles as $subtitle ) {
            if ( $subtitle->isRepack || $subtitle->isProper ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Converts all Subtitles into TestedReleaseSubtitle
     * @param \BD\Subber\Release\Release $release
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     *
     * @return \BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle[]
     */
    private function makeSubtitlesTestable( Release $release, array $subtitles )
    {
        return array_map(
            function ( $subtitle, $release ) {
                return new TestedReleaseSubtitle( $release, $subtitle );
            },
            $subtitles,
            array_fill( 0, count( $subtitles ), $release )
        );
    }
}
