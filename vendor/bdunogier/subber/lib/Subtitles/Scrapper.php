<?php
namespace BD\Subber\Subtitles;

interface Scrapper
{
    /**
     * Scraps a filename, and returns subtitles for it if any.
     * @return Subtitle[]
     */
    public function scrap( $filename );
}
