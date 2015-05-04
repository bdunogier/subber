<?php

namespace spec\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Subtitles\Subtitle;
use PhpSpec\ObjectBehavior;

/**
 * @method \BD\Subber\Subtitles\Subtitle parseReleaseName( $releaseName )
 */
class Addic7edParserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser');
    }

    public function it_parses_a_valid_release()
    {
        $release = $this->parseReleaseName('Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com');
        $release->shouldBeAnInstanceOf('BD\Subber\Subtitles\Subtitle');
    }

    public function it_throws_exception_on_unhandled_release()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringParseReleaseName('this is not valid');
    }

    public function getMatchers()
    {
        return [
            'haveProperties' => function (Subtitle $subtitle, array $properties) {
                foreach ($properties as $property => $value) {
                    if ($subtitle->$property !== $value) {
                        return false;
                    }
                }

                return true;
            },
        ];
    }
}
