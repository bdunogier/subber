<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\SubberBundle\Command;

use BD\Subber\SubberBundle\Entity\Task;
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
        $em = $this->getContainer()->get( 'doctrine.orm.entity_manager' );

        $task = new Task();

        $task->setFile( $filePathName = $input->getArgument( 'file' ) );

        $task->setOriginalName(
            $input->hasOption( 'original-name' )
            ? $input->getOption( 'original-name' )
            : basename( $filePathName )
        );

        $em->persist( $task );
        $em->flush();
    }
}
