<?php
namespace BD\Subber\Subber;

use BD\Subber\Entity\TaskRepository;
use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\ReleaseSubtitles\IndexFactory;
use BD\Subber\Subtitles\Saver;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineQueueProcessor implements QueueProcessor
{
    /** @var \BD\Subber\Entity\TaskRepository */
    private $tasksRepository;

    /** @var \BD\Subber\ReleaseSubtitles\IndexFactory */
    private $indexFactory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \BD\Subber\Subtitles\Saver */
    private $saver;

    public function __construct(
        TaskRepository $tasksRepository,
        IndexFactory $collectionFactory,
        Saver $saver
    )
    {
        $this->tasksRepository = $tasksRepository;
        $this->indexFactory = $collectionFactory;
        $this->saver = $saver;
    }

    public function process()
    {
        /** @var \BD\Subber\Entity\Task $task */
        foreach( $this->tasksRepository->findAllPendingTasks() as $task )
        {
            // $output->writeln( "Processing " . $task->getOriginalName() );

            // see if we need to check again ?
            $collection = $this->indexFactory->build( $task->getOriginalName() );

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
                // update collections status & timestamp
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
