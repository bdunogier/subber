<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace spec\BD\Subber\WatchList;

use BD\Subber\Release\ReleaseObject;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use BD\Subber\WatchList\WatchListItem;
use BD\Subber\ReleaseSubtitles\Index;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewBestSubtitleWatchListMonitorSpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\WatchList\WatchList                              $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory                    $indexFactory
     * @param \BD\Subber\Subtitles\Saver                                  $saver
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function let($watchList, $indexFactory, $saver, $eventDispatcher)
    {
        $this->beConstructedWith($watchList, $indexFactory, $saver, $eventDispatcher);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\WatchList\NewBestSubtitleWatchListMonitor');
    }

    /**
     * @param \BD\Subber\WatchList\WatchList           $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $indexFactory
     */
    public function it_builds_the_index_for_pending_items($watchList, $indexFactory)
    {
        $watchList->findAllActiveItems()->willReturn(
            [
                new WatchListItem(['originalName' => 'a']),
                new WatchListItem(['originalName' => 'b']),
            ]
        );

        $indexFactory->build('a')->willReturn(new Index(new ReleaseObject(), []));
        $indexFactory->build('b')->willReturn(new Index(new ReleaseObject(), []));

        $this->watchItems();
    }

    /**
     * @param \BD\Subber\WatchList\WatchList           $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $indexFactory
     * @param \BD\Subber\Subtitles\Saver               $saver
     */
    public function it_ignores_subtitles_with_a_rating_lower_than_the_task_rating($watchList, $indexFactory, $saver)
    {
        $watchListItem = new WatchListItem();
        $watchList->findAllActiveItems()->willReturn($watchListItem);

        $watchListItem->setRating(0);
        $subtitle = new TestedSubtitleObject();
        $indexFactory->build('a')->willReturn(new Index(new ReleaseObject(), [$subtitle], []));

        $saver->save($subtitle, Argument::any())->shouldNotBeCalled();

        $this->watchItems();
    }

    /**
     * @param \BD\Subber\WatchList\WatchList                              $watchList
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory                    $indexFactory
     * @param \BD\Subber\ReleaseSubtitles\Index                           $index
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function it_dispatches_a_new_best_subtitle_event_when_a_better_subtitle_is_found(
        $watchList,
        $indexFactory,
        Index $index,
        EventDispatcherInterface $eventDispatcher
    ) {
        $watchListItem = new WatchListItem(['originalName' => 'a', 'rating' => 0]);
        $watchList->findAllActiveItems()->willReturn([$watchListItem]);

        $newerSubtitle = new TestedSubtitleObject(['rating' => 3]);
        $indexFactory->build('a')->willReturn($index);
        $index->hasBestSubtitle()->willReturn(true);
        $index->getBestSubtitle()->willReturn($newerSubtitle);

        $eventDispatcher->dispatch(
            'subber.new_best_subtitle',
            Argument::type('BD\Subber\Event\NewBestSubtitleEvent')
        )->shouldBeCalled();
        $this->watchItems();
    }
}
