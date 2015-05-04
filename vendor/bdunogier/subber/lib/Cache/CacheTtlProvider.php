<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Cache;

/**
 * Calculates cache time-to-live (TTL).
 */
interface CacheTtlProvider
{
    /**
     * Returns the Cache TTL to use for $item.
     *
     * @param mixed $item
     *
     * @return int
     */
    public function get($item);
}
