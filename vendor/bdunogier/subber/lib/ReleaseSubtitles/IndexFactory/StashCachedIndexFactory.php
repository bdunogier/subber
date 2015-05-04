<?php

namespace BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\Cache\CacheTtlProvider;
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

    public function __construct(IndexFactory $cachedFactory, PoolInterface $cachePool, CacheTtlProvider $cacheTtlProvider)
    {
        $this->cachedFactory = $cachedFactory;
        $this->cachePool = $cachePool;
        $this->ttl = $cacheTtlProvider;
    }

    public function build($releaseName)
    {
        $cacheItem = $this->cachePool->getItem($releaseName, 'subber_release_subtitles_index');
        if ($cacheItem->isMiss()) {
            $index = $this->cachedFactory->build($releaseName);
            $cacheItem->set($index, $this->ttl->get($index));
        }

        return $cacheItem->get();
    }
}
