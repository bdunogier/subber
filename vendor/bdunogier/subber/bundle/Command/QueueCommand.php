<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use BD\Subber\Entity\Task;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class QueueCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:queue' );
        $this->setDescription( 'Queues a file + release for subbing' );

        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'The path to the file to queue for subbing'
        );

        $this->addOption(
            'original-name',
            'o',
            InputOption::VALUE_OPTIONAL,
            "The original name of the downloaded file, if different"
        );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $repository = $this->getContainer()->get( 'bd_subber.tasks_repository' );

        $task = new Task();

        $task->setFile( $filePathName = $input->getArgument( 'file' ) );

        $task->setOriginalName(
            $input->hasOption( 'original-name' )
            ? $input->getOption( 'original-name' )
            : basename( $filePathName )
        );

        $task->setCreatedAt( new DateTime() );
        $task->setUpdatedAt( new DateTime() );

        $repository->addTask( $task );
    }
}
