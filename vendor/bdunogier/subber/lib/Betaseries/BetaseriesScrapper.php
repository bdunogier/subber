<?php
namespace BD\Subber\Betaseries;

use BD\Subber\Event\ScrapErrorEvent;
use BD\Subber\Exceptions\UnknownSubtitleSourceException;
use BD\Subber\Release\Parser\ReleaseParserException;
use BD\Subber\Subtitles\Scrapper;
use InvalidArgumentException;
use Patbzh\BetaseriesBundle\Exception\PatbzhBetaseriesException;
use Patbzh\BetaseriesBundle\Model\Client as BetaseriesClient;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BetaseriesScrapper implements Scrapper
{
    /**
     * Betaseries client
     * @var \Patbzh\BetaseriesBundle\Model\Client
     */
    private $client;

    /** @var \BD\Subber\Betaseries\ZipSubtitleFilter */
    private $zipSubtitleFilter;

    /** @var \BD\Subber\Betaseries\ParserRegistry */
    private $parserRegistry;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param \Patbzh\BetaseriesBundle\Model\Client $client
     * @param \BD\Subber\Betaseries\ZipSubtitleFilter $zipSubtitleFilter
     */
    public function __construct( BetaseriesClient $client, ZipSubtitleFilter $zipSubtitleFilter, ParserRegistry $parserRegistry )
    {
        $this->client = $client;
        $this->zipSubtitleFilter = $zipSubtitleFilter;
        $this->parserRegistry = $parserRegistry;
    }

    /**
     * Scraps a filename, and returns subtitles for it if any.
     * @return \BD\Subber\Subtitles\Subtitle[]
     */
    public function scrap( $filename )
    {
        try {
            $data = $this->client->scrapeEpisode( $filename );
        } catch ( PatbzhBetaseriesException $e ) {
            $this->eventDispatcher->dispatch(
                'subber.scrap_error',
                new ScrapErrorEvent( $filename, $e->getMessage(), $e )
            );

            return array();
        }
        $subtitles = [];
        if ( isset( $data['episode']['subtitles'] ) )
        {
            $filteredSubtitles = $this->zipSubtitleFilter->filter( $data['episode']['subtitles'] );
            foreach ( $filteredSubtitles as $subtitleArray )
            {
                try {
                    $subtitle = $this->parserRegistry->getParser( $subtitleArray['source'] )->parseReleaseName( $subtitleArray['file'] );
                } catch ( ReleaseParserException $e ) {
                    $this->eventDispatcher->dispatch(
                        'subber.scrap_error',
                        new ScrapErrorEvent( $filename, "Parsing error: " . $e->getMessage(), $e )
                    );
                    continue;
                } catch ( InvalidArgumentException $e ) {
                    $this->eventDispatcher->dispatch(
                        'subber.scrap_error',
                        new ScrapErrorEvent(
                            $filename,
                            "Unknown source " . $subtitleArray['source'],
                            new UnknownSubtitleSourceException( $filename, $subtitleArray['source'] )
                        )
                    );
                    continue;
                }
                $subtitle->setUrl = $subtitleArray['url'];
                $subtitles[] = $subtitle;
            }
        }
        return $subtitles;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher( $eventDispatcher )
    {
        $this->eventDispatcher = $eventDispatcher;
    }
}
