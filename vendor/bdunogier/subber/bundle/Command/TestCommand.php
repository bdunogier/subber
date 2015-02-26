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
use ZipArchive;

class TestCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName( 'bd:test' );
        $this->addArgument( 'arg' );
    }

    public function execute( InputInterface $input, OutputInterface $output )
    {
        $subtitle = array(
            'name' => 'foo',
            'url' => 'bar',
            'language' => 'VF',
            'quality' => 1,
            'file' => $input->getArgument( 'arg' )
        );
        $zip = new ZipArchive;
        $zip->open( $input->getArgument( 'arg' ) );
        for( $i = 0; $i < $zip->numFiles; $i++ )
        {
            $filename = (string)$zip->getNameIndex( $i );
            $extension = pathinfo( $filename, PATHINFO_EXTENSION );

            // @todo makde configurable
            if ( $extension !== 'srt' && $extension !== 'ass' )
                continue;

            $newSubtitles[] = array_merge(
                $subtitle,
                [
                    'url' => "@TODO/" . rawurlencode( str_replace( '/', '#', $filename ) ),
                    'file' => $filename
                ]
            );
        }

        print_r( $newSubtitles );
    }
}
