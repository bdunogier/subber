<?php

/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\ReleaseSubtitles;

/**
 * Builds up Index objects from a release.
 */
interface IndexFactory
{
    /**
     * @param string $releaseName
     *
     * @return \BD\Subber\ReleaseSubtitles\Index
     */
    public function build($releaseName);
}
