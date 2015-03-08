<?php
namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Parses a subtitle release
 */
class ParseSubtitleReleaseCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:parse-subtitle-release' );
        $this->addArgument( 'release', InputArgument::REQUIRED, "The name of a video-release" );
        $this->addOption( 'source', null, InputOption::VALUE_REQUIRED, "The subtitle's source site (betaseries, addic7ed, seriessub, soustitres)" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $release = $input->getArgument( 'release' );
        $source = $input->getOption( 'source' );

        $parser = $this->getContainer()->get( 'bd_subber.release_parser.subtitles_registry' )->getParser( $source );

        $output->writeln( "Release: $release" );
        $output->writeln( "" );

        $subtitleRelease = $parser->parseReleaseName( $release );
        $output->writeln( "Parsed properties" );
        foreach( $subtitleRelease as $field => $value )
        {
            $output->writeln( "- $field: $value" );
        }
    }
}
