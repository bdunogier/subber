<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use BD\Subber\WatchList\WatchListItem;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowWatchListCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:watchlist:show' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $watchlist = $this->getContainer()->get( 'bd_subber.watchlist' );

        $output->writeln( "Pending tasks" );
        $output->writeln( "" );

        /** @var WatchListItem $item */
        foreach ($watchlist->findAllPendingItems() as $item) {
            $output->writeln(
                sprintf( "- %s (%s)", $item->getOriginalName(), $item->getFile() )
            );
        }
    }
}
