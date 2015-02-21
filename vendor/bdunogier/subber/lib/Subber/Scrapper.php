<?php
namespace BD\Subber\Subber;

interface Scrapper
{
    /**
     * @return SubtitleScrap[]
     */
    public function scrap( $showName, $season, $episode );
}
