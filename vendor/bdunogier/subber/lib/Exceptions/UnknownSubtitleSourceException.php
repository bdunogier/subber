<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Exceptions;

use Exception;

class UnknownSubtitleSourceException extends Exception
{
    /** @var string */
    private $releaseName;

    /** @var int */
    private $sourceName;

    public function __construct( $releaseName, $sourceName )
    {
        $this->releaseName = $releaseName;
        $this->sourceName = $sourceName;

        parent::__construct( "Unknown source $sourceName for release $releaseName" );
    }

    /**
     * @return int
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * @return string
     */
    public function getReleaseName()
    {
        return $this->releaseName;
    }
}
