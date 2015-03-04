<?php
namespace BD\Subber\Subtitles;

/**
 * Rates a subtitle according to preferences
 */
interface Rater
{
    public function rate( Subtitle $subtitle );
}
