<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

use BD\Subber\Election\Ballot;
use BD\Subber\Release\Parser\VideoReleaseParser;

/**
 * Instantiates ReleaseSubtitlesCollectionFactory objects from an episode and a download.
 *
 * Scraps the downloaded filename for subtitles, and filters the subtitles based on the download.
 */
class ReleaseSubtitlesCollectionFactory
{
    /** @var \BD\Subber\Subtitles\Scrapper */
    private $scrapper;

    /** @var \BD\Subber\Subtitles\SubtitleReleaseMatcher */
    private $matcher;

    /** @var \BD\Subber\Subtitles\SubtitleRater */
    private $rater;

    /** @var \BD\Subber\Release\Parser\VideoReleaseParser */
    private $videoReleaseParser;

    public function __construct( Scrapper $scrapper, VideoReleaseParser $videoReleaseParser, SubtitleReleaseMatcher $matcher, SubtitleRater $rater )
    {
        $this->scrapper = $scrapper;
        $this->matcher = $matcher;
        $this->rater = $rater;
        $this->videoReleaseParser = $videoReleaseParser;
    }

    /**
     * @param string $downloadedFileName
     *
     * @return \BD\Subber\Subtitles\ReleaseSubtitlesCollection
     */
    public function build( $releaseName )
    {
        $subtitles = $this->scrapper->scrap( $releaseName );
        $videoRelease = $this->videoReleaseParser->parseReleaseName( $releaseName);

        $acceptableSubtitles = [];
        $unacceptableSubtitles = [];

        foreach ( $subtitles as $subtitle )
        {
            if ( $this->matcher->matches( $subtitle, $videoRelease ) )
            {
                $acceptableSubtitles[] = $subtitle;
            }
            else
            {
                $unacceptableSubtitles[] = $subtitle;
            }
        }

        $subtitleSortCallback = function( Subtitle $a, Subtitle $b ) {
            $aRate = $this->rater->rate( $a );
            $bRate = $this->rater->rate( $b );

            if ( $aRate === $bRate )
                return 0;
            if ( $aRate > $bRate )
                return -1;
            if ( $aRate < $bRate )
                return 1;
        };

        usort( $acceptableSubtitles, $subtitleSortCallback );
        usort( $unacceptableSubtitles, $subtitleSortCallback );

        return new ReleaseSubtitlesCollection( $acceptableSubtitles, $unacceptableSubtitles );
    }
}
