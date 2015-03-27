<?php
namespace BD\Subber\WatchList;

/**
 * Monitors the WatchList for updates to items, and dispatches an event upon a change.
 */
interface WatchListMonitor
{
    public function watchItems();
}
