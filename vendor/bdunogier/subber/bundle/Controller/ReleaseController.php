<?php

namespace BD\Subberbundle\Controller;

use BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ReleaseController extends Controller implements ContainerAwareInterface
{
    /** @var \BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory */
    private $factory;

    public function __construct(SubtitledEpisodeReleaseFactory $factory)
    {
        $this->factory = $factory;
    }

    public function showReleaseByNameAction($releaseName)
    {
        return $this->render(
            'BDSubberBundle::release.html.twig',
            ['release' => $this->factory->buildFromReleaseName($releaseName)]
        );
    }
}
