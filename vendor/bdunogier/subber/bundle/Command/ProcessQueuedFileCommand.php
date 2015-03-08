<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueuedFileCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:process-queued-file' );
        $this->setDescription( 'Processes a queued file' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
    }
}
