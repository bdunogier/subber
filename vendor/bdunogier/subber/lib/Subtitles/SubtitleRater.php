<?php
namespace BD\Subber\Subtitles;

/**
 * Rates a subtitle according to preferences
 */
interface SubtitleRater
{
    public function rate( Subtitle $subtitle );
}
