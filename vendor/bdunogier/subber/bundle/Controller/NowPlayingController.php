<?php
namespace BD\SubberBundle\Controller;

use BD\Subber\NowPlaying\NowPlaying;
use BD\Subber\Queue\Task;
use BD\Subber\Queue\TaskRepository;
use BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NowPlayingController extends Controller implements ContainerAwareInterface
{
    /** @var \BD\Subber\NowPlaying\NowPlaying */
    private $nowPlaying;

    /** @var \BD\Subber\Queue\TaskRepository */
    private $taskRepository;

    public function __construct( TaskRepository $taskRepository, NowPlaying $nowPlaying )
    {
        $this->nowPlaying = $nowPlaying;
        $this->taskRepository = $taskRepository;
    }

    public function showNowPlayingAction()
    {
        $nowPlayingFile = $this->nowPlaying->getNowPlayingFilePath();
        if ($nowPlayingFile === null) {
            return new Response();
        }

        $task = $this->taskRepository->loadByLocalReleasePath( $nowPlayingFile );
        if ( !$task instanceof Task ) {
            return new Response();
        }
        return $this->render(
            'BDSubberBundle::now_playing.html.twig',
            ['release_name' => $task->getOriginalName()]
        );
    }
}
