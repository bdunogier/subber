<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use BD\Subber\Entity\Task;
use BD\Subber\Subtitles\ReleaseSubtitlesCollection;
use BD\Subber\Subtitles\Subtitle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

class ProcessQueueCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:process-queue' );
        $this->setDescription( 'Processes the queue' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $queueProcessor = $this->getContainer()->get( 'bd_subber_subber_doctrine_queue_processor' );
        $queueProcessor->process();
    }
}
