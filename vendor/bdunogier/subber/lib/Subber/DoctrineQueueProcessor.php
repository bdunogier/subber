<?php
namespace BD\Subber\Subber\Doctrine;

use BD\Subber\Entity\Task;
use BD\Subber\Subber\QueueProcessor;
use BD\Subber\Subtitles\ReleaseSubtitlesCollectionFactory;
use BD\Subber\Subtitles\Subtitle;
use Doctrine\ORM\EntityRepository;
use ZipArchive;

class DoctrineQueueProcessor implements QueueProcessor
{
    /** @var \Doctrine\ORM\EntityRepository */
    private $tasksRepository;

    /** @var \BD\Subber\Subtitles\ReleaseSubtitlesCollectionFactory */
    private $collectionFactory;

    public function __construct( EntityRepository $tasksRepository, ReleaseSubtitlesCollectionFactory $collectionFactory )
    {
        $this->tasksRepository = $tasksRepository;
        $this->collectionFactory = $collectionFactory;
    }

    public function process()
    {
        /** @var \BD\Subber\Entity\Task $task */
        foreach( $this->tasksRepository->findByStatus( 0 ) as $task )
        {
            // $output->writeln( "Processing " . $task->getOriginalName() );

            // see if we need to check again ?
            $collection = $this->collectionFactory->build( $task->getOriginalName() );

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
        if ( !$this->isZipFile( $subtitle->url ) ) {
            copy( $subtitle->url, $to );
            return;
        }

        $wantedSubName = false;
        foreach (explode( '&', parse_url( $subtitle->url, PHP_URL_QUERY ) ) as $queryPart) {
            list( $name, $value ) = explode( '=', $queryPart );
            if ($name == 'subber_zipfile') {
                $wantedSubName = $value;
            }
        }

        // extract requested zip file
        $zipPath = tempnam( sys_get_temp_dir(), 'subberzip_' );
        copy( $subtitle->url, $zipPath );
        $zip = new ZipArchive;
        $zip->open( $zipPath );
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = (string)$zip->getNameIndex( $i );
            if ($filename === $wantedSubName) {
                file_put_contents( $to, $zip->getFromName( $filename ) );
                $saved = true;
                break;
            }
        }
        $zip->close();

        if ( !isset( $saved ) ) {
            throw new \Exception( "No file '$wantedSubName' extracted from zip file, something went wrong" );
        }

        unlink( $zipPath );
    }

    private function isZipFile( Subtitle $subtitle)
    {
        return strstr( $subtitle->url, 'subber_zipfile' ) !== false;
    }

    private function computeSubtitleFileName( $videoFile, Subtitle $subtitle )
    {
        $videoExtension = pathinfo( $videoFile, PATHINFO_EXTENSION );
        $subtitleExtension = pathinfo( $subtitle->file, PATHINFO_EXTENSION );

        return preg_replace( "/\.$videoExtension$/", ".$subtitleExtension", $videoFile );
    }
}
