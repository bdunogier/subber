<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Controller;

use BD\Subber\Queue\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class DashboardController extends Controller implements ContainerAwareInterface
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;
    /**
     * @var
     */
    private $enableNowPlaying;

    public function __construct( TaskRepository $repository, $enableNowPlaying )
    {
        $this->repository = $repository;
        $this->enableNowPlaying = $enableNowPlaying;
    }

    public function listAction()
    {
        return $this->render(
            'BDSubberBundle::tasks_list.html.twig',
            [
                'tasks' => $this->repository->findAllPendingTasks(),
                'enableNowPlaying' => $this->enableNowPlaying
            ]
        );
    }
}
