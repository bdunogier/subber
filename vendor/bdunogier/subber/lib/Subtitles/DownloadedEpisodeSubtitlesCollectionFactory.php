<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

use BD\Subber\Election\Ballot;

/**
 * Instantiates EpisodeSubtitleCollection objects from an episode and a download.
 *
 * Scraps the downloaded filename for subtitles, and filters the subtitles based on the download.
 */
class DownloadedEpisodeSubtitlesCollectionFactory
{
    /** @var \BD\Subber\Subtitles\Scrapper */
    private $scrapper;

    /** @var \BD\Subber\Election\Ballot */
    private $matcher;

    /** @var \BD\Subber\Subtitles\SubtitleRater */
    private $rater;

    public function __construct( Scrapper $scrapper, Matcher $matcher, SubtitleRater $rater )
    {
        $this->scrapper = $scrapper;
        $this->matcher = $matcher;
        $this->rater = $rater;
    }

    /**
     * @param string $downloadedFileName
     *
     * @return \BD\Subber\Subtitles\EpisodeSubtitlesCollection
     */
    public function getCollection( $downloadedFileName )
    {
        $subtitles = $this->scrapper->scrap( $downloadedFileName );

        $acceptableSubtitles = [];
        $unacceptableSubtitles = [];

        foreach ( $subtitles as $subtitle )
        {
            if ( $this->matcher->matches( $subtitle, $downloadedFileName ) )
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
                return 1;
            if ( $aRate < $bRate )
                return -1;
        };

        usort( $acceptableSubtitles, $subtitleSortCallback );
        usort( $unacceptableSubtitles, $subtitleSortCallback );

        return new EpisodeSubtitlesCollection( $acceptableSubtitles, $unacceptableSubtitles );
    }
}
