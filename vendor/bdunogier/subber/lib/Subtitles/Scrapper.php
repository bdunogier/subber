<?php
namespace BD\Subber\Subtitles;

interface Scrapper
{
    /**
     * Scraps a filename, and returns subtitles for it if any.
     *
     * @param $filename
     *
     * @return \BD\Subber\Subtitles\Subtitle[]
     */
    public function scrap( $filename );
}
