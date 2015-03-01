<?php

namespace spec\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Subtitles\Subtitle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @method \BD\Subber\Subtitles\Subtitle parseReleaseName( $releaseName )
 */
class Addic7edParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser');
    }

    function it_parses_a_subtitle_filename()
    {
        $release = $this->parseReleaseName( 'Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com' );

        $release->shouldBeAnInstanceOf( 'BD\Subber\Subtitles\Subtitle' );

        $release->shouldHaveProperty( 'name', 'Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com' );
        $release->shouldHaveProperty( 'source', 'web-dl' );
        $release->shouldHaveProperty( 'language', 'en' );
        $release->shouldHaveProperty( 'author', 'addic7ed' );
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function( Subtitle $subtitle, $property, $value ) {
                return $subtitle->$property == $value;
            }
        ];
    }
}
