<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Episode;

use InvalidArgumentException;

/**
 * Parses an Episode from a Metadata File.
 */
interface EpisodeMetadataFileParser
{
    /**
     * @return Episode
     *
     * @throws InvalidArgumentException if no metadata file could be found matching this episode file path
     */
    public function parseFromEpisodeFilePath($episodeFilePath);
}
