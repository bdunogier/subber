<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'bd:test' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $client = $this->getContainer()->get( 'patbzh.betaseries.client' );
        print_r( $client->scrapeEpisode( 'Modern Family S06E15.720p HDTV x264-DIMENSION' ) );
    }
}
