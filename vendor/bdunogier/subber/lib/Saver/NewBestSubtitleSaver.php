<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Saver;

use BD\Subber\Event\NewBestSubtitleEvent;
use BD\Subber\WatchList\WatchList;
use BD\Subber\Subtitles\Saver;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewBestSubtitleSaver implements EventSubscriberInterface
{
    /** @var \BD\Subber\WatchList\WatchList */
    private $watchList;

    /** @var \BD\Subber\Subtitles\Saver */
    private $saver;

    public function __construct( WatchList $watchList, Saver $saver )
    {
        $this->watchList = $watchList;
        $this->saver = $saver;
    }

    public static function getSubscribedEvents()
    {
        return ['subber.new_best_subtitle' => 'onNewBestSubtitle'];
    }

    public function onNewBestSubtitle( NewBestSubtitleEvent $event )
    {
        $item = $event->getWatchListItem();
        $subtitle = $event->getSubtitle();

        $this->watchList->update(
            $item
                ->setRating( $subtitle->getRating() )
                ->setHasSubtitle( true )
        );

        $this->saver->save( $subtitle, $item->getFile() );
    }
}
