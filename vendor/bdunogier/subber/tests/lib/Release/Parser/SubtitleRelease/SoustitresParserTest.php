<?php
namespace tests\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\SubtitleRelease\SoustitresParser;

class SoustitresParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease( $releaseName, $expectedProperties )
    {
        $parser = new SoustitresParser();
        $release = $parser->parseReleaseName( $releaseName );

        foreach ( ['author' => 'soustitres'] + $expectedProperties as $property => $value )
        {
            self::assertAttributeEquals( $value, $property, $release );
        }
    }

    public function getValidReleases()
    {
        return [
            [
                'Modern.Family.615.lol.FR.TAG.srt',
                [
                    'language' => 'fr',
                    'group' => 'lol',
                    'hasTags' => true
                ]
            ],
            [
                'Modern.Family.615.720p.1080p.web-dl.FR.ass',
                [
                    'name' => 'Modern.Family.615.720p.1080p.web-dl.FR.ass',
                    'resolution' => ['1080p', '720p'],
                    'source' => 'web-dl',
                    'language' => 'fr',
                    'subtitleFormat' => 'ass'
                ],
            ],
            [
                'Modern.Family.615.720p.dimension.FR.ass',
                [
                    'resolution' => '720p',
                    'source' => 'hdtv',
                    'group' => 'dimension',
                    'language' => 'fr',
                    'subtitleFormat' => 'ass'
                ]
            ],
            [
                'Modern.Family.615.720p.1080p.web-dl.FR.TAG.srt',
                [
                    'resolution' => ['1080p', '720p'],
                    'source' => 'web-dl',
                    'language' => 'fr',
                    'hasTags' => true
                ]
            ]
        ];
    }
}
