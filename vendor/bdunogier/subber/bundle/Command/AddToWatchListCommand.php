<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use DateTime;
use BD\Subber\WatchList\WatchListItem;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddToWatchListCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:watchlist:add-item' );
        $this->setDescription( 'Adds a file + release to the Watchlist for subbing' );

        $this->addArgument(
            'file',
            InputArgument::REQUIRED,
            'The path to the file'
        );

        $this->addOption(
            'original-name',
            'o',
            InputOption::VALUE_OPTIONAL,
            "The original name of the downloaded file"
        );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $repository = $this->getContainer()->get( 'bd_subber.watchlist' );

        $task = new WatchListItem();

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
