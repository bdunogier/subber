<?php
namespace BD\Subber\Queue;

use Doctrine\Common\Persistence\ObjectRepository;

interface TaskRepository
{
    /**
     * @return Task[]
     */
    public function findAllPendingTasks();

    public function addTask( Task $task );

    public function setTaskComplete( Task $task );
}
