<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

use BD\Subber\Release\Release;

/**
 * Matches a Subtitle against a Release.
 *
 * A subtitle matches if it seems that it would fit the file in terms of source, quality, ...
 */
class SubtitleReleaseMatcher
{
    /**
     * @param \BD\Subber\Subtitles\Subtitle $subtitle
     * @param \BD\Subber\Release\Release $release
     *
     * @return bool
     */
    public function matches( Subtitle $subtitle, Release $release )
    {
        if ( $subtitle->getGroup() != $release->getGroup() ) {
            return false;
        }

        if ( $subtitle->getFormat() != $release->getFormat() ) {
            return false;
        }

        if ( $subtitle->getSource() != $release->getSource() ) {
            return false;
        }
        return true;
    }
}
