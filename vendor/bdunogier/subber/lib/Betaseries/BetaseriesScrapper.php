<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Betaseries;

use BD\Subber\Subtitles\Scrapper;
use BD\Subber\Subtitles\Subtitle;
use Patbzh\BetaseriesBundle\Model\Client as BetaseriesClient;

class BetaseriesScrapper implements Scrapper
{
    /**
     * Betaseries client
     * @var \Patbzh\BetaseriesBundle\Model\
     */
    private $client;

    /**
     * @var \BD\Subber\Betaseries\ZipSubtitleFilter
     */
    private $zipSubtitleFilter;

    /**
     * @param \Patbzh\BetaseriesBundle\Model\Client $client
     * @param \BD\Subber\Betaseries\ZipSubtitleFilter $zipSubtitleFilter
     */
    public function __construct( BetaseriesClient $client, ZipSubtitleFilter $zipSubtitleFilter )
    {
        $this->client = $client;
        $this->zipSubtitleFilter = $zipSubtitleFilter;
    }

    /**
     * Scraps a filename, and returns subtitles for it if any.
     * @return array
     */
    public function scrap( $filename )
    {
        $data = $this->client->scrapeEpisode( $filename );
        $subtitles = [];
        if ( isset( $data['episode']['subtitles'] ) )
        {
            $filteredSubtitles = $this->zipSubtitleFilter->filter( $data['episode']['subtitles'] );
            foreach ( $filteredSubtitles as $subtitleArray )
            {
                $subtitle = new Subtitle();
                $subtitle->filename = $subtitleArray['file'];
                $subtitle->language = $subtitleArray['language'];
                $subtitle->source = $subtitleArray['source'];
                $subtitle->url = $subtitleArray['url'];

                $subtitles[] = $subtitle;
            }
        }
        return $subtitles;
    }
}
