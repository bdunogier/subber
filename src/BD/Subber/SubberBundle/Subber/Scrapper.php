<?php
namespace BD\Subber\SubberBundle\Scrapper;

interface Scrapper
{
    /**
     * @return SubtitleScrap[]
     */
    public function scrap( $showName, $season, $episode );
}
