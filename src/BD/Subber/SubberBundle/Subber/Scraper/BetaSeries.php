<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\SubberBundle\Subber\Scraper;

use BD\Subber\SubberBundle\BetaSeries\BetaSeriesClient;
use BD\Subber\SubberBundle\Subber\Scrapper;

class BetaSeries implements Scrapper
{
    public function __construct( BetaSeriesClient $client )
    {
        $this->client = $client;
    }

    public function scrap( $showName, $season, $episode )
    {
        // TODO: Implement scrap() method.
    }
}
