<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueuedReleaseCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:process-queued-release' );
        $this->setDescription( 'Processes a queued release' );
        $this->addArgument( 'release-name', InputArgument::REQUIRED, "The release name" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $factory = $this->getContainer()->get( 'bd_subber.subtitled_episode_release_factory' );
        $release = $factory->buildFromReleaseName( $input->getArgument( 'release-name' ) );
        print_r( $release );
    }
}
