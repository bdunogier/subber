<?php

namespace BD\Subber\Event;

use BD\Subber\WatchList\WatchListItem;
use Symfony\Component\EventDispatcher\Event;

class NewWatchListItemEvent extends Event
{
    /** @var WatchListItem */
    private $item;

    public function __construct(WatchListItem $item)
    {
        $this->item = $item;
    }

    /**
     * @return WatchListItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param WatchListItem $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }
}
