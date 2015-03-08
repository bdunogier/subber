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

    function it_matches_same_release_sources()
    {
        $release = new Release(['source' => 'hdtv']);
        $subtitle = new Subtitle(['source' => 'hdtv']);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }

    function it_does_not_match_different_sources()
    {
        $release = new Release(['source' => 'hdtv']);
        $subtitle = new Subtitle(['source' => 'web-dl']);
        $this->matches( $subtitle, $release )->shouldEqual( false );
    }

    function it_matches_webrip_with_webdl()
    {
        $release = new Release(['source' => 'webrip']);
        $subtitle = new Subtitle(['source' => 'web-dl']);
        $this->matches( $subtitle, $release )->shouldEqual( true );
    }
}
