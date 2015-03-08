<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Subtitles;

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
        if ( $subtitle->resolution == '720p' && $subtitle->group == 'lol' ) {
            return [
                ['group' => 'lol', 'resolution' => '480p'],
                ['group' => null, 'resolution' => '720p']
            ];
        }
    }

    private function isInconsistent( Subtitle $subtitle )
    {
        return ( $subtitle->resolution == '720p' && $subtitle->group == 'lol' );
    }

    private function flattenByProperty( array $subtitlesList, $propertyName )
    {
        $newList = [];
        while ( $subtitle = array_shift($subtitlesList) ) {
            if ( is_array( $subtitle->$propertyName ) ) {
                foreach ($subtitle->$propertyName as $propertyValue) {
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
    private function forkSubtitle( Subtitle $subtitle, array $properties )
    {
        $fork = clone $subtitle;
        foreach ($properties as $propertyName => $propertyValue) {
            $fork->$propertyName = $propertyValue;
        }
        return $fork;
    }
}
