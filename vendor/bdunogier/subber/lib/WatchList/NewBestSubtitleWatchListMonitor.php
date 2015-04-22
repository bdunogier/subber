<?php
namespace BD\Subber\WatchList;

use BD\Subber\Event\NewBestSubtitleEvent;
use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\ReleaseSubtitles\IndexFactory;
use BD\Subber\Subtitles\Saver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewBestSubtitleWatchListMonitor implements WatchListMonitor
{
    /** @var \BD\Subber\WatchList\WatchList */
    private $watchList;

    /** @var \BD\Subber\ReleaseSubtitles\IndexFactory */
    private $indexFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \BD\Subber\Subtitles\Saver */
    private $saver;

    public function __construct(
        WatchList $watchList,
        IndexFactory $indexFactory,
        Saver $saver,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->watchList = $watchList;
        $this->indexFactory = $indexFactory;
        $this->saver = $saver;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function watchItems()
    {
        foreach( $this->watchList->findAllPendingItems() as $item )
        {
            // see if we need to check again ?
            $index = $this->indexFactory->build( $item->getOriginalName() );

            if ( $index->hasBestSubtitle() )
            {
                $subtitle = $index->getBestSubtitle();
                if ( $item->getRating() !== null && $subtitle->getRating() <= $item->getRating() ) {
                    continue;
                }

                if ( isset( $this->eventDispatcher ) )
                {
                    $this->eventDispatcher->dispatch(
                        'subber.new_best_subtitle',
                        new NewBestSubtitleEvent( $item, $subtitle )
                    );
                }
            }
        }
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher( $eventDispatcher )
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
