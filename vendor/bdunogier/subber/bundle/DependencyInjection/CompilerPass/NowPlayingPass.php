<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\SubberBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NowPlayingPass implements CompilerPassInterface
{
    public function process( ContainerBuilder $container )
    {
        $configuredConnector = $container->getParameter( 'subber.now_playing.connector' );

        if ($configuredConnector === null) {
            $container->setParameter( 'subber.enable_now_playing', false );
            return;
        }

        foreach ( $container->findTaggedServiceIds( 'subber.now_playing_connector' ) as $id => $tags )
        {
            foreach ( $tags as $tag )
            {
                if ( !isset( $tag['alias'] ) )
                    throw new \LogicException( 'The subber.now_playing_connector service tags requires an alias attribute' );
                if ( $tag['alias'] === $configuredConnector )
                {
                    $container->setAlias( 'subber.now_playing_connector', $id );
                    $container->setParameter( 'subber.enable_now_playing', true );
                    return;
                }
            }
        }

        throw new \LogicException( "The configured now playing connector $configuredConnector doesn't match any configured service" );
    }
}
