<?php
namespace BD\Subber\Election\Ballot;

use BD\Subber\Election\Ballot;
use BD\Subber\Election\Result;

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
     * @return Result
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
     * @param string $targetName The filename we are voting for
     * @param $subtitle The candidate subtitle
     *
     * return int
     */
    private function gradeSubtitle( $targetName, $subtitle )
    {
        $grade = 0;

        $filename = strtolower( $subtitle['file'] );
        $language = strtolower( $subtitle['language'] );
        $extension = pathinfo( $filename, PATHINFO_EXTENSION );

        // english is strongly downgraded. It should never come first if there is VF
        if ( $language == 'vo' )
            $grade -= 20;

        // hearing impaired
        if ( strstr( $filename, '.hi.' ) )
            $grade -= 5;

        // type
        if ( $extension == 'ass' )
            $grade += 3;

        // tag/notag
        if ( strstr( $filename, '.tag' ) || strstr( $filename, '.notag' ) )
            $grade += 1;

        if ( $extension === 'zip' )
            $grade -= 50;

        return $grade;
    }
}
