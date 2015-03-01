<?php
namespace tests\BD\Subber\Betaseries;

use BD\Subber\Betaseries\BetaseriesScrapper;
use BD\Subber\Subtitles\Subtitle;
use PHPUnit_Framework_TestCase;

class BetaseriesScrapperTest extends PHPUnit_Framework_TestCase
{
    private $betaseriesClientMock;
    private $zipSubtitleFilterMock;
    private $parserRegistryMock;
    private $subtitleReleaseParserMock;

    public function testScrapFromBetaseries()
    {
        $data = [
            'episode' => [
                'subtitles' => [
                    [
                        'id' => '495244',
                        'language' => 'VF',
                        'source' => 'tvsubtitles',
                        'quality' => '1',
                        'file' => 'Vikings_3x02_HDTV.KILLERS.fr.zip',
                        'url' => 'https://www.betaseries.com/srt/495244',
                        'date' => '2015-02-27 17:46:09',
                    ]
                ]
            ]
        ];
        $this->getBetaseriesClientMock()
            ->method( 'scrapeEpisode' )
            ->willReturn( $data );

        $subtitles = $this->getScrapper()->scrap( 'test' );

        self::assertEquals(
            [
                new Subtitle( ['name' => 'Vikings_3x02_HDTV.KILLERS.fr.zip', 'url' => 'https://www.betaseries.com/srt/495244' ] )
            ],
            $subtitles
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Patbzh\BetaseriesBundle\Model\Client
     */
    private function getBetaseriesClientMock()
    {
        if ( !isset( $this->betaseriesClientMock ) ) {
            $this->betaseriesClientMock = $this->getMock( 'Patbzh\BetaseriesBundle\Model\Client' );
        }
        return $this->betaseriesClientMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\BD\Subber\Betaseries\ZipSubtitleFilter
     */
    private function getZipSubtitleFilter()
    {
        if ( !isset( $this->zipSubtitleFilterMock ) ) {
            $this->zipSubtitleFilterMock = $this->getMock( 'BD\Subber\Betaseries\ZipSubtitleFilter' );
            $this->zipSubtitleFilterMock
                ->expects( $this->any() )
                ->method( 'filter' )
                ->willReturnArgument( 0 );
        }
        return $this->zipSubtitleFilterMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\BD\Subber\Betaseries\ParserRegistry
     */
    private function getParserRegistryMock()
    {
        if ( !isset( $this->parserRegistryMock ) ) {
            $this->parserRegistryMock = $this->getMockBuilder( 'BD\Subber\Betaseries\ParserRegistry' )->disableOriginalConstructor()->getMock();
            $this->parserRegistryMock
                ->expects( $this->any() )
                ->method( 'getParser' )
                ->will( $this->returnValue( $this->getSubtitleReleaseParserMock() ) );
        }
        return $this->parserRegistryMock;
    }

    private function getSubtitleReleaseParserMock()
    {
        if ( !isset( $this->subtitleReleaseParserMock ) ) {
            $this->subtitleReleaseParserMock = $this->getMock( '\BD\Subber\Release\Parser\ReleaseParser' );
            $this->subtitleReleaseParserMock
                ->expects( $this->any() )
                ->method( 'parseReleaseName' )
                ->will( $this->returnCallback( function( $releaseName ) { return new Subtitle( ['name' => $releaseName] ); }  ) );
        }
        return $this->subtitleReleaseParserMock;
    }

    private function getScrapper()
    {
        return new BetaseriesScrapper(
            $this->getBetaseriesClientMock(),
            $this->getZipSubtitleFilter(),
            $this->getParserRegistryMock()
        );
    }
}
