<?php
namespace spec\BD\Subber\Release\Episode;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EpisodeReleaseFactorySpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\Release\Episode\EpisodeMetadataFileParser $metadataParser
     */
    function it_is_initializable( $metadataParser )
    {
        $this->beConstructedWith( $metadataParser );
        $this->shouldHaveType( 'BD\Subber\Release\Episode\EpisodeReleaseFactory' );
    }

    function it_throws_an_exception_if_the_episode_metadatafile_does_not_exist()
    {
        $this->buildFromLocalFile( '/path/to/not_found_file' );
    }
}
