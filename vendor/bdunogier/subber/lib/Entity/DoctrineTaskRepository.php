<?php
namespace BD\Subber\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DoctrineTaskRepository extends EntityRepository implements TaskRepository
{
    public function findAllPendingTasks()
    {
        return $this->findByStatus( 0 );
    }

    public function addTask( Task $task )
    {
        $this->_em->persist( $task );
        $this->_em->flush();
    }
}
