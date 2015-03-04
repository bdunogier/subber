<?php
namespace BD\Subber\Subber;

use BD\Subber\Event\SaveSubtitleEvent;
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
            'subber.post_scrap_release' => ['onScrapRelease']
        ];
    }

    public function onSaveSubtitle( SaveSubtitleEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Saving subtitle '%s' for file '%s'",
                $event->getFrom(), $event->getTo()
            )
        );
    }

    public function onScrapRelease( ScrapReleaseEvent $event )
    {
        $this->logger->info(
            sprintf(
                "Scrapped %d subtitles for %s",
                $event->getReleaseName(),
                count( $event->getSubtitles() )
            )
        );
    }
}
