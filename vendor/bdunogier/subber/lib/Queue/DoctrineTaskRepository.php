<?php
namespace BD\Subber\Queue;

use BD\Subber\Event\QueueTaskEvent;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DoctrineTaskRepository extends EntityRepository implements TaskRepository
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    public function findAllPendingTasks()
    {
        return $this->findByStatus( 0 );
    }

    public function addTask( Task $task )
    {
        $event = new QueueTaskEvent( $task );
        $this->eventDispatcher->dispatch( 'subber.pre_queue_task', $event );
        $this->_em->persist( $task );
        $this->_em->flush();
        $this->eventDispatcher->dispatch( 'subber.post_queue_task', $event );
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher( $eventDispatcher )
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @deprecated
     */
    public function setTaskComplete( Task $task )
    {
        $this->setTaskDone( $task, null );
    }

    public function setTaskDone( Task $task )
    {
        $task->setStatus( Task::STATUS_DONE );
        $task->setUpdatedAt( new DateTime() );
        $this->_em->persist( $task );
        $this->_em->flush();
    }

    /**
     * @return Task
     */
    public function loadByReleaseName( $releaseName )
    {
        return $this->findOneByOriginalName( $releaseName );
    }

    /**
     * @return Task
     */
    public function loadByLocalReleasePath( $localReleasePath )
    {
        return $this->findOneByFile( $localReleasePath );
    }
}
