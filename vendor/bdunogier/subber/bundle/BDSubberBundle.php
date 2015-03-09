<?php

namespace BD\SubberBundle;

use BD\SubberBundle\DependencyInjection\CompilerPass\NowPlayingPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BDSubberBundle extends Bundle
{
    public function build( ContainerBuilder $container )
    {
        parent::build( $container );
        $container->addCompilerPass( new NowPlayingPass() );
    }
}
