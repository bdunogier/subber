<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use BD\Subber\Queue\Task;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowQueueCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:show-queue' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $repository = $this->getContainer()->get( 'bd_subber.tasks_repository' );

        $output->writeln( "Pending tasks" );
        $output->writeln( "" );

        /** @var Task $task */
        foreach ($repository->findAllPendingTasks() as $task) {
            $output->writeln(
                sprintf( "- %s (%s)", $task->getOriginalName(), $task->getFile() )
            );
        }
    }
}
