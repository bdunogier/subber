<?php
namespace BD\Subber\Subtitles\Saver;

use BD\Subber\Event\SaveSubtitleErrorEvent;
use BD\Subber\Event\SaveSubtitleEvent;
use BD\Subber\Subtitles\Saver;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use ZipArchive;

/**
 * Saves subtitles to disk using copy and file_get_contents.
 *
 * Handles subtitles in zip files
 */
class BasicSaver implements Saver
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher( $eventDispatcher )
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save( Subtitle $subtitle, $forFile )
    {
        $subtitleSavePath = $this->computeSubtitleFileName( $forFile, $subtitle );

        if ( !is_writable( dirname( $subtitleSavePath ) ) || ( file_exists( $subtitleSavePath ) && !is_writable( $subtitleSavePath ) ) ) {
            if ( isset( $this->eventDispatcher ) ) {
                $this->eventDispatcher->dispatch(
                    "subber.save_subtitle_error",
                    new SaveSubtitleErrorEvent(
                        $subtitle,
                        $forFile,
                        $subtitleSavePath,
                        "Destination is not writable"
                    )
                );
            }
            return;
        }

        if ( !$this->isZipFile( $subtitle ) ) {
            copy( $subtitle->getUrl(), $subtitleSavePath );
            $this->dispatch( $subtitle, $subtitleSavePath );
            return;
        }

        $wantedSubName = false;
        foreach (explode( '&', parse_url( $subtitle->getUrl(), PHP_URL_QUERY ) ) as $queryPart) {
            list( $name, $value ) = explode( '=', $queryPart );
            if ($name == 'subber_zipfile') {
                $wantedSubName = urldecode( $value );
            }
        }

        // extract requested zip file
        $zipPath = tempnam( sys_get_temp_dir(), 'subberzip_' );
        copy( $subtitle->getUrl(), $zipPath );
        $zip = new ZipArchive;
        $zip->open( $zipPath );
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = (string)$zip->getNameIndex( $i );
            if ($filename === $wantedSubName) {
                file_put_contents( $subtitleSavePath, $zip->getFromName( $filename ) );
                $this->dispatch( $subtitle, $subtitleSavePath );
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
        return strstr( $subtitle->getUrl(), 'subber_zipfile' ) !== false;
    }

    private function computeSubtitleFileName( $videoFile, Subtitle $subtitle )
    {
        $videoExtension = pathinfo( $videoFile, PATHINFO_EXTENSION );
        $subtitleExtension = pathinfo( $subtitle->getName(), PATHINFO_EXTENSION );

        return preg_replace( "/\.$videoExtension$/", ".fr.$subtitleExtension", $videoFile );
    }

    private function dispatch( Subtitle $subtitle, $toFilePath )
    {
        if ( isset( $this->eventDispatcher ) ) {
            $this->eventDispatcher->dispatch(
                'subber.save_subtitle', new SaveSubtitleEvent( $subtitle, $toFilePath )
            );
        }
    }
}
