<?php
namespace BD\SubberBundle\Command;

use BD\Subber\Election\Ballot\BasicBallot;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

/**
 * Gets subtitles for tasks
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
        $factory = $this->getContainer()->get( 'bd_subber.downloaded_episode_subtitle_collection_factory' );
        $collection = $factory->getCollection( $input->getArgument( 'release-filename' ) );

        if (!$collection->hasBestSubtitle()) {
            $output->writeln( "No best subtitle found for this release" );
            exit(1);
        }

        $subtitle = $collection->getBestSubtitle();

        if ( $input->getOption( 'video-file' ) )
        {
            $output->writeln( "Saving best subtitle for " . $input->getOption( 'video-file' ) );
            $subtitleFilePath = $this->computeSubtitleFileName( $input->getOption( 'video-file' ), $subtitle->name );

            echo $subtitle->url . "\n";
            if ( strstr( $subtitle->url, 'subber_zipfile' ) === false )
            {
                copy( $subtitle->url, $subtitleFilePath );
            }
            else
            {
                foreach ( explode( '&', parse_url( $subtitle->url, PHP_URL_QUERY ) ) as $queryPart ) {
                    list( $name, $value ) = explode( '=', $queryPart );
                    if ( $name == 'subber_zipfile' ) {
                        $wantedSubName = $value;
                    }
                }

                $zipPath = tempnam( sys_get_temp_dir(), 'subberzip_' );
                copy( $subtitle->url, $zipPath );
                $zip = new ZipArchive;
                $zip->open( $zipPath );
                for( $i = 0; $i < $zip->numFiles; $i++ )
                {
                    $filename = (string)$zip->getNameIndex( $i );
                    if ( $filename === $wantedSubName ) {
                        file_put_contents( $subtitleFilePath, $zip->getFromName( $filename ) );
                        break;
                    }
                }
                $zip->close();
                unlink( $zipPath );
            }
        }
    }

    private function computeSubtitleFileName( $videoFile, $subtitleFile )
    {
        $videoExtension = pathinfo( $videoFile, PATHINFO_EXTENSION );
        $subtitleExtension = pathinfo( $subtitleFile, PATHINFO_EXTENSION );

        return preg_replace( "/\.$videoExtension$/", ".$subtitleExtension", $videoFile );
    }
}
