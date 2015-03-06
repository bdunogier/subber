<?php
namespace spec\BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\ReleaseSubtitles\IndexFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Stash\Interfaces\PoolInterface;
use Stash\Item;

class StashCachedIndexFactorySpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $cachedIndexFactory
     * @param \Stash\Interfaces\PoolInterface $cachePool
     */
    function let( $cachedIndexFactory, $cachePool )
    {
        $this->beConstructedWith( $cachedIndexFactory, $cachePool );
    }

    function it_is_initializable(  )
    {
        $this->shouldHaveType( 'BD\Subber\ReleaseSubtitles\IndexFactory\StashCachedIndexFactory' );
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $cachedIndexFactory
     * @param \Stash\Interfaces\PoolInterface $cachePool
     * @param  \Stash\Interfaces\ItemInterface $cacheItem
     */
    function it_caches_uncached_data( $cachedIndexFactory, $cachePool, $cacheItem )
    {
        $subtitles = ['release_1' => [], 'release_2'];

        $cacheItem->isMiss()->willReturn( true );
        $cacheItem->set( $subtitles )->shouldBeCalled();
        $cacheItem->get()->willReturn( $subtitles );

        $cachePool->getItem( 'release.name-group', Argument::type('string') )->willReturn( $cacheItem );

        $cachedIndexFactory->build( 'release.name-group' )->willReturn( $subtitles );

        $this->build( 'release.name-group' )->shouldReturn( $subtitles );
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $cachedIndexFactory
     * @param \Stash\Interfaces\PoolInterface $cachePool
     * @param  \Stash\Interfaces\ItemInterface $cacheItem
     */
    function it_uses_valid_cache_if_it_exists( $cachedIndexFactory, $cachePool, $cacheItem )
    {
        $subtitles = ['release_1' => [], 'release_2'];

        $cacheItem->isMiss()->willReturn( false );
        $cacheItem->get()->willReturn( $subtitles );
        $cachePool->getItem( 'release.name-group', Argument::type('string') )->willReturn( $cacheItem );

        $this->build( 'release.name-group' )->shouldReturn( $subtitles );
    }
}
