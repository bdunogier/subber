<?php
namespace BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\Event\ScrapReleaseEvent;
use BD\Subber\EventDispatcher\EventDispatcherAware;
use BD\Subber\Release\Parser\VideoReleaseParser;
use BD\Subber\ReleaseSubtitles\Index;
use BD\Subber\ReleaseSubtitles\IndexFactory;
use BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle;
use BD\Subber\Subtitles\ListConsolidator;
use BD\Subber\Subtitles\Scrapper;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\Rater;
use BD\Subber\ReleaseSubtitles\CompatibilityMatcher;

class ScrapperBasedIndexFactory implements IndexFactory
{
    use EventDispatcherAware;

    /** @var \BD\Subber\Subtitles\Scrapper */
    private $scrapper;

    /** @var \BD\Subber\ReleaseSubtitles\CompatibilityMatcher */
    private $compatiblityMatcher;

    /** @var \BD\Subber\Subtitles\Rater */
    private $rater;

    /** @var \BD\Subber\Release\Parser\VideoReleaseParser */
    private $videoReleaseParser;

    /** @var \BD\Subber\Subtitles\ListConsolidator */
    private $subtitleListConsolidator;

    public function __construct(
        Scrapper $scrapper,
        VideoReleaseParser $videoReleaseParser,
        CompatibilityMatcher $compatibilityMatcher,
        Rater $rater,
        ListConsolidator $subtitleListConsolidator
    )
    {
        $this->scrapper = $scrapper;
        $this->compatiblityMatcher = $compatibilityMatcher;
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

        $this->subtitleListConsolidator->consolidate( $subtitles );
        $subtitles = $this->compatiblityMatcher->match( $videoRelease, $subtitles );

        $subtitleSortCallback = function( Subtitle $a, Subtitle $b ) {
            $aRate = $this->rater->rate( $a );
            $bRate = $this->rater->rate( $b );

            if ( $aRate > $bRate )
                return -1;
            if ( $aRate < $bRate )
                return 1;
            return 0;
        };

        usort( $subtitles, $subtitleSortCallback );

        $compatibleSubtitles = array_values(
            array_filter(
                $subtitles,
                function ( TestedReleaseSubtitle $subtitle ) {
                    return $subtitle->isCompatible();
                }
            )
        );

        $incompatibleSubtitles = array_values(
                array_filter(
                $subtitles,
                function ( TestedReleaseSubtitle $subtitle ) {
                    return !$subtitle->isCompatible();
                }
            )
        );

        return new Index(
            $compatibleSubtitles,
            $incompatibleSubtitles
        );
    }
}
