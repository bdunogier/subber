<?php
namespace BD\SubberBundle\Controller;

use BD\Subber\NowPlaying\NowPlaying;
use BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NowPlayingController extends Controller implements ContainerAwareInterface
{
    /** @var \BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory */
    private $factory;
    /**
     * @var \BD\Subber\NowPlaying\NowPlaying
     */
    private $nowPlaying;

    public function __construct( SubtitledEpisodeReleaseFactory $factory, NowPlaying $nowPlaying )
    {
        $this->factory = $factory;
        $this->nowPlaying = $nowPlaying;
    }

    public function showNowPlayingReleaseAction()
    {
        $nowPlayingFile = $this->nowPlaying->getNowPlayingFilePath();
        if ($nowPlayingFile === false) {
            return new Response();
        }


        return $this->render(
            'BDSubberBundle::release.html.twig',
            ['release' => $this->factory->buildFromLocalReleasePath( $nowPlayingFile)]
        );
    }
}
