<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\NowPlaying;

class FakeNowPlaying implements NowPlaying
{
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getNowPlayingFilePath()
    {
        return false;
    }
}
