<?php

namespace spec\BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use BD\Subber\Release\ReleaseObject;
use BD\Subber\ReleaseSubtitles\Index;
use BD\Subber\ReleaseSubtitles\TestedSubtitle;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use BD\Subber\Subtitles\SubtitleObject;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexSpec extends ObjectBehavior
{

    /**
     * @param \BD\Subber\Release\Release $release
     */
    function let( Release $release )
    {
        $this->beConstructedWith( $release );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\ReleaseSubtitles\Index');
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitleOne
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitleTwo
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitleThree
     */
    function it_sorts_added_subtitles_in_descending_rating_order( $subtitleOne, $subtitleTwo, $subtitleThree )
    {
        $subtitleOne->getRating()->willReturn( 3 );
        $subtitleOne->isCompatible()->willReturn( true );

        $subtitleTwo->getRating()->willReturn( 1 );
        $subtitleTwo->isCompatible()->willReturn( true );

        $subtitleThree->getRating()->willReturn( 5 );
        $subtitleThree->isCompatible()->willReturn( true );

        $this->addSubtitle( $subtitleOne );
        $this->addSubtitle( $subtitleTwo );
        $this->addSubtitle( $subtitleThree );

        $this->getCompatibleSubtitles()->shouldHaveSubtitlesInOrder( [$subtitleThree, $subtitleOne, $subtitleTwo] );
    }

    function getMatchers()
    {
        return [
            'haveSubtitlesInOrder' => function ( $subtitles, array $expectedOrder ) {
                for ( $i = 0; $i < count( $subtitles ); $i++ ) {
                    if ( $subtitles[$i] != $expectedOrder[$i] ) {
                        return false;
                    }
                }
                return true;
            }
        ];
    }
}
