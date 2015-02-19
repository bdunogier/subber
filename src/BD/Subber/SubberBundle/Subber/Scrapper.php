<?php
namespace BD\Subber\SubberBundle\Subber;

interface Scrapper
{
    /**
     * @return SubtitleScrap[]
     */
    public function scrap( $showName, $season, $episode );
}
