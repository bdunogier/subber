<?php
namespace BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\Event\ScrapReleaseEvent;
use BD\Subber\EventDispatcher\EventDispatcherAware;
use BD\Subber\Release\Parser\VideoReleaseParser;
use BD\Subber\ReleaseSubtitles\Index;
use BD\Subber\ReleaseSubtitles\IndexFactory;
use BD\Subber\Subtitles\ListConsolidator;
use BD\Subber\Subtitles\Scrapper;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\Rater;
use BD\Subber\ReleaseSubtitles\Matcher;

class ScrapperBasedIndexFactory implements IndexFactory
{
    use EventDispatcherAware;

    /** @var \BD\Subber\Subtitles\Scrapper */
    private $scrapper;

    /** @var \BD\Subber\ReleaseSubtitles\Matcher */
    private $matcher;

    /** @var \BD\Subber\Subtitles\Rater */
    private $rater;

    /** @var \BD\Subber\Release\Parser\VideoReleaseParser */
    private $videoReleaseParser;

    /** @var \BD\Subber\Subtitles\ListConsolidator */
    private $subtitleListConsolidator;

    public function __construct(
        Scrapper $scrapper,
        VideoReleaseParser $videoReleaseParser,
        Matcher $matcher,
        Rater $rater,
        ListConsolidator $subtitleListConsolidator
    )
    {
        $this->scrapper = $scrapper;
        $this->matcher = $matcher;
        $this->rater = $rater;
        $this->videoReleaseParser = $videoReleaseParser;
        $this->subtitleListConsolidator = $subtitleListConsolidator;
    }

    public function build( $releaseName )
    {
        $event = new ScrapReleaseEvent( $releaseName );
        $this->dispatch( 'subber.pre_scrap_release', $event );
        $subtitles = $this->scrapper->scrap( $releaseName );
        $event->setSubtitles( $subtitles );
        $this->dispatch( 'subber.post_scrap_release', $event );

        $videoRelease = $this->videoReleaseParser->parseReleaseName( $releaseName);

        $compatible = [];
        $incompatible = [];

        $this->subtitleListConsolidator->consolidate( $subtitles );
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

            if ( $aRate > $bRate )
                return -1;
            if ( $aRate < $bRate )
                return 1;
            return 0;
        };

        usort( $compatible, $subtitleSortCallback );
        usort( $incompatible, $subtitleSortCallback );

        return new Index( $compatible, $incompatible );
    }
}
