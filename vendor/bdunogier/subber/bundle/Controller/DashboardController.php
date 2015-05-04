<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subberbundle\Controller;

use BD\Subber\WatchList\WatchList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class DashboardController extends Controller implements ContainerAwareInterface
{
    /** @var \BD\Subber\WatchList\WatchList */
    private $watchList;

    public function __construct(WatchList $watchList)
    {
        $this->watchList = $watchList;
    }

    public function listAction()
    {
        return $this->render(
            'BDSubberBundle::watchlist.html.twig',
            ['items' => $this->watchList->findAllPendingItems()]
        );
    }
}
