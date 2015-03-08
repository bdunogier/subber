<?php
namespace tests\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser;
use BD\Subber\Release\Parser\SubtitleRelease\TvsubtitlesParser;

class TvsubtitlesParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser */
    private $parser;

    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease( $releaseName, $expectedProperties )
    {
        $parser = new TvsubtitlesParser();
        $release = $parser->parseReleaseName( $releaseName );

        foreach ( ['author' => 'tvsubtitles'] + $expectedProperties as $property => $value )
        {
            self::assertAttributeEquals( $value, $property, $release );
        }
    }

    public function getValidReleases()
    {
        return [
            [
                'Gotham - 1x11 - Episode 11.HDTV.LOL.fr.srt',
                [
                    'name' => 'Gotham - 1x11 - Episode 11.HDTV.LOL.fr.srt',
                    'group' => 'lol',
                    'language' => 'fr',
                    'source' => 'hdtv'
                ]
        ],
            [
                'Gotham - 1x16 - The Blind Fortune Teller.HDTV.LOL+720p.DIMENSION.fr.srt',
                [
                    'name' => 'Gotham - 1x16 - The Blind Fortune Teller.HDTV.LOL+720p.DIMENSION.fr.srt',
                    'source' => 'hdtv',
                    'group' => ['dimension', 'lol'],
                    'language' => 'fr'
                ]
            ],
            [
                'Gotham - 1x16 - The Blind Fortune Teller.HDTV.LOL.en.srt',
                [
                    'name' => 'Gotham - 1x16 - The Blind Fortune Teller.HDTV.LOL.en.srt',
                    'group' => 'lol',
                    'language' => 'en',
                    'source' => 'hdtv'
                ]
            ],
            [
                "Two and a Half Men - 12x15-16 - Of Course He's Dead - Part One.720p HDTV.fr.srt",
                [
                    'name' => "Two and a Half Men - 12x15-16 - Of Course He's Dead - Part One.720p HDTV.fr.srt",
                    'group' => null,
                    'language' => 'fr',
                    'source' => 'hdtv',
                    'resolution' => '720p'
                ]
            ]
        ];
    }
}
