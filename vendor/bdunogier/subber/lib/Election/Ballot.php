<?php
namespace BD\Subber\Election;

interface Ballot
{
    /**
     * Runs the vote on a set of subtitles for originally downloaded file $originalName
     *
     * @param string $originalName Original download name
     * @param array $subtitles An array of subtitles, as returned by the Betaseries API
     *
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function vote( $originalName, array $subtitles );
}
