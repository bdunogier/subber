<?php
namespace BD\SubberBundle\Command;

use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Matches a Subtitle and an Episode Release for compatibility
 */
class MatchReleasesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'subber:match-releases' );
        $this->addArgument( 'episode', InputArgument::REQUIRED, "Name of the episode release" );
        $this->addArgument( 'subtitle-source', InputArgument::REQUIRED, "Name of the subtitle release source" );
        $this->addArgument( 'subtitle', InputArgument::REQUIRED, "Name of the subtitle release" );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $releaseName = $input->getArgument( 'episode' );
        $subtitleName = $input->getArgument( 'subtitle' );
        $subtitleSourceName = $input->getArgument( 'subtitle-source' );

        $episodeParser = $this->getContainer()->get( 'bd_subber.release_parser.video' );
        $episode = $episodeParser->parseReleaseName( $releaseName );
        $output->writeln( "Subtitle:" );
        $this->printValueObject( $episode, $output );
        $output->writeln('');

        $subtitleParser = $this->getContainer()->get( 'bd_subber.release_parser.subtitles_registry' )->getParser( $subtitleSourceName );
        $subtitle = $subtitleParser->parseReleaseName( $subtitleName );
        $output->writeln( "Subtitle:" );
        $this->printValueObject( $subtitle, $output );
        $output->writeln('');

        $compatibilityMatcher = $this->getContainer()->get( 'bd_subber.subtitle_release.compatiblity_matcher' );
        $subtitle = new TestedSubtitleObject( $subtitle->toArray() );
        $subtitles = $compatibilityMatcher->match( $episode, [$subtitle] );
        $output->writeln(  $subtitles[0]->isCompatible() ? "COMPATIBLE" : "INCOMPATIBLE" );
    }

    private function printValueObject( Release $valueObject, OutputInterface $output )
    {
        foreach( $valueObject->toArray() as $field => $value )
        {
            if ( is_array( $value ) ) {
                $output->writeln( "- $field: [" . implode( ', ', $value ) . "]" );
            } elseif ( $value !== null ) {
                $output->writeln( "- $field: $value" );
            }
        }
    }
}
