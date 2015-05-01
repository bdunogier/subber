<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\SubtitledEpisodeRelease;

use BD\Subber\Release\ReleaseObject;

class SubtitledEpisodeRelease extends ReleaseObject
{
    /** @var \BD\Subber\Episode\Episode */
    private $episode;

    /** @var \BD\Subber\ReleaseSubtitles\Index */
    private $subtitlesIndex;

    /**
     * @return \BD\Subber\Episode\Episode
     */
    public function getEpisode()
    {
        return $this->episode;
    }

    /**
     * @param \BD\Subber\Episode\Episode $episode
     */
    public function setEpisode( $episode )
    {
        $this->episode = $episode;
    }

    /**
     * @return \BD\Subber\ReleaseSubtitles\Index
     */
    public function getSubtitlesIndex()
    {
        return $this->subtitlesIndex;
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\Index $subtitlesIndex
     */
    public function setSubtitlesIndex( $subtitlesIndex )
    {
        $this->subtitlesIndex = $subtitlesIndex;
    }
}
