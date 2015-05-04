<?php

namespace spec\BD\Subber\ReleaseSubtitles;

use BD\Subber\Release\Release;
use PhpSpec\ObjectBehavior;

class IndexSpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\Release\Release $release
     */
    public function let(Release $release)
    {
        $this->beConstructedWith($release);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\ReleaseSubtitles\Index');
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitleOne
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitleTwo
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle $subtitleThree
     */
    public function it_sorts_added_subtitles_in_descending_rating_order($subtitleOne, $subtitleTwo, $subtitleThree)
    {
        $subtitleOne->getRating()->willReturn(3);
        $subtitleOne->isCompatible()->willReturn(true);

        $subtitleTwo->getRating()->willReturn(1);
        $subtitleTwo->isCompatible()->willReturn(true);

        $subtitleThree->getRating()->willReturn(5);
        $subtitleThree->isCompatible()->willReturn(true);

        $this->addSubtitle($subtitleOne);
        $this->addSubtitle($subtitleTwo);
        $this->addSubtitle($subtitleThree);

        $this->getCompatibleSubtitles()->shouldHaveSubtitlesInOrder([$subtitleThree, $subtitleOne, $subtitleTwo]);
    }

    public function getMatchers()
    {
        return [
            'haveSubtitlesInOrder' => function ($subtitles, array $expectedOrder) {
                for ($i = 0; $i < count($subtitles); $i++) {
                    if ($subtitles[$i] != $expectedOrder[$i]) {
                        return false;
                    }
                }

                return true;
            },
        ];
    }
}
