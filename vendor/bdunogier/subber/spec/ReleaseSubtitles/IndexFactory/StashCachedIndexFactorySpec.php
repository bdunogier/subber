<?php
namespace spec\BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\ReleaseSubtitles\IndexFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Stash\Interfaces\PoolInterface;

class StashCachedIndexFactorySpec extends ObjectBehavior
{
    function let( $cachedIndexFactory, $cachePool )
    {
        $cachedIndexFactory->beADoubleOf( 'BD\Subber\ReleaseSubtitles\IndexFactory' );
        $cachePool->beADoubleOf( 'Stash\Interfaces\PoolInterface' );
        $this->beConstructedWith( $cachedIndexFactory, $cachePool );
    }

    function it_is_initializable(  )
    {
        $this->shouldHaveType('BD\Subber\ReleaseSubtitles\IndexFactory\StashCachedIndexFactory');
    }
}
