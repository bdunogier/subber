<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowQueuedReleaseCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:show-queued-release' );
        $this->setDescription( 'Shows a queued release information' );
        $this->addArgument( 'release-name', InputArgument::REQUIRED, "The release name" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $factory = $this->getContainer()->get( 'bd_subber.subtitled_episode_release_factory' );
        $release = $factory->buildFromReleaseName( $input->getArgument( 'release-name' ) );
        print_r( $release );
    }
}
