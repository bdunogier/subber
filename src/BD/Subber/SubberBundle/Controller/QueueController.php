<?php
namespace BD\Subber\SubberBundle\Controller;

use BD\Subber\SubberBundle\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QueueController extends Controller
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    public function __construct( EntityManagerInterface $em )
    {
        $this->em = $em;
    }

    public function addToQueueAction( Request $request )
    {
        $taskArray = json_decode( $request->getContent(), true );

        $task = new Task();
        $task->setFile( $taskArray['path'] );
        $task->setOriginalName( $taskArray['original_name'] );

        $this->em->persist( $task );
        $this->em->flush();

        return new Response();
    }
}
