<?php

namespace BD\SubberBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Lists subtitles for a video file.
 */
class ParseVideoReleaseCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('subber:parse-video-release');
        $this->addArgument('release', InputArgument::REQUIRED, 'The name of a video-release');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $release = $input->getArgument('release');
        $parser = $this->getContainer()->get('bd_subber.release_parser.video');

        $output->writeln("Release: $release");
        $output->writeln('');

        $episode = $parser->parseReleaseName($release);
        $output->writeln('Parsed properties');
        foreach ($episode->toArray() as $field => $value) {
            $output->writeln("- $field: $value");
        }
    }
}
