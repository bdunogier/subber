<?php

namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MonitorWatchListCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('subber:watchlist:monitor');
        $this->setDescription('Launches the watchlist monitor');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $watchListMonitor = $this->getContainer()->get('bd_subber.watchlist_monitor');
        $watchListMonitor->watchItems();
    }
}
