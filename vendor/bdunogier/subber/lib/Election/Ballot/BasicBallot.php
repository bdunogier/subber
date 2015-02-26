<?php
namespace BD\Subber\Election\Ballot;

use BD\Subber\Election\Ballot;
use BD\Subber\Subtitles\Subtitle;
use ZipArchive;

/**
 * A very basic subtitle Ballot, based on procedural strings tests
 */
class BasicBallot implements Ballot
{
    /**
     * Runs the vote on a set of subtitles for originally downloaded file $originalName
     *
     * @param string $originalName Original download name
     * @param array $subtitles An array of subtitles, as returned by the Betaseries API
     *
     * @return \BD\Subber\Subtitles\Subtitle
     */
    public function vote( $originalName, array $subtitles )
    {
        $bestGrade = -100;
        $bestSubtitle = null;

        foreach ( $subtitles as $subtitle )
        {
            $grade = $this->gradeSubtitle( $originalName, $subtitle );

            if ( $grade > $bestGrade )
            {
                $bestGrade = $grade;
                $bestSubtitle = $subtitle;
            }
        }

        return $bestSubtitle;
    }

    /**
     * @param \BD\Subber\Subtitles\Subtitle[] $subtitles
     *
     * @return array The given subtitles array, with zip replaced by the "real" subtitle file(s) (e.g. inside the zip)
     */
    private function processZipFiles( array &$subtitles )
    {
        $newSubtitles = [];

        foreach ( $subtitles as $subtitle )
        {
            $extension = pathinfo( $subtitle->filename, PATHINFO_EXTENSION );
            if ( $extension !== 'zip' )
            {
                $newSubtitles[] = $subtitle;
                continue;
            }

            copy( $subtitle->url, $zipFilename = tempnam( sys_get_temp_dir(), 'zip' ) );
            $zip = new ZipArchive;
            $zip->open( $zipFilename );
            for( $i = 0; $i < $zip->numFiles; $i++ )
            {
                $filename = (string)$zip->getNameIndex( $i );
                $extension = pathinfo( $filename, PATHINFO_EXTENSION );

                // @todo makde configurable
                if ( $extension !== 'srt' && $extension !== 'ass' )
                    continue;

                $newSubtitles[] = array_merge(
                    $subtitle,
                    [
                        'url' => "@TODO/" . rawurlencode( str_replace( '/', '#', $filename ) ),
                        'file' => $filename
                    ]
                );
            }
        }

        return $newSubtitles;
    }

    /**
     * @param string $videoFilename The filename we are voting for
     * @param $subtitle The candidate subtitle

     * return int
     */
    private function gradeSubtitle( $videoFilename, Subtitle $subtitle )
    {
        $grade = 0;

        $subtitleFilename = strtolower( $subtitle->filename );
        $subtitleLanguage = strtolower( $subtitle->language );
        $subtitleExtension = pathinfo( $subtitleFilename, PATHINFO_EXTENSION );
        $videoFilename = strtolower( $videoFilename );

        // english is strongly downgraded. It should never come first if there is VF
        if ( $subtitleLanguage == 'vo' )
            $grade -= 20;

        if ( strstr( $videoFilename, 'web-dl' ) !== false )
        {
            $grade += strstr( $subtitleFilename, 'web-dl' ) !== false ? 5 : -5;
        }

        if ( strstr( $videoFilename, 'dimension' ) !== false )
        {
            $grade += strstr( $subtitleFilename, 'dimension' ) !== false ? 5 : -5;
        }

        if ( strstr( $videoFilename, 'lol' ) !== false )
        {
            $grade += strstr( $subtitleFilename, 'lol' ) !== false ? 5 : -5;
        }

        if ( strstr( $videoFilename, 'hdtv' ) !== false || strstr( $videoFilename, '720p' ) !== false)
        {
            $grade += strstr( $subtitleFilename, 'hdtv' ) || strstr( $subtitleFilename, '720p' ) !== false ? 5 : -5;
        }

        if ( strstr( $videoFilename, '1080p' ) !== false )
        {
            $grade += strstr( $subtitleFilename, '1080p' ) !== false ? 5 : -5;
        }

        // hearing impaired
        if ( strstr( $subtitleFilename, '.hi.' ) )
            $grade -= 5;

        // type
        if ( $subtitleExtension == 'ass' )
            $grade += 3;

        // tag/notag
        if ( strstr( $subtitleFilename, '.tag' ) || strstr( $subtitleFilename, '.notag' ) )
            $grade += 1;

        switch ( $subtitle->source )
        {
            case 'soustitres':  $grade += 2; break;
            case 'addic7ed':    $grade += 1; break;
            case 'tvsubtitles': $grade -= 2; break;
        }

        return $grade;
    }
}
