<?php
namespace BD\Subber\WatchList;

/**
 * A repository of items watched for subtitles.
 */
interface WatchList
{
    /**
     * @return WatchListItem[]
     */
    public function findAllPendingItems();

    public function addItem( WatchListItem $task );

    public function setItemComplete( WatchListItem $task );

    /**
     * @return WatchListitem
     */
    public function loadByReleaseName( $releaseName );

    /**
     * @return WatchListitem
     */
    public function loadByLocalReleasePath( $localReleasePath );
}
