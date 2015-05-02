<?php
namespace BD\SubberBundle\Controller;

use BD\Subber\WatchList\WatchListItem;
use BD\Subber\WatchList\WatchList;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WatchListController extends Controller
{
    /** @var \BD\Subber\WatchList\WatchList */
    private $watchList;

    public function __construct( WatchList $watchList )
    {
        $this->item = $watchList;
    }

    public function addToWatchListAction( Request $request )
    {
        $itemArray = json_decode( $request->getContent(), true );

        $item = new WatchListItem();
        $item->setFile( $itemArray['path'] );
        $item->setOriginalName( $itemArray['original_name'] );
        $item->setCreatedAt( new \DateTime() );
        $item->setUpdatedAt( new \DateTime() );

        $this->item->addItem( $item );

        return new Response();
    }

    public function watchListFormAction()
    {

    }
}
