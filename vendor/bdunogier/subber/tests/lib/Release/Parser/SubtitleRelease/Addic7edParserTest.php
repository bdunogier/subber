<?php
namespace tests\BD\Subber\Release\Parser\SubtitleRelease;

use BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser;

class Addic7edParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var \BD\Subber\Release\Parser\SubtitleRelease\Addic7edParser */
    private $parser;

    /**
     * @dataProvider getValidReleases
     */
    public function testParseValidRelease( $releaseName, $expectedProperties )
    {
        $parser = new Addic7edParser();
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
                'The Big Bang Theory - 08x18 - The Leftover Thermalization.DIMENSION.colored.English.HI.C.orig.Addic7ed.com.srt',
                [
                    'group' => 'dimension',
                    'isHearingImpaired' => true,
                    'hasTags' => true,
                    'author' => 'addic7ed',
                    'language' => 'en'
                ]
            ],
            [
                'Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com',
                [
                    'name' => 'Bitten - 02x04 - Dead Meat.KILLERS.English.C.orig.Addic7ed.com',
                    'group' => 'killers',
                    'author' => 'addic7ed',
                    'language' => 'en',
                ]
            ],
            [
                'Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com',
                [
                    'name' => 'Vikings - 03x02 - The Wanderer.WEB-DL-BS.English.C.orig.Addic7ed.com',
                    'group' => 'bs',
                    'author' => 'addic7ed',
                    'language' => 'en',
                    'source' => 'web-dl',
                ]
            ],
            [
                'Vikings - 03x01 - Mercenary.KILLERS-TRANSLATE.French.C.updated.Addic7ed.com',
                [
                    'name' => 'Vikings - 03x01 - Mercenary.KILLERS-TRANSLATE.French.C.updated.Addic7ed.com',
                    'group' => 'killers',
                    'author' => 'addic7ed',
                    'language' => 'fr'
                ]
            ],
            [
                'Vikings - 03x01 - Mercenary.KILLERS.English.HI.C.orig.Addic7ed.com',
                [
                    'name' => 'Vikings - 03x01 - Mercenary.KILLERS.English.HI.C.orig.Addic7ed.com',
                    'group' => 'killers',
                    'author' => 'addic7ed',
                    'language' => 'en',
                    'isHearingImpaired' => true
                ]
            ],
            [
                'Gotham - 01x17 - Red Hood.LOL.French.C.updated.Addic7ed.com',
                [
                    'name' => 'Gotham - 01x17 - Red Hood.LOL.French.C.updated.Addic7ed.com',
                    'group' => 'lol',
                    'author' => 'addic7ed',
                    'language' => 'fr'
                ]
            ],
            [
                'Modern Family - 06x16 - Connection Lost.LOL.French.orig.Addic7ed.com.srt',
                [
                    'name' => 'Modern Family - 06x16 - Connection Lost.LOL.French.orig.Addic7ed.com.srt',
                    'group' => 'lol',
                    'author' => 'addic7ed',
                    'language' => 'fr'
                ]
            ],
            [
                '12 Monkeys - 01x02 - Mentally Divergent.WEB-DL.French.C.orig.Addic7ed.com.srt',
                [
                    'name' => '12 Monkeys - 01x02 - Mentally Divergent.WEB-DL.French.C.orig.Addic7ed.com.srt',
                    'author' => 'addic7ed',
                    'source' => 'web-dl',
                    'language' => 'fr',
                    'group' => null
                ]
            ],
            [
                "Vikings - 03x03 - Warrior's Fate.REPACK-2HD.English.HI.C.orig.Addic7ed.com.srt",
                [
                    'name' => "Vikings - 03x03 - Warrior's Fate.REPACK-2HD.English.HI.C.orig.Addic7ed.com.srt",
                    'isHearingImpaired' => true,
                    'group' => '2hd',
                    'isRepack' => true,
                    'language' => 'en'
                ]
            ]
        ];
    }
}
