<?php

namespace spec\BD\Subber\ReleaseSubtitles\Matcher;

use BD\Subber\Release\Release;
use BD\Subber\Subtitles\Subtitle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SourceMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType( 'BD\Subber\ReleaseSubtitles\Matcher\SourceMatcher' );
    }

    function it_does_not_match_repacked_subtitles_with_non_repacked_releases()
    {
        $release = new Release(['isRepack' => true]);
        $subtitle = new Subtitle(['isRepack' => false]);
        $this->matches( $subtitle, $release )->shouldEqual( false );
    }

    function it_does_not_match_non_repacked_subtitles_with_repacked_releases()
    {
        $release = new Release(['isRepack' => false]);
        $subtitle = new Subtitle(['isRepack' => true]);
        $this->matches( $subtitle, $release )->shouldEqual( false );
    }

    function it_matches_repacked_subtitles_with_repacked_releases()
    {
        $release = new Release(['isRepack' => true]);
        $subtitle = new Subtitle(['isRepack' => true]);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }

    function it_matches_non_repacked_subtitles_with_non_repacked_releases()
    {
        $release = new Release(['isRepack' => false]);
        $subtitle = new Subtitle(['isRepack' => false]);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }

    function it_matches_hdtv_subtitles_with_hdtv_releases()
    {
        $release = new Release(['source' => 'hdtv']);
        $subtitle = new Subtitle(['source' => 'hdtv']);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }

    function it_matches_webdl_subtitles_with_webdl_releases()
    {
        $release = new Release(['source' => 'webdl']);
        $subtitle = new Subtitle(['source' => 'webdl']);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }

    function it_matches_webrip_subtitles_with_webrip_releases()
    {
        $release = new Release(['source' => 'webrip']);
        $subtitle = new Subtitle(['source' => 'webrip']);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }

    function it_matches_webdl_subtitles_with_webrip_releases()
    {
        $release = new Release(['source' => 'webrip']);
        $subtitle = new Subtitle(['source' => 'web-dl']);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }
}
