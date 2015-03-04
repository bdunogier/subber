<?php
namespace BD\Subber\Subtitles;

/**
 * Saves subtitles to disk
 */
interface SubtitleSaver
{
    /**
     * Save $subtitle for file $forFile
     * @param Subtitle $subtitle
     * @param string $forFile The file to save the subtitle for, usually the video
     */
    public function save( Subtitle $subtitle, $forFile );
}
