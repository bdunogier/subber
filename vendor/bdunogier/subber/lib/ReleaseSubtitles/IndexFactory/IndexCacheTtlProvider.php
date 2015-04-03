<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\Cache\CacheTtlProvider;

class IndexCacheTtlProvider implements CacheTtlProvider
{

    /**
     * Returns the Cache TTL to use for $item
     * @param \BD\Subber\ReleaseSubtitles\Index $index
     * @return int
     */
    public function get( $index )
    {
        if ( $index->hasBestSubtitle() ) {
            if ( $index->getBestSubtitle()->getRating() > 0 ) {
                $ttl = 43200; // 12 hours
            } else {
                $ttl = 7200; // 2 hours
            }
        } else {
            $ttl = 3600;
        }
        return $ttl;
    }
}
