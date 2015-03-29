<?php
namespace BD\Subber\Event;

use BD\Subber\ReleaseSubtitles\TestedSubtitle;
use BD\Subber\WatchList\WatchListItem;
use Symfony\Component\EventDispatcher\Event;

class NewBestSubtitleEvent extends Event
{
    /** @var \BD\Subber\WatchList\WatchListItem */
    private $watchListItem;

    /** @var \BD\Subber\ReleaseSubtitles\TestedSubtitle */
    private $subtitle;

    public function __construct( WatchListItem $item, TestedSubtitle $subtitle )
    {
        $this->watchListItem = $item;
        $this->subtitle = $subtitle;
    }

    /**
     * @return WatchListItem
     */
    public function getWatchListItem()
    {
        return $this->watchListItem;
    }

    /**
     * @return TestedSubtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }
}
