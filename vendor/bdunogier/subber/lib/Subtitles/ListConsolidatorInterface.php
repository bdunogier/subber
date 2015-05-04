<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

interface ListConsolidatorInterface
{
    /**
     * @param Subtitle[] $subtitlesList
     *
     * @return Subtitle[]
     */
    public function consolidate(array $subtitlesList);
}
