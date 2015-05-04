<?php

namespace spec\BD\Subber\Episode\EpisodeMetadataFileParser;

use BD\Subber\Episode\Episode;
use org\bovigo\vfs\vfsStream;
use PhpSpec\ObjectBehavior;

class XbmcNfoParserSpec extends ObjectBehavior
{
    public function let()
    {
        $this->setupVfs();
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
            ],
        ];
        vfsStream::setup('TV', null, $directoryStructure);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\Episode\EpisodeMetadataFileParser\XbmcNfoParser');
    }

    public function it_throws_an_exception_if_the_episode_metadatafile_does_not_exist()
    {
        $this
            ->shouldThrow('\InvalidArgumentException')
            ->during('parseFromEpisodeFilePath', [$this->invalidEpisodePath()]);
    }

    public function it_throws_an_exception_if_the_episode_metadatafile_is_not_valid()
    {
        $this
            ->shouldThrow('\InvalidArgumentException')
            ->during('parseFromEpisodeFilePath', [$this->invalidXmlEpisodePath()]);
    }

    public function it_parses_an_xbmc_nfo_xml_into_a_release()
    {
        $result = $this->parseFromEpisodeFilePath($this->validEpisodePath());

        $result->shouldBeAnEpisode();
        $result->shouldHaveProperty('showTitle', 'A Great TV Show');
        $result->shouldHaveProperty('episodeTitle', 'A great episode');
        $result->shouldHaveProperty('seasonNumber', 8);
        $result->shouldHaveProperty('episodeNumber', 17);
    }

    public function it_locates_image_files()
    {
        $result = $this->parseFromEpisodeFilePath($this->validEpisodePath());

        $result->shouldBeAnEpisode();
        $result->shouldHaveEpisodeThumbWithContents($this->episodeThumbPath(), 'episode thumbnail contents');
        $result->shouldHaveShowPosterWithContents($this->showPosterPath(), 'show poster contents');
    }

    public function getMatchers()
    {
        return [
            'haveProperty' => function (Episode $result, $propertyName, $propertyValue) {
                return $result->$propertyName === $propertyValue;
            },
            'haveShowPosterWithContents' => function (Episode $result, $expectedPath, $expectedContents) {
                return
                    $result->showPoster === $expectedPath &&
                    file_get_contents($result->showPoster) === $expectedContents;
            },
            'haveEpisodeThumbWithContents' => function (Episode $result, $expectedPath, $expectedContents) {
                return
                    $result->episodeThumb === $expectedPath &&
                    file_get_contents($result->episodeThumb) === $expectedContents;
            },
            'beAnEpisode' => function (Episode $result) {
                return $result instanceof Episode;
            },
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
        return vfsStream::url('TV/A Great TV Show/A Great TV Show.tbn');
    }

    public function episodeThumbPath()
    {
        return vfsStream::url('TV/A Great TV Show/A great episode.tbn');
    }
}
