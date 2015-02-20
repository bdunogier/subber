<?php
namespace BD\Subber\SubberBundle\Command;

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
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $tasksRepository = $this->getContainer()->get( 'bd_subber.tasks_repository' );
        $betaSeriesClient = $this->getContainer()->get( 'patbzh.betaseries.client' );

        /** @var \BD\Subber\SubberBundle\Entity\Task $task $task */
        foreach ( $tasksRepository->findAll() as $task )
        {
            print_r(
                $betaSeriesClient->scrapeEpisode( $task->getOriginalName() )
            );
        }
    }
}
