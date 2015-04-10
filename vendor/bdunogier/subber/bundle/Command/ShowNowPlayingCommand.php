<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShowNowPlayingCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:show-now-playing' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $nowPlaying = $this->getContainer()->get( 'bd_subber.now_playing.plex' );
        $output->writeln( "Now playing " . $nowPlaying->getNowPlayingFilePath() );
    }
}
