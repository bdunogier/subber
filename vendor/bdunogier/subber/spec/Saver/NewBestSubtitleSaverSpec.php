<?php

namespace spec\BD\Subber\Saver;

use BD\Subber\Event\NewBestSubtitleEvent;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use BD\Subber\Subtitles\Saver;
use BD\Subber\Subtitles\SubtitleObject;
use BD\Subber\WatchList\WatchList;
use BD\Subber\WatchList\WatchListItem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NewBestSubtitleSaverSpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\WatchList\WatchList $watchList
     * @param \BD\Subber\Subtitles\Saver $saver
     */
    function let( WatchList $watchList, Saver $saver)
    {
        $this->beConstructedWith( $watchList, $saver );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Saver\NewBestSubtitleSaver');
    }

    function it_saves_the_subtitle_file( Saver $saver )
    {
        $subtitle = new TestedSubtitleObject();
        $saver->save( $subtitle, Argument::any() )->shouldBeCalled();

        $event = new NewBestSubtitleEvent( new WatchListItem(), $subtitle );
        $this->onNewBestSubtitle( $event );
    }

    function it_updates_the_watchlist_item_rating( NewBestSubtitleEvent $event, WatchListItem $watchListItem )
    {
        $event->getWatchListItem()->willReturn( $watchListItem );
        $event->getSubtitle()->willReturn( new TestedSubtitleObject(['rating' => 3]) );

        $watchListItem->setRating(3)->shouldBeCalled();
        $watchListItem->getFile()->shouldBeCalled();
        $this->onNewBestSubtitle( $event );
    }

    function it_marks_the_watchlist_item_as_complete( WatchList $watchList, NewBestSubtitleEvent $event, WatchListItem $watchListItem )
    {
        $event->getWatchListItem()->willReturn( $watchListItem );
        $event->getSubtitle()->willReturn( new TestedSubtitleObject() );

        $watchListItem->setRating( Argument::any() )->shouldBeCalled();
        $watchListItem->getFile()->shouldBeCalled();

        $watchList->setItemComplete( $watchListItem )->shouldBeCalled();

        $this->onNewBestSubtitle( $event );
    }
}
