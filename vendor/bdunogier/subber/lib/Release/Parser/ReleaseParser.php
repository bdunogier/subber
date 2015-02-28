<?php
namespace BD\Subber\Release\Parser;

/**
 * Parses a release name into a Release object
 */
interface ReleaseParser
{
    /**
     * @param string $episodeName
     * @return Release
     */
    public function parseReleaseName( $parseReleaseName );
}
