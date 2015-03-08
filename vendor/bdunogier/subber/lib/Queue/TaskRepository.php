<?php
namespace BD\Subber\Queue;

interface TaskRepository
{
    /**
     * @return Task[]
     */
    public function findAllPendingTasks();

    public function addTask( Task $task );

    public function setTaskComplete( Task $task );

    /**
     * @return Task
     */
    public function loadByReleaseName( $releaseName );

    /**
     * @return Task
     */
    public function loadByLocalReleasePath( $localReleasePath );
}
