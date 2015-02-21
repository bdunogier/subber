<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:scrap' );
        $this->setDescription( 'Scraps a release filename from betaseries, and returns episode data' );
        $this->addArgument( 'filename', InputArgument::REQUIRED, "Name of the file to scrap data for" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $client = $this->getContainer()->get( 'patbzh.betaseries.client' );
        print_r( $client->scrapeEpisode( $input->getArgument( 'filename' ) ) );
    }
}
