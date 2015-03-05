<?php
namespace tests\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\SubtitleRelease\BetaseriesParser;

class Addic7edParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser */
    private $parser;

    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease( $releaseName, $expectedProperties )
    {
        $parser = new BetaseriesParser();
        $release = $parser->parseReleaseName( $releaseName );

        foreach ( $expectedProperties as $property => $value )
        {
            self::assertAttributeEquals( $value, $property, $release );
        }
    }

    public function getValidReleases()
    {
        return [
            [
                'The Simpsons.S24E17.What Animated Women Want.LOL.720p.srt',
                [
                    'name' => 'The Simpsons.S24E17.What Animated Women Want.LOL.720p.srt',
                    'group' => 'lol',
                    'source' => 'hdtv',
                    'language' => 'fr'
                ]
            ],
            [
                'The Simpsons.S24E17.What Animated Women Want.WEB-DL.1080p.srt',
                [
                    'name' => 'The Simpsons.S24E17.What Animated Women Want.WEB-DL.1080p.srt',
                    'author' => 'betaseries',
                    'language' => 'fr',
                    'resolution' => '1080p',
                    'source' => 'web-dl'
                ]
            ],
        ];
    }
}
