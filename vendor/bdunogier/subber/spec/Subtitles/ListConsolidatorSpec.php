<?php

namespace spec\BD\Subber\Subtitles;

use BD\Subber\Subtitles\Subtitle;
use BD\Subber\Subtitles\SubtitleObject;
use PhpSpec\ObjectBehavior;

class ListConsolidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Subtitles\ListConsolidator');
    }

    public function it_forks_subtitles_with_multiple_groups()
    {
        $subtitles = [
            new SubtitleObject(['group' => ['lol', 'dimension']]),
        ];
        $result = $this->consolidate($subtitles);
        $result->shouldBeAnArrayOfSubtitles('lol');
        $result->shouldHaveOneSubtitleWithGroup('lol');
        $result->shouldHaveOneSubtitleWithGroup('dimension');
    }

    public function it_forks_subtitles_with_multiple_resolutions()
    {
        $result = $this->consolidate([new SubtitleObject(['resolution' => ['720p', '1080p']])]);
        $result->shouldBeAnArrayOfSubtitles();
        $result->shouldHaveOneSubtitleWithResolution('720p');
        $result->shouldHaveOneSubtitleWithResolution('1080p');
    }

    public function it_forks_subtitles_with_inconsistent_group_and_resolution()
    {
        $result = $this->consolidate([new SubtitleObject(['resolution' => '720p', 'group' => 'lol'])]);
        $result->shouldBeAnArrayOfSubtitles();
        $result->shouldHaveOneSubtitleWithGroupAndResolution('lol', '480p');
        $result->shouldHaveOneSubtitleWithResolution('720p');
    }

    public function it_sets_source_to_hdtv_for_lol_releases()
    {
        $result = $this->consolidate([new SubtitleObject(['group' => 'lol'])]);
        $result->shouldBeAnArrayOfSubtitles();
        $result->shouldHaveOneSubtitleWithGroupAndSource('lol', 'hdtv');
    }

    public function getMatchers()
    {
        return [
            'beAnArrayOfSubtitles' => function ($subject) {
                foreach ($subject as $object) {
                    if (!$object instanceof Subtitle) {
                        return false;
                    }
                }

                return true;
            },
            'haveOneSubtitleWithGroup' => function ($subject, $expectedGroup) {
                foreach ($subject as $subtitle) {
                    if ($subtitle->getGroup() == $expectedGroup) {
                        return true;
                    }
                }

                return false;
            },
            'haveOneSubtitleWithResolution' => function ($subject, $expectedResolution) {
                foreach ($subject as $subtitle) {
                    if ($subtitle->getResolution() == $expectedResolution) {
                        return true;
                    }
                }

                return false;
            },
            'haveOneSubtitleWithGroupAndResolution' => function ($subject, $expectedGroup, $expectedResolution) {
                foreach ($subject as $subtitle) {
                    if ($subtitle->getResolution() == $expectedResolution && $subtitle->getGroup() == $expectedGroup) {
                        return true;
                    }
                }

                return false;
            },
            'haveOneSubtitleWithGroupAndSource' => function ($subject, $expectedGroup, $expectedSource) {
                foreach ($subject as $subtitle) {
                    if ($subtitle->getSource() == $expectedSource && $subtitle->getGroup() == $expectedGroup) {
                        return true;
                    }
                }

                return false;
            },
        ];
    }
}
