<?php
namespace BD\SubberBundle\Controller;

use BD\Subber\Queue\Task;
use BD\Subber\Queue\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QueueController extends Controller
{
    /** @var \BD\Subber\Queue\TaskRepository */
    private $taskRepository;

    public function __construct( TaskRepository $taskRepository )
    {
        $this->taskRepository = $taskRepository;
    }

    public function addToQueueAction( Request $request )
    {
        $taskArray = json_decode( $request->getContent(), true );

        $task = new Task();
        $task->setFile( $taskArray['path'] );
        $task->setOriginalName( $taskArray['original_name'] );
        $task->setCreatedAt( new \DateTime() );
        $task->setUpdatedAt( new \DateTime() );

        $this->taskRepository->addTask( $task );

        return new Response();
    }
}
