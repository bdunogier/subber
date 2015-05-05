<?php

namespace spec\BD\Subber\Logger;

use BD\Subber\Event\NewBestSubtitleEvent;
use BD\Subber\Event\NewWatchListItemEvent;
use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\Event\ScrapErrorEvent;
use BD\Subber\Event\ScrapReleaseEvent;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;
use BD\Subber\WatchList\WatchListItem;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LoggerEventListenerSpec extends ObjectBehavior
{
    /**
     * @param \Monolog\Logger $logger
     */
    public function let($logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Logger\LoggerEventListener');
    }

    public function it_listens_to_save_subtitle_event()
    {
        $this->getSubscribedEvents()->shouldListenToEventWithMethod('subber.save_subtitle', 'onSaveSubtitle');
    }

    public function it_logs_an_info_on_save_subtitle(Logger $logger)
    {
        $logger->info(Argument::any())->shouldBeCalled();

        $this->onSaveSubtitle(
            new SaveSubtitleEvent(new SubtitleObject(), '/local/path')
        );
    }

    public function it_logs_an_info_on_scrap_release(Logger $logger)
    {
        $logger->info(Argument::any())->shouldBeCalled();

        $this->onScrapRelease(
            new ScrapReleaseEvent('release.name')
        );
    }

    public function it_logs_an_error_on_scrap_error(Logger $logger)
    {
        $logger->error(Argument::any())->shouldBeCalled();

        $this->onScrapError(
            new ScrapErrorEvent('release.name', 'this is the message')
        );
    }

    public function it_logs_an_info_when_WatchListItem_is_added(Logger $logger)
    {
        $logger->info(Argument::any())->shouldBeCalled();

        $this->onNewWatchListItem(
            new NewWatchListItemEvent(new WatchListItem())
        );
    }

    public function it_logs_an_info_on_new_best_subtitle(Logger $logger)
    {
        $logger->info(Argument::any())->shouldBeCalled();

        $this->onNewBestSubtitle(
            new NewBestSubtitleEvent(new WatchListItem(), new TestedSubtitleObject())
        );
    }

    public function getMatchers()
    {
        return [
            'listenToEventWithMethod' => function (array $events, $expectedEvent, $methodName) {
                if (!isset($events[$expectedEvent])) {
                    return false;
                }

                return $events[$expectedEvent][0] == $methodName;
            },
        ];
    }
}
