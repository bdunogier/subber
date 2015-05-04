<?php

namespace spec\BD\Subber\ReleaseSubtitles\IndexFactory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StashCachedIndexFactorySpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $cachedIndexFactory
     * @param \Stash\Interfaces\PoolInterface          $cachePool
     * @param \BD\Subber\Cache\CacheTtlProvider        $cacheTtlProvider
     */
    public function let($cachedIndexFactory, $cachePool, $cacheTtlProvider)
    {
        $this->beConstructedWith($cachedIndexFactory, $cachePool, $cacheTtlProvider);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\ReleaseSubtitles\IndexFactory\StashCachedIndexFactory');
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $cachedIndexFactory
     * @param \Stash\Interfaces\PoolInterface          $cachePool
     * @param \Stash\Interfaces\ItemInterface          $cacheItem
     */
    public function it_caches_uncached_data($cachedIndexFactory, $cachePool, $cacheItem)
    {
        $subtitles = ['release_1' => [], 'release_2'];

        $cacheItem->isMiss()->willReturn(true);
        $cacheItem->set($subtitles, null)->shouldBeCalled();
        $cacheItem->get()->willReturn($subtitles);

        $cachePool->getItem('release.name-group', Argument::type('string'))->willReturn($cacheItem);

        $cachedIndexFactory->build('release.name-group')->willReturn($subtitles);

        $this->build('release.name-group')->shouldReturn($subtitles);
    }

    /**
     * @param \Stash\Interfaces\PoolInterface $cachePool
     * @param \Stash\Interfaces\ItemInterface $cacheItem
     */
    public function it_uses_valid_cache_if_it_exists($cachePool, $cacheItem)
    {
        $subtitles = ['release_1' => [], 'release_2'];

        $cacheItem->isMiss()->willReturn(false);
        $cacheItem->get()->willReturn($subtitles);
        $cachePool->getItem('release.name-group', Argument::type('string'))->willReturn($cacheItem);

        $this->build('release.name-group')->shouldReturn($subtitles);
    }
}
