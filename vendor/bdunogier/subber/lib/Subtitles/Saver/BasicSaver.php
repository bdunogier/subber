<?php
namespace BD\Subber\Subtitles\Saver;

use BD\Subber\Subtitles\Saver;
use BD\Subber\Subtitles\Subtitle;
use ZipArchive;

/**
 * Saves subtitles to disk using copy and file_get_contents.
 *
 * Handles subtitles in zip files
 */
class BasicSaver implements Saver
{
    public function save( Subtitle $subtitle, $forFile )
    {
        $subtitleSavePath = $this->computeSubtitleFileName( $forFile, $subtitle );
        if ( !$this->isZipFile( $subtitle ) ) {
            copy( $subtitle->getUrl(), $subtitleSavePath );
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

        return preg_replace( "/\.$videoExtension$/", ".$subtitleExtension", $videoFile );
    }
}
