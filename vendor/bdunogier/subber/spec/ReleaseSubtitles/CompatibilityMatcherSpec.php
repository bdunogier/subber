<?php
namespace spec\BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\Release\ReleaseObject;
use BD\Subber\ReleaseSubtitles\Index as SubtitlesIndex;
use BD\Subber\ReleaseSubtitles\TestedSubtitle;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;
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
        $release = new ReleaseObject( ['source' => 'web-dl'] );
        $compatibleSubtitles = [
            new TestedSubtitleObject( ['name' => 'b', 'source' => 'web-dl'] ),
            new TestedSubtitleObject( ['name' => 'a', 'source' => 'web-dl'] )
        ];
        $incompatibleSubtitles = [
            new TestedSubtitleObject( ['name' => 'c', 'source' => 'hdtv'] ),
            new TestedSubtitleObject( ['name' => 'd', 'source' => 'hdtv'] )
        ];

        $result = $this->match( $release, array_merge( $compatibleSubtitles, $incompatibleSubtitles ) );
        $result->shouldContainIncompatibleSubtitles( ['c', 'd'] );
    }

    function it_marks_as_incompatible_subtitles_with_a_resolution_and_a_known_group_different_from_the_release()
    {
        $release = new ReleaseObject( ['group' => 'killers'] );
        $subtitles = [
            new TestedSubtitleObject( ['name' => 'a', 'group' => 'killers'] ),
            new TestedSubtitleObject( ['name' => 'b', 'group' => 'lol'] ),
            new TestedSubtitleObject( ['name' => 'c', 'group' => 'dimension', 'resolution' => '720p'] )
        ];

        $result = $this->match( $release, $subtitles );
        $result->shouldContainIncompatibleSubtitles( ['c'] );
    }

    function it_marks_as_incompatible_non_repacked_subtitles_when_applicable()
    {
        $release = new ReleaseObject( ['isRepack' => true] );
        $subtitles = [
            new TestedSubtitleObject( ['name' => 'a', 'isRepack' => false] ),
            new TestedSubtitleObject( ['name' => 'b', 'isRepack' => false] ),
            new TestedSubtitleObject( ['name' => 'c', 'isRepack' => true] ),
            new TestedSubtitleObject( ['name' => 'd', 'isRepack' => true] ),
        ];

        $result = $this->match( $release, $subtitles );
        $result->shouldContainIncompatibleSubtitles( ['a', 'b'] );
    }

    function it_doesnt_mark_as_incompatible_hdtv_subtitles_from_a_different_group_if_no_resolution_is_set()
    {
        $release = new ReleaseObject( ['source' => 'hdtv', 'group' => 'lol'] );
        $subtitles = [
            new TestedSubtitleObject( ['name' => 'a', 'source' => 'hdtv', 'group' => 'dimension'] ),
            new TestedSubtitleObject( ['name' => 'b', 'source' => 'web-dl', 'group' => 'dimension'] ),
        ];

        $result = $this->match( $release, $subtitles );
        $result->shouldNotContainIncompatibleSubtitles( ['a'] );
        $result->shouldContainIncompatibleSubtitles( ['b'] );
    }

    function getMatchers()
    {
        return [
            // tests that $result contains $wantedSubtitles as Compatible
            'containCompatibleSubtitles' => function( array $subtitles, array $wantedSubtitlesNames ) {
                return $this->containSubtitles( $subtitles, $wantedSubtitlesNames, TestedSubtitle::COMPATIBLE );
            },
            // tests that $result contains $wantedSubtitles as Incompatible
            'containIncompatibleSubtitles' => function( array $subtitles, array $wantedSubtitlesNames ) {
                return $this->containSubtitles( $subtitles, $wantedSubtitlesNames, TestedSubtitle::INCOMPATIBLE );
            },
        ];
    }

    private function containSubtitles( array $testedSubtitles, array $wantedSubtitles, $wantedCompatibility )
    {
        foreach ($wantedSubtitles as $wantedSubtitleName) {
            $found = false;
            foreach ($testedSubtitles as $testedSubtitle) {
                if ( $testedSubtitle->getName() == $wantedSubtitleName && $testedSubtitle->getCompatibility() == $wantedCompatibility ) {
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
