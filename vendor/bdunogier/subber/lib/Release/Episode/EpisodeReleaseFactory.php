<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Release\Episode;

class EpisodeReleaseFactory
{
    /** @var \BD\Subber\Release\Episode\EpisodeMetadataFileParser */
    private $metadataFileparser;

    public function __construct( EpisodeMetadataFileParser $metadataFileparser )
    {
        $this->metadataFileparser = $metadataFileparser;
    }

    /**
     * @param string $localEpisodeFilePath
     *
     * @return \BD\Subber\Release\Episode\EpisodeRelease
     */
    public function buildFromLocalEpisode( $localEpisodeFilePath )
    {
        return new EpisodeRelease();
    }
}
