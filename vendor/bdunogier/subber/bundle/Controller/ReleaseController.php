<?php
namespace BD\SubberBundle\Controller;

use BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReleaseController extends Controller implements ContainerAwareInterface
{
    /** @var \BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory */
    private $factory;

    public function __construct( SubtitledEpisodeReleaseFactory $factory )
    {
        $this->factory = $factory;
    }

    public function showReleaseByNameAction( $releaseName )
    {
        return $this->render(
            'BDSubberBundle::release.html.twig',
            ['release' => $this->factory->buildFromReleaseName( $releaseName )]
        );
    }
}
