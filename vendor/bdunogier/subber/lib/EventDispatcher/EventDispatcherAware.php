<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\EventDispatcher;

use Symfony\Component\EventDispatcher\Event;

trait EventDispatcherAware
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher( $eventDispatcher )
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Dispatches the event if the dispatcher is set
     *
     * @param $eventName
     * @param \Symfony\Component\EventDispatcher\Event $event
     */
    protected function dispatch( $eventName, Event $event )
    {
        if ( isset( $this->eventDispatcher ) ) {
            $this->eventDispatcher->dispatch( $eventName, $event );
        }
    }
}
