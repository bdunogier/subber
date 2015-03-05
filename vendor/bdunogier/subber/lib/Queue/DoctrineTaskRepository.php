<?php
namespace BD\Subber\Queue;

use BD\Subber\Event\QueueTaskEvent;
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

    public function setTaskComplete( Task $task )
    {
        $task->setStatus( 1 );
        $this->_em->persist( $task );
    }
}
