<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

use BD\Subber\ReleaseSubtitles\TestedSubtitle;
use BD\Subber\ReleaseSubtitles\TestedSubtitleObject;

class ListConsolidator implements ListConsolidatorInterface
{
    /**
     * @param Subtitle[] $subtitlesList
     *
     * @return Subtitle[]
     */
    public function consolidate( array $subtitlesList )
    {
        $subtitlesList = $this->flattenByProperty( $subtitlesList, 'group' );
        $subtitlesList = $this->flattenByProperty( $subtitlesList, 'resolution' );
        $subtitlesList = $this->forkInconsistentSubtitles( $subtitlesList );
        $subtitlesList = $this->guessSource( $subtitlesList );
        return $subtitlesList;
    }

    private function forkInconsistentSubtitles( array $subtitlesList )
    {
        $newList = [];
            while ( $subtitle = array_shift($subtitlesList) ) {
            if ( $this->isInconsistent( $subtitle ) ) {
                foreach ( $this->extractConsistentAttributes( $subtitle ) as $attributes ) {
                    $newList[] = $this->forkSubtitle( $subtitle, $attributes );
                }
            } else {
                $newList[] = $subtitle;
            }
        }

        return $newList;
    }

    /**
     * Extracts sets of properties required to make $subtitle consistent.
     *
     * To be used with ForkSubtitle
     *
     * @param Subtitle $subtitle
     * @return array
     */
    private function extractConsistentAttributes( Subtitle $subtitle )
    {
        if ( $subtitle->getResolution() == '720p' && $subtitle->getGroup() == 'lol' ) {
            return [
                ['group' => 'lol', 'resolution' => '480p'],
                ['group' => null, 'resolution' => '720p']
            ];
        }
    }

    private function isInconsistent( Subtitle $subtitle )
    {
        return ( $subtitle->getResolution() == '720p' && $subtitle->getGroup() == 'lol' );
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle[] $subtitlesList
     * @param $propertyName
     *
     * @return \BD\Subber\ReleaseSubtitles\TestedSubtitle[]
     */
    private function flattenByProperty( array $subtitlesList, $propertyName )
    {
        $newList = [];
        while ( $subtitle = array_shift($subtitlesList) ) {
            $properties = $subtitle->toArray();
            if ( is_array( $properties[$propertyName] ) ) {
                foreach ($properties[$propertyName] as $propertyValue) {
                    $newList[] = $this->forkSubtitle( $subtitle, [$propertyName => $propertyValue] );
                }
            }
            else
            {
                $newList[] = $subtitle;
            }
        }
        return $newList;
    }

    /**
     * @return Subtitle
     */
    private function forkSubtitle( Subtitle $subtitle, array $overrideProperties )
    {
        return new TestedSubtitleObject( $overrideProperties + $subtitle->toArray() );
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\TestedSubtitle[] $subtitles
     */
    private function guessSource( array $subtitles )
    {
        foreach ($subtitles as $subtitle) {
            if ($subtitle->getSource() !== null) {
                continue;
            }

            if ($subtitle->getGroup() === 'lol') {
                $subtitle->setSource( 'hdtv' );
                continue;
            }
        }

        return $subtitles;
    }
}
