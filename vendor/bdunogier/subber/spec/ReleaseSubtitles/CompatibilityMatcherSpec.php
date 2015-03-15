<?php
namespace spec\BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\Index as SubtitlesIndex;
use BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle;
use BD\Subber\Subtitles\Subtitle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompatibilityMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType( 'BD\Subber\ReleaseSubtitles\CompatibilityMatcher' );
    }

    function it_marks_as_incompatible_subtitles_with_a_known_source_different_from_the_release()
    {
        $release = new Release( ['source' => 'web-dl'] );
        $compatibleSubtitles = [
            new Subtitle( ['name' => 'b', 'source' => 'web-dl'] ),
            new Subtitle( ['name' => 'a', 'source' => 'web-dl'] )
        ];
        $incompatibleSubtitles = [
            new Subtitle( ['name' => 'c', 'source' => 'hdtv'] ),
            new Subtitle( ['name' => 'd', 'source' => 'hdtv'] )
        ];

        $result = $this->match( $release, array_merge( $compatibleSubtitles, $incompatibleSubtitles ) );
        $result->shouldBeAnArrayOfTestedReleaseSubtitles();
        $result->shouldContainIncompatibleSubtitles( ['c', 'd'] );
    }

    function it_marks_as_incompatible_subtitles_with_a_known_group_different_from_the_release()
    {
        $release = new Release( ['group' => 'killers'] );
        $subtitles = [
            new Subtitle( ['name' => 'a', 'group' => 'killers'] ),
            new Subtitle( ['name' => 'b', 'group' => 'lol'] ),
            new Subtitle( ['name' => 'c', 'group' => 'dimension'] )
        ];

        $result = $this->match( $release, $subtitles );
        $result->shouldBeAnArrayOfTestedReleaseSubtitles();
        $result->shouldContainIncompatibleSubtitles( ['b', 'c'] );
    }

    function it_marks_as_incompatible_non_repacked_subtitles_when_applicable()
    {
        $release = new Release( ['isRepack' => true] );
        $subtitles = [
            new Subtitle( ['name' => 'a', 'isRepack' => false] ),
            new Subtitle( ['name' => 'b', 'isRepack' => false] ),
            new Subtitle( ['name' => 'c', 'isRepack' => true] ),
            new Subtitle( ['name' => 'd', 'isRepack' => true] ),
        ];

        $result = $this->match( $release, $subtitles );
        $result->shouldBeAnArrayOfTestedReleaseSubtitles();
        $result->shouldContainIncompatibleSubtitles( ['a', 'b'] );
    }

    function getMatchers()
    {
        return [
            'beAnArrayOfTestedReleaseSubtitles' => function ( $result ) {
                if ( !is_array( $result ) ) {
                    return false;
                }

                foreach ( $result as $item ) {
                    if ( !$item instanceof TestedReleaseSubtitle ) {
                        return false;
                    }
                }
                return true;
            },
            // tests that $result contains ONLY $wantedSubtitles as Compatible
            'containCompatibleSubtitles' => function( array $subtitles, array $wantedSubtitlesNames ) {
                return $this->containSubtitles( $subtitles, $wantedSubtitlesNames, TestedReleaseSubtitle::COMPATIBLE );
            },
            // tests that $result contains ONLY $wantedSubtitles as Inompatible
            'containIncompatibleSubtitles' => function( array $subtitles, array $wantedSubtitlesNames ) {
                return $this->containSubtitles( $subtitles, $wantedSubtitlesNames, TestedReleaseSubtitle::INCOMPATIBLE );
            }
        ];
    }

    private function containSubtitles( array $testedSubtitles, array $wantedSubtitles, $wantedCompatibility )
    {
        foreach ($wantedSubtitles as $wantedSubtitleName) {
            $found = false;
            foreach ($testedSubtitles as $testedSubtitle) {
                if ( $testedSubtitle->name == $wantedSubtitleName && $testedSubtitle->getCompatibility() == $wantedCompatibility ) {
                    $found = true;
                }
            }
            if (!$found) {
                return false;
            }
        }
        return true;
    }
}
