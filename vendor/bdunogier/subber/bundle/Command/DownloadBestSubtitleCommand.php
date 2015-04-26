<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Gets subtitles for a release + file
 */
class DownloadBestSubtitleCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:download-best-subtitle' );
        $this->addArgument( 'release-filename', InputArgument::REQUIRED, "The filename of the downloaded release" );
        $this->addOption(
            'video-file',
            'f',
            InputOption::VALUE_OPTIONAL,
            "The path to the video file the subtitle should be saved for",
            false
        );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $releaseName = $input->getArgument( 'release-filename' );

        $factory = $this->getContainer()->get( 'bd_subber.release_subtitles.index_factory' );
        $downloader = $this->getContainer()->get( 'bd_subber.subtitle_saver' );

        $collection = $factory->build( $releaseName );

        if (!$collection->hasBestSubtitle()) {
            $output->writeln( "No best subtitle found for this release" );
            exit(1);
        }

        $subtitle = $collection->getBestSubtitle();
        $output->writeln( "Best subtitle for $releaseName is " . $subtitle->name );

        if ( $input->getOption( 'video-file' ) )
        {
            $videoFile = $input->getOption( 'video-file' );
            $output->writeln( "Saving subtitle for $videoFile" );
            $downloader->save( $subtitle, $videoFile );
        }
    }
}
