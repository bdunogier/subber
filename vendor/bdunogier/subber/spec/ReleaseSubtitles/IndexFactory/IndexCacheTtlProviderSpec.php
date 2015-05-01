<?php

namespace spec\BD\Subber\ReleaseSubtitles\IndexFactory;

use BD\Subber\ReleaseSubtitles\TestedReleaseSubtitle;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IndexCacheTtlProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\ReleaseSubtitles\IndexFactory\IndexCacheTtlProvider');
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\Index $index
     */
    function it_sets_ttl_to_one_hour_when_no_best_subtitles( $index )
    {
        $index->hasBestSubtitle()->willReturn( false );

        $this->get( $index )->shouldReturn( 3600 );
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\Index $index
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitle
     */
    function it_sets_ttl_to_two_hours_when_best_subtitles_with_zero_or_negative_rating( $index, $subtitle )
    {
        $index->hasBestSubtitle()->willReturn( true );
        $index->getBestSubtitle()->willReturn( $subtitle );

        $subtitle->getRating()->willReturn( -1 );

        $this->get( $index )->shouldReturn( 7200 );
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\Index $index
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitle
     */
    function it_sets_ttl_to_one_half_day_when_best_subtitle_with_positive_rating( $index, $subtitle )
    {
        $index->hasBestSubtitle()->willReturn( true );
        $index->getBestSubtitle()->willReturn( $subtitle );

        $subtitle->getRating()->willReturn( 2 );

        $this->get( $index )->shouldReturn( 43200 );
    }
}
