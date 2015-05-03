<?php
namespace BD\Subber\Logger;

use BD\Subber\Event\NewBestSubtitleEvent;
use BD\Subber\Event\NewWatchListitemEvent;
use BD\Subber\Event\SaveSubtitleErrorEvent;
use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\Event\ScrapErrorEvent;
use BD\Subber\Event\ScrapReleaseEvent;
use BD\Subber\Exceptions\UnknownSubtitleSourceException;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Writes to monolog depending on various subber events
 */
class LoggerEventListener implements EventSubscriberInterface
{
    /** @var \Monolog\Logger */
    private $logger;

    public function __construct( Logger $logger )
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            'subber.save_subtitle' => ['onSaveSubtitle'],
            'subber.post_scrap_release' => ['onScrapRelease'],
            'subber.watch.post_new_item' => ['onNewWatchListItem'],
            'subber.scrap_error' => ['onScrapError'],
            'subber.new_best_subtitle' => ['onNewBestSubtitle'],
            'subber.save_subtitle_error' => ['onSaveSubtitleError']
        ];
    }

    public function onSaveSubtitle( SaveSubtitleEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Saving subtitle '%s' for file '%s'",
                $event->getSubtitle()->getUrl(), $event->getTo()
            )
        );
    }

    public function onSaveSubtitleError( SaveSubtitleErrorEvent $event )
    {
        $this->logger->warning(
            sprintf(
                "Unable to save '%s' to '%s': %s",
                $event->getSubtitle()->getUrl(), $event->getToFile(), $event->getError()
            )
        );
    }

    public function onScrapRelease( ScrapReleaseEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Scrapped %d subtitles for %s",
                count( $event->getSubtitles() ),
                $event->getReleaseName()
            )
        );
    }

    public function onScrapError( ScrapErrorEvent $event )
    {
        $exception = $event->getException();

        if ($exception instanceof UnknownSubtitleSourceException) {
            $this->logger->debug(
                sprintf(
                    "Unhandled subtitle source '%s' for release '%s'",
                    $exception->getSourceName(),
                    $exception->getReleaseName()
                )
            );
            return;
        }

        $this->logger->error(
            sprintf(
                "A scrapping error occured on %s: %s",
                $event->getReleaseName(), $event->getMessage()
            )
        );
    }

    public function onNewWatchListItem( NewWatchListItemEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Added release '%s' with file '%s' to the Watch List",
                $event->getItem()->getOriginalName(),
                $event->getItem()->getFile()
            )
        );
    }

    public function onNewBestSubtitle( NewBestSubtitleEvent $event )
    {
        $this->logger->info(
            sprintf(
                "New best subtitle '%s' for release '%s'",
                $event->getSubtitle()->getName(), $event->getWatchListItem()->getOriginalName()
            )
        );
    }
}
