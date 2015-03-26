<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueueCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:process-queue' );
        $this->setDescription( 'Processes the queue' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $queueProcessor = $this->getContainer()->get( 'bd_subber_subber.queue_processor' );
        $queueProcessor->process();
    }
}
