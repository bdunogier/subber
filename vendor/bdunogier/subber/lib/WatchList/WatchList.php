<?php

namespace BD\Subber\WatchList;

/**
 * A repository of items watched for subtitles.
 */
interface WatchList
{
    /**
     * @deprecated See findAllActiveItems()
     *
     * @return WatchListItem[]
     */
    public function findAllPendingItems();

    /**
     * Finds all items that are actively watched for subtitles.
     *
     * @return WatchListItem[]
     */
    public function findAllActiveItems();

    /**
     * @return WatchListItem[]
     */
    public function findAll();

    public function addItem(WatchListItem $item);

    public function setItemComplete(WatchListItem $item);

    /**
     * @return WatchListitem
     */
    public function loadByReleaseName($releaseName);

    /**
     * @return WatchListitem
     */
    public function loadByLocalReleasePath($localReleasePath);

    public function remove(WatchListItem $item);

    public function update(WatchListItem $item);
}
