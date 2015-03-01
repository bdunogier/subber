<?php
namespace BD\Subber\Betaseries;

class ParserRegistry
{
    /** @var \BD\Subber\Release\Parser\ReleaseParser[] */
    private $parsers;

    public function __construct( array $parsers )
    {
        $this->parsers = $parsers;
    }

    /**
     * @return \BD\Subber\Release\Parser\ReleaseParser
     */
    public function getParser( $key )
    {
        if ( isset( $this->parsers[$key] ) ) {
            return $this->parsers[$key];
        }

        throw new \InvalidArgumentException( "No parser for key $key" );
    }
}
