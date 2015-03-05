<?php
namespace BD\Subber\Event;

use BD\Subber\Queue\Task;
use Symfony\Component\EventDispatcher\Event;

class QueueTaskEvent extends Event
{
    /** @var Task */
    private $task;

    public function __construct( Task $task )
    {
        $this->task = $task;
    }

    /**
     * @return Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param Task $task
     */
    public function setTask( $task )
    {
        $this->task = $task;
    }
}
