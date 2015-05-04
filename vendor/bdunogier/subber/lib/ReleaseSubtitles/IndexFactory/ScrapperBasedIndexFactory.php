<?php

namespace BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\Event\ScrapReleaseEvent;
use BD\Subber\EventDispatcher\EventDispatcherAware;
use BD\Subber\Release\Parser\VideoReleaseParser;
use BD\Subber\ReleaseSubtitles\Index;
use BD\Subber\ReleaseSubtitles\IndexFactory;
use BD\Subber\ReleaseSubtitles\TestedSubtitle;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use BD\Subber\Subtitles\ListConsolidator;
use BD\Subber\Subtitles\Scrapper;
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
    ) {
        $this->scrapper = $scrapper;
        $this->compatiblityMatcher = $compatibilityMatcher;
        $this->rater = $rater;
        $this->videoReleaseParser = $videoReleaseParser;
        $this->subtitleListConsolidator = $subtitleListConsolidator;
    }

    public function build($releaseName)
    {
        $event = new ScrapReleaseEvent($releaseName);
        $this->dispatch('subber.pre_scrap_release', $event);
        $subtitles = $this->scrapper->scrap($releaseName);
        $event->setSubtitles($subtitles);
        $this->dispatch('subber.post_scrap_release', $event);

        $videoRelease = $this->videoReleaseParser->parseReleaseName($releaseName);

        $this->subtitleListConsolidator->consolidate($subtitles);

        $this->makeSubtitlesTestable($subtitles);
        $subtitles = $this->compatiblityMatcher->match($videoRelease, $subtitles);
        array_map(
            function (TestedSubtitle $subtitle) {
                $subtitle->setRating($this->rater->rate($subtitle));
            },
            $subtitles
        );

        return new Index($videoRelease, $subtitles);
    }

    /**
     * Makes $subtitles an array of TestedSubtitle.
     *
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     *
     * @return \BD\Subber\ReleaseSubtitles\TestedSubtitle[]
     */
    private function makeSubtitlesTestable(array &$subtitles)
    {
        $subtitles = array_map(
            function ($subtitle) {
                return new TestedSubtitleObject(['compatibility' => TestedSubtitle::UNDETERMINED] + $subtitle->toArray());
            },
            $subtitles
        );
    }
}
