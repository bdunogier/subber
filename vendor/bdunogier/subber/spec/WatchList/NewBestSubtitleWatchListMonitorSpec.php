<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\BD\Subber\WatchList;

use BD\Subber\WatchList\WatchListItem;
use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\Index;
use BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle;
use BD\Subber\Subtitles\Subtitle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NewBestSubtitleWatchListMonitorSpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\WatchList\WatchList $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $indexFactory
     * @param \BD\Subber\Subtitles\Saver $saver
     */
    function let( $watchList, $indexFactory, $saver )
    {
        $this->beConstructedWith( $watchList, $indexFactory, $saver );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\WatchList\NewBestSubtitleWatchListMonitor');
    }

    /**
     * @param \BD\Subber\WatchList\WatchList $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $indexFactory
     */
    function it_builds_the_index_for_pending_items( $watchList, $indexFactory )
    {
        $watchList->findAllPendingItems()->willReturn(
            [
                new WatchListItem(['originalName' => 'a']),
                new WatchListItem(['originalName' => 'b']),
            ]
        );

        $indexFactory->build('a')->willReturn( new Index(new Release(), [], []) );
        $indexFactory->build('b')->willReturn( new Index(new Release(), [], []) );

        $this->watchItems();
    }

    /**
     * @param \BD\Subber\WatchList\WatchList $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $indexFactory
     * @param \BD\Subber\Subtitles\Saver $saver
     */
    function it_ignores_subtitles_with_a_rating_lower_than_the_task_rating( $watchList, $indexFactory, $saver )
    {
        $watchListItem = new WatchListItem();
        $watchList->findAllPendingItems()->willReturn( $watchListItem );

        $watchListItem->setRating(0);
        $subtitle = new Subtitle();
        $indexFactory->build('a')->willReturn( new Index( new Release(), [$subtitle], [] ) );

        $saver->save( $subtitle, Argument::any() )->shouldNotBeCalled();

        $this->watchItems();
    }

    /**
     * @param \BD\Subber\WatchList\WatchList $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $indexFactory
     * @param \BD\Subber\Subtitles\Saver $saver
     * @param \BD\Subber\WatchList\WatchListItem $task
     * @param \BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle $testedSubtitle
     */
    function it_saves_a_subtitle_with_a_rating_higher_than_the_task_rating( $watchList, $indexFactory, $saver )
    {
        $watchListItem = new WatchListItem();
        $watchList->findAllPendingItems()->willReturn( [$watchListItem] );

        $watchListItem->setRating( 0 );
        $subtitle = new Subtitle();
        $subtitle->setRating( 1 );
        $indexFactory->build('a')->willReturn( new Index( new Release(), [subtitle], [] ) );

        $saver->save( $subtitle, Argument::any() )->shouldBeCalled();
        $watchListItem->setRating(1)->shouldBeCalled();
        $watchList->setItemComplete($watchListItem)->shouldBeCalled();

        $this->process();
    }
}
