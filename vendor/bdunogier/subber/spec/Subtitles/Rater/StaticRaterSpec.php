<?php

namespace spec\BD\Subber\Subtitles\Rater;

use BD\Subber\Subtitles\SubtitleObject;
use PhpSpec\ObjectBehavior;

class StaticRaterSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Subtitles\Rater\StaticRater');
    }

    /**
     * @param \BD\Subber\Subtitles\Subtitle $subtitle
     */
    public function it_gives_minus_5_if_language_is_english()
    {
        $this->rate(new SubtitleObject(['language' => 'en']))->shouldBe(-5);
    }
}
