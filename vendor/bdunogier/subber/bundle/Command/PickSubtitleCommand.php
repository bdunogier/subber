<?php
namespace BD\SubberBundle\Command;

use BD\Subber\Election\Ballot\BasicBallot;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Gets subtitles for tasks
 */
class PickSubtitleCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:pick-subtitle' );
        $this->addArgument( 'release-filename', InputArgument::REQUIRED, "The filename of the downloaded release" );
        $this->addOption( 'video-file', null, InputOption::VALUE_OPTIONAL, "The path to the video file the subtitle should be saved for", false );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $betaSeriesClient = $this->getContainer()->get( 'patbzh.betaseries.client' );
        $ballot = new BasicBallot();

        $filename = $input->getArgument( 'release-filename' );
        $output->writeln( "Scrapping subtitles for $filename" );

        $scrapData = $betaSeriesClient->scrapeEpisode( $filename );
        if ( !isset( $scrapData['episode']['subtitles'] ) )
        {
            $output->writeln( "No subtitles were found" );
        }

        $printSubtitleCallback = function ( $subtitle ) use ( $output ) {
            $output->writeln( $subtitle['file'] . " (" . $subtitle['language'] . ")" );
        };
        $output->writeln( "" );
        $output->writeln( "Candidates:" );
        array_map(
            $printSubtitleCallback,
            $scrapData['episode']['subtitles']
        );

        $subtitle = $ballot->vote( $filename, $scrapData['episode']['subtitles'] );
        $output->writeln( "" );
        $output->writeln( "Winner:" );
        $printSubtitleCallback( $subtitle );

        if ( $input->getOption( 'video-file' ) )
        {
            $output->writeln( "Saving best subtitle for " . $input->getOption( 'video-file' ) );
            copy(
                $subtitle['url'],
                $this->computeSubtitleFileName( $input->getOption( 'video-file' ), $subtitle['file'] )
            );
        }
    }

    private function computeSubtitleFileName( $videoFile, $subtitleFile )
    {
        $videoExtension = pathinfo( $videoFile, PATHINFO_EXTENSION );
        $subtitleExtension = pathinfo( $subtitleFile, PATHINFO_EXTENSION );

        return preg_replace( "/\.$videoExtension$/", ".$subtitleExtension", $videoFile );
    }
}
