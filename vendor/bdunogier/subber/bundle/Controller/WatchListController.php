<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Controller;

use BD\Subber\WatchList\WatchList;
use BD\Subber\WatchList\WatchListItem;
use BD\SubberBundle\Form\WatchListItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;

class WatchListController extends Controller implements ContainerAwareInterface
{
    /** @var \BD\Subber\WatchList\WatchList */
    private $watchList;

    public function __construct(WatchList $watchList)
    {
        $this->watchList = $watchList;
    }

    public function showFormAction(Request $request)
    {
        $form = $this->createForm(
            new WatchListItemType(),
            new WatchListItem(),
            ['action' => $this->generateUrl('bd_subber_item_form')]
        );

        return $this->render(
            'BDSubberBundle::add_watchlist_item_form.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction(Request $request)
    {
        $form = $this->createForm(new WatchListItemType(), new WatchListItem(), []);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $item = $form->getData();

            $this->watchList->addItem($item);

            return $this->redirectToRoute('bd_subber_item_view', ['releaseName' => $item->getName()]);
        }

        return $this->render(
            'AcmeAccountBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }

    public function viewAction($releaseName)
    {
        $this->render(
            'BDSubberBundle::release.html.twig',
            ['release' => $this->watchList->loadByReleaseName($releaseName)]
        );
    }
}
