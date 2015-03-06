<?php
namespace BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\ReleaseSubtitles\IndexFactory;
use Stash\Interfaces\PoolInterface;

class StashCachedIndexFactory implements IndexFactory
{
    /** @var \BD\Subber\ReleaseSubtitles\IndexFactory */
    private $cachedFactory;
    /**
     * @var \Stash\Interfaces\PoolInterface
     */
    private $cachePool;

    public function __construct( IndexFactory $cachedFactory, PoolInterface $cachePool )
    {
        $this->cachedFactory = $cachedFactory;
        $this->cachePool = $cachePool;
    }

    public function build( $releaseName )
    {
        $cacheItem = $this->cachePool->getItem( $releaseName, 'subber_release_subtitles_index' );
        if ( $cacheItem->isMiss() )
        {
            $index = $this->cachedFactory->build( $releaseName );
            $cacheItem->set( $index );
        }

        return $cacheItem->get();
    }
}
