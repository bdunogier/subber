<?php
namespace BD\Subber\WatchList;

use BD\Subber\Event\QueueTaskEvent;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Doctrine ORM based watch list.
 */
class DoctrineWatchList extends EntityRepository implements WatchList
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @return \BD\Subber\WatchList\WatchListItem[]
     */
    public function findAllPendingItems()
    {
        return $this->findByStatus( 0 );
    }

    public function addItem( WatchListitem $item )
    {
        $event = new QueueTaskEvent( $item );
        $this->eventDispatcher->dispatch( 'subber.pre_queue_task', $event );
        $this->_em->persist( $item );
        $this->_em->flush();
        $this->eventDispatcher->dispatch( 'subber.post_queue_task', $event );
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher( $eventDispatcher )
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @deprecated
     */
    public function setItemComplete( WatchListItem $item )
    {
        $this->setItemDone( $item, null );
    }

    public function setItemDone( WatchListItem $item )
    {
        $item->setStatus( WatchListItem::STATUS_DONE );
        $item->setUpdatedAt( new DateTime() );
        $this->_em->persist( $item );
        $this->_em->flush();
    }

    /**
     * @return \BD\Subber\WatchList\WatchListItem
     */
    public function loadByReleaseName( $releaseName )
    {
        return $this->findOneByOriginalName( $releaseName );
    }

    /**
     * @return \BD\Subber\WatchList\WatchListItem
     */
    public function loadByLocalReleasePath( $localReleasePath )
    {
        return $this->findOneByFile( $localReleasePath );
    }
}
