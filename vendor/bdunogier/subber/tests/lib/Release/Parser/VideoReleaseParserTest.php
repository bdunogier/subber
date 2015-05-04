<?php

namespace tests\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\VideoReleaseParser;

class VideoReleaseParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \BD\Subber\Release\Parser\VideoReleaseParser */
    private $parser;

    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease($releaseName, $expectedProperties)
    {
        $parser = new VideoReleaseParser();
        $release = $parser->parseReleaseName($releaseName);

        foreach ($expectedProperties as $property => $value) {
            self::assertAttributeEquals($value, $property, $release, "Subtitle::$property has wrong value");
        }
    }

    public function getValidReleases()
    {
        return [
            [
                'greys.anatomy.s11e15.repack.720p.hdtv.x264-dimension',
                [
                    'group' => 'dimension',
                    'source' => 'hdtv',
                    'resolution' => '720p',
                    'isRepack' => true,
                    'isProper' => false,
                ],
            ],
            [
                'Marvels Agents Of S H I E L D S02E11.720p HDTV x264-KILLERS',
                [
                    'group' => 'killers',
                    'format' => 'x264',
                    'source' => 'hdtv',
                    'resolution' => '720p',
                ],
            ],
            [
                'House Of Cards 2013 S03E03 Repack 720p Nf WEBRip Dd5.1 x264-NTB',
                [
                    'group' => 'ntb',
                    'format' => 'x264',
                    'source' => 'webrip',
                    'resolution' => '720p',
                    'isRepack' => true,
                ],
            ],
            [
                'Red.Dwarf.S10E06.BDRip.XviD-TASTETV',
                [
                    'group' => 'tastetv',
                    'format' => 'xvid',
                    'source' => 'bdrip',
                ],
            ],
            [
                'The.Simpsons.S26E15.REAL.REPACK.720p.HDTV.x264-KILLERS',
                [
                    'group' => 'killers',
                    'format' => 'x264',
                    'source' => 'hdtv',
                    'resolution' => '720p',
                    'isRepack' => true,
                ],
            ],

            [
                'The.Simpsons.S24E18.PROPER.720p.WEB-DL.DD5.1.H.264-LFF',
                [
                    'group' => 'lff',
                    'format' => 'x264',
                    'isProper' => true,
                    'source' => 'web-dl',
                    'resolution' => '720p',
                ],
            ],

            [
                'The Simpsons S24E17 1080p WEB-DL H 264 DD5 1-NTb',
                [
                    'group' => 'ntb',
                    'format' => 'x264',
                    'source' => 'web-dl',
                    'resolution' => '1080p',
                ],
            ],
        ];
    }
}
