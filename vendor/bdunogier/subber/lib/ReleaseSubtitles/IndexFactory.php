<?php
namespace BD\Subber\ReleaseSubtitles;

use BD\Subber\Election\Ballot;
use BD\Subber\Release\Parser\VideoReleaseParser;
use BD\Subber\Subtitles\Scrapper;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\Rater;
use BD\Subber\ReleaseSubtitles\Matcher;

/**
 * Builds up Index objects from an episode and a download.
 *
 * Scraps the downloaded filename for subtitles, and filters the subtitles based on the download.
 */
class IndexFactory
{
    /** @var \BD\Subber\Subtitles\Scrapper */
    private $scrapper;

    /** @var \BD\Subber\ReleaseSubtitles\Matcher */
    private $matcher;

    /** @var \BD\Subber\Subtitles\Rater */
    private $rater;

    /** @var \BD\Subber\Release\Parser\VideoReleaseParser */
    private $videoReleaseParser;

    public function __construct(
        Scrapper $scrapper,
        VideoReleaseParser $videoReleaseParser,
        Matcher $matcher,
        Rater $rater
    )
    {
        $this->scrapper = $scrapper;
        $this->matcher = $matcher;
        $this->rater = $rater;
        $this->videoReleaseParser = $videoReleaseParser;
    }

    /**
     * @param string $releaseName
     *
     * @return \BD\Subber\ReleaseSubtitles\Index
     */
    public function build( $releaseName )
    {
        $subtitles = $this->scrapper->scrap( $releaseName );
        $videoRelease = $this->videoReleaseParser->parseReleaseName( $releaseName);

        $compatible = [];
        $incompatible = [];

        foreach ( $subtitles as $subtitle )
        {
            if ( $this->matcher->matches( $subtitle, $videoRelease ) )
            {
                $compatible[] = $subtitle;
            }
            else
            {
                $incompatible[] = $subtitle;
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

        usort( $compatible, $subtitleSortCallback );
        usort( $incompatible, $subtitleSortCallback );

        return new Index( $compatible, $incompatible );
    }
}
