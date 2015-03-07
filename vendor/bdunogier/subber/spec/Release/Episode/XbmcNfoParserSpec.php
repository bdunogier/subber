<?php
namespace spec\BD\Subber\Release\Episode;

use BD\Subber\Release\Episode\EpisodeRelease;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XbmcNfoParserSpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\Release\Episode\EpisodeMetadataFileParser $metadataParser
     */
    function let( $metadataParser )
    {
        $this->setupVfs();
        $this->beConstructedWith( $metadataParser );
    }

    private function setupVfs()
    {
        $episodeNfoContents = <<< XML
<episodedetails>
  <title>A great episode</title>
  <showtitle>A Great TV Show</showtitle>
  <season>8</season>
  <episode>17</episode>
</episodedetails>
XML;
        $directoryStructure = [
            'A Great TV Show' => [
                'A great episode.nfo' => $episodeNfoContents,
                'A Great TV Show.tbn' => 'show poster contents',
                'A great episode.tbn' => 'episode thumbnail contents',
                'invalid xml.nfo' => 'not xml',
            ]
        ];
        vfsStream::setup( 'TV', null, $directoryStructure );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType( 'BD\Subber\Release\Episode\XbmcNfoParser' );
    }

    function it_throws_an_exception_if_the_episode_metadatafile_does_not_exist()
    {
        $this
            ->shouldThrow('\InvalidArgumentException')
            ->during('parseFromEpisodeFilePath', [$this->invalidEpisodePath()]);
    }

    function it_throws_an_exception_if_the_episode_metadatafile_is_not_valid()
    {
        $this
            ->shouldThrow('\InvalidArgumentException')
            ->during('parseFromEpisodeFilePath', [$this->invalidXmlEpisodePath()]);
    }

    function it_parses_an_xbmc_nfo_xml_into_a_release()
    {
        $result = $this->parseFromEpisodeFilePath( $this->validEpisodePath() );

        $result->shouldBeAnInstanceOf('BD\Subber\Release\Episode\EpisodeRelease');
        $result->shouldHaveProperty( 'showTitle', 'A Great TV Show' );
        $result->shouldHaveProperty( 'episodeTitle', 'A great episode' );
        $result->shouldHaveProperty( 'seasonNumber', 8 );
        $result->shouldHaveProperty( 'episodeNumber', 17 );
    }

    function it_locates_image_files()
    {
        $result = $this->parseFromEpisodeFilePath( $this->validEpisodePath() );

        $result->shouldBeAnInstanceOf('BD\Subber\Release\Episode\EpisodeRelease');
        $result->shouldHaveEpisodeThumbWithContents( $this->episodeThumbPath(), 'episode thumbnail contents' );
        $result->shouldHaveShowPosterWithContents( $this->showPosterPath(), 'show poster contents' );
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function(EpisodeRelease $result, $propertyName, $propertyValue ) {
                return $result->$propertyName === $propertyValue;
            },
            'haveShowPosterWithContents' => function(EpisodeRelease $result, $expectedPath, $expectedContents ) {
                return
                    $result->showPoster === $expectedPath &&
                    file_get_contents( $result->showPoster ) === $expectedContents;
            },
            'haveEpisodeThumbWithContents' => function(EpisodeRelease $result, $expectedPath, $expectedContents ) {
                return
                    $result->episodeThumb === $expectedPath &&
                    file_get_contents( $result->episodeThumb ) === $expectedContents;
            }
        ];
    }

    public function invalidEpisodePath()
    {
        return vfsStream::url('TV/Unknown show/episode.mkv');
    }

    public function invalidXmlEpisodePath()
    {
        return vfsStream::url('TV/A Great TV Show/invalid xml.mkv');
    }

    public function validEpisodePath()
    {
        return vfsStream::url('TV/A Great TV Show/A great episode.mkv');
    }

    public function showPosterPath()
    {
        return vfsStream::url( 'TV/A Great TV Show/A Great TV Show.tbn' );
    }

    public function episodeThumbPath()
    {
        return vfsStream::url( 'TV/A Great TV Show/A great episode.tbn' );
    }
}

