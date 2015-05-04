<?php

namespace BD\Subber\WatchList;

use BD\Subber\Event\NewWatchListItemEvent;
use DateTime;
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
        return $this->findAllActiveItems();
    }

    /**
     * @return \BD\Subber\WatchList\WatchListItem[]
     */
    public function findAllActiveItems()
    {
        return $this->_em
            ->createQuery('SELECT i FROM SubberWatchList:WatchListItem i WHERE i.rating <= 0 OR i.hasSubtitle = 0')
            ->getResult();
    }

    public function addItem(WatchListitem $item)
    {
        $event = new NewWatchListItemEvent($item);
        $this->eventDispatcher->dispatch('subber.watchlist.pre_new_item', $event);
        $this->_em->persist($item);
        $this->_em->flush();
        $this->eventDispatcher->dispatch('subber.watchlist.post_new_item', $event);
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher($eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @deprecated
     */
    public function setItemComplete(WatchListItem $item)
    {
        $this->setItemDone($item);
    }

    public function setItemDone(WatchListItem $item)
    {
        $item->setStatus(WatchListItem::STATUS_DONE);
        $item->setUpdatedAt(new DateTime());
        $this->_em->persist($item);
        $this->_em->flush();
    }

    /**
     * @return \BD\Subber\WatchList\WatchListItem
     */
    public function loadByReleaseName($releaseName)
    {
        return $this->findOneByOriginalName($releaseName);
    }

    /**
     * @return \BD\Subber\WatchList\WatchListItem
     */
    public function loadByLocalReleasePath($localReleasePath)
    {
        return $this->findOneByFile($localReleasePath);
    }

    public function remove(WatchListItem $item)
    {
        $this->_em->remove($item);
        $this->_em->flush();
    }

    public function update(WatchListItem $item)
    {
        $this->_em->persist($item);
        $this->_em->flush($item);
    }
}
