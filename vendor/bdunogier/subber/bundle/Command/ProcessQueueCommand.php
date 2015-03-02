<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use BD\Subber\Entity\Task;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class ProcessQueueCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:process-queue' );
        $this->setDescription( 'Processes the queue' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $tasksRepository = $this->getContainer()->get( 'bd_subber.tasks_repository' );
        $subtitlesCollectionFactory = $this->getContainer()->get( 'bd_subber.release_subtitles_collection_factory' );
        foreach( $tasksRepository->findByStatus( 0 ) as $task )
        {
            $output->writeln( "Processing " . $task->getOriginalName() );

            // see if we need to check again ?
            $collection = $subtitlesCollectionFactory->build( $task->getOriginalName() );

            if ( $collection->hasBestSubtitle() )
            {
                $subtitle = $collection->getBestSubtitle();
                $this->saveSubtitle(
                    $subtitle,
                    $this->computeSubtitleFileName( $task->getFile(), $subtitle )
                );
            }

            // update collections status & timestamp
        }
    }

    private function saveSubtitle( Subtitle $subtitle, $to )
    {
        if (strstr( $subtitle->url, 'subber_zipfile' ) === false) {
            copy( $subtitle->url, $to );
        } else {
            foreach (explode( '&', parse_url( $subtitle->url, PHP_URL_QUERY ) ) as $queryPart) {
                list( $name, $value ) = explode( '=', $queryPart );
                if ($name == 'subber_zipfile') {
                    $wantedSubName = $value;
                }
            }

            $zipPath = tempnam( sys_get_temp_dir(), 'subberzip_' );
            copy( $subtitle->url, $zipPath );
            $zip = new ZipArchive;
            $zip->open( $zipPath );
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = (string)$zip->getNameIndex( $i );
                if ($filename === $wantedSubName) {
                    file_put_contents( $to, $zip->getFromName( $filename ) );
                    break;
                }
            }
            $zip->close();
            unlink( $zipPath );
        }
    }

    private function computeSubtitleFileName( $videoFile, Subtitle $subtitle )
    {
        $videoExtension = pathinfo( $videoFile, PATHINFO_EXTENSION );
        $subtitleExtension = pathinfo( $subtitle->file, PATHINFO_EXTENSION );

        return preg_replace( "/\.$videoExtension$/", ".$subtitleExtension", $videoFile );
    }
}
