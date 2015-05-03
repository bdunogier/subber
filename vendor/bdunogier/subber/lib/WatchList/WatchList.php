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

    /**
     * @return WatchListItem[]
     */
    public function findAll();

    public function addItem( WatchListItem $item );

    public function setItemComplete( WatchListItem $item );

    /**
     * @return WatchListitem
     */
    public function loadByReleaseName( $releaseName );

    /**
     * @return WatchListitem
     */
    public function loadByLocalReleasePath( $localReleasePath );

    public function remove( WatchListItem $item );
}
