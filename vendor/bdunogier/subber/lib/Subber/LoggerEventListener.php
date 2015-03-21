<?php
namespace BD\Subber\Subber;

use BD\Subber\Event\QueueTaskEvent;
use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\Event\ScrapErrorEvent;
use BD\Subber\Event\ScrapReleaseEvent;
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
            'subber.post_queue_task' => ['onQueueTask'],
            'subber.scrap_error' => ['onScrapError']
        ];
    }

    public function onSaveSubtitle( SaveSubtitleEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Saving subtitle '%s' for file '%s'",
                $event->getSubtitle()->url, $event->getTo()
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

    public function onQueueTask( QueueTaskEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Queued release '%s' (%s)",
                $event->getTask()->getOriginalName(),
                $event->getTask()->getFile()
            )
        );
    }

    public function onScrapError( ScrapErrorEvent $event )
    {
        $this->logger->error(
            sprintf(
                "A scrapping error occured on %s: %s",
                $event->getReleaseName(), $event->getMessage()
            )
        );
    }
}
