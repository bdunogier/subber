<?php
namespace BD\SubberBundle\Command;

use BD\Subber\Election\Ballot\BasicBallot;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Gets subtitles for tasks
 */
class ProcessTasksCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:process-tasks' );
        $this->setDescription( 'Processes pending tasks for subtitles' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $tasksRepository = $this->getContainer()->get( 'bd_subber.tasks_repository' );
        $betaSeriesClient = $this->getContainer()->get( 'patbzh.betaseries.client' );
        $ballot = new BasicBallot();

        /** @var \BD\SubberBundle\Entity\Task $task $task */
        foreach ( $tasksRepository->findAll() as $task )
        {
            $output->writeln( "Scrapping subtitles for " . $task->getOriginalName() . " (" . $task->getFile() . ")" );

            $scrapData = $betaSeriesClient->scrapeEpisode( $task->getOriginalName() );
            if ( !isset( $scrapData['episode']['subtitles'] ) )
            {
                $output->writeln( "No subtitles were found" );
                continue;
            }

            $output->writeln( "Subtitles:" );
            array_map(
                function ( $subtitle ) use ( $output ) {
                    $output->writeln( $subtitle['file'] . " (" . $subtitle['language'] . ")" );
                },
                $scrapData['episode']['subtitles']
            );

            $subtitle = $ballot->vote( $task->getOriginalName(), $scrapData['episode']['subtitles'] );

            print_r( $subtitle );
            exit;
        }
    }

    private function pickBestSubtitle( $originalName, array $subtitles )
    {
        $bestGrade = -100;
        $bestSubtitle = null;

        foreach ( $subtitles as $subtitle )
        {
            $grade = 0;

            $filename = strtolower( $subtitle['file'] );
            $language = strtolower( $subtitle['language'] );
            $extension = pathinfo( $filename, PATHINFO_EXTENSION );

            // english is strongly downgraded. It should never come first if there is VF
            if ( $language == 'VO' )
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

            if ( $extension === '.zip' )
                $grade -= 50;

            if ( $grade > $bestGrade )
            {
                $bestGrade = $grade;
                $bestSubtitle = $subtitle;
            }
        }

        return $bestSubtitle;
    }
}
