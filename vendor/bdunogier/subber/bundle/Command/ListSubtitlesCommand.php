<?php
namespace BD\SubberBundle\Command;

use BD\Subber\Election\Ballot\BasicBallot;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lists subtitles for a video file
 */
class ListSubtitlesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:list-subtitles' );
        $this->addArgument( 'downloaded-release', InputArgument::REQUIRED, "The name of the downloaded release file" );
        $this->addOption( 'video-file', 'f', InputOption::VALUE_OPTIONAL, "The path to the video file the subtitle subtitles must be listed for", false );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $printSubtitleCallback = function ( Subtitle $subtitle ) use ( $output ) {
            $output->writeln( sprintf( "%s (%s)", $subtitle->name, strtoupper( $subtitle->language ) ) );
        };

        $downloadedRelease = $input->getArgument( 'downloaded-release' );

        $output->writeln( "Listing subtitles for $downloadedRelease" );

        $factory = $this->getContainer()->get( 'bd_subber.release_subtitles_collection_factory' );
        $collection = $factory->build( $downloadedRelease );

        if ( $collection->hasBestSubtitle() )
        {
            $output->writeln( "" );
            $output->write( "Best subtitle: " );
            $printSubtitleCallback( $collection->getBestSubtitle() );
        }

        $acceptableSubtitles = $collection->getCompatibleSubtitles();
        if ( count( $acceptableSubtitles ) > 1 )
        {

            $output->writeln( "" );
            $output->writeln( "Other compatible subtitles (" . count( $collection->getCompatibleSubtitles() ) . "):" );
            array_map( $printSubtitleCallback, $acceptableSubtitles );
        }

        $output->writeln( "" );
        $incompatibleSubtitles = $collection->getIncompatibleSubtitles();
        if ( count( $incompatibleSubtitles ) > 1 ) {
            $output->writeln( "Incompatible subtitles (" . count( $incompatibleSubtitles ) . "):" );
            array_map( $printSubtitleCallback, $incompatibleSubtitles );
        }
    }
}
