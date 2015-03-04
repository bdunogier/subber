<?php
namespace BD\Subber\Subber;

use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\Subtitles\ReleaseSubtitlesCollectionFactory;
use BD\Subber\Subtitles\SubtitleSaver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineQueueProcessor implements QueueProcessor
{
    /** @var \Doctrine\ORM\EntityRepository */
    private $tasksRepository;

    /** @var \BD\Subber\Subtitles\ReleaseSubtitlesCollectionFactory */
    private $collectionFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \BD\Subber\Subtitles\SubtitleSaver */
    private $saver;

    public function __construct(
        EntityRepository $tasksRepository,
        ReleaseSubtitlesCollectionFactory $collectionFactory,
        SubtitleSaver $saver
    )
    {
        $this->tasksRepository = $tasksRepository;
        $this->collectionFactory = $collectionFactory;
        $this->saver = $saver;
    }

    public function process()
    {
        /** @var \BD\Subber\Entity\Task $task */
        foreach( $this->tasksRepository->findByStatus( 0 ) as $task )
        {
            // $output->writeln( "Processing " . $task->getOriginalName() );

            // see if we need to check again ?
            $collection = $this->collectionFactory->build( $task->getOriginalName() );

            if ( $collection->hasBestSubtitle() )
            {
                $subtitle = $collection->getBestSubtitle();
                if (isset( $this->eventDispatcher )) {
                    $this->eventDispatcher->dispatch(
                        'subber.save_subtitle',
                        new SaveSubtitleEvent( $subtitle->url, $task->getFile() )
                    );
                }
                $this->saver->save( $subtitle, $task->getFile() );
            }

            // update collections status & timestamp
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
