<?php
namespace tests\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\SubtitleRelease\SoustitresParser;
use BD\Subber\Release\Release;

class SoustitresParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \BD\Subber\Release\Parser\ReleaseParser|\PHPUnit_Framework_MockObject_MockObject */
    private $episodeParserMock;

    public function setUp()
    {
        $this->episodeParserMock = $this->getMock( 'BD\Subber\Release\Parser\ReleaseParser' );
    }

    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease( $releaseName, $expectedProperties )
    {
        $parser = $this->getParser();
        $release = $parser->parseReleaseName( $releaseName );

        foreach ( ['author' => 'soustitres'] + $expectedProperties as $property => $value )
        {
            self::assertAttributeEquals( $value, $property, $release );
        }
    }

    public function testDelegationToEpisodeParser()
    {
        $this->episodeParserMock
            ->expects( $this->once() )
            ->method( 'parseReleaseName' )
            ->with( '12.monkeys.s01e02.mentally.divergent.720p.web-dl.dd5.1.h.264-bs' )
            ->willReturn(
                new Release(
                    [
                        'name' => '12.monkeys.s01e02.mentally.divergent.720p.web-dl.dd5.1.h.264-bs',
                        'group' => 'bs',
                        'source' => 'web-dl',
                        'resolution' => '720p',
                        'format' => 'x264'
                    ]
                )
            );
        $parser = $this->getParser();
        $release = $parser->parseReleaseName( '12.Monkeys.S01E02.Mentally.Divergent.720p.WEB-DL.DD5.1.H.264-BS.srt' );
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
            ],
            [
                'The.Big.Bang.Theory.S08E16.LOL.720p.DIMENSION.VF.ass',
                [
                    'resolution' => '720p',
                    'source' => 'hdtv',
                    'language' => 'fr',
                    'group' => 'dimension'
                ]
            ]
        ];
    }

    /**
     * @return \BD\Subber\Release\Parser\SubtitleRelease\SoustitresParser|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getParser()
    {
        return new SoustitresParser( $this->episodeParserMock );
    }
}
