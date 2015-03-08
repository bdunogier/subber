<?php

namespace spec\BD\Subber\SubtitledEpisodeRelease;

use BD\Subber\Episode\Episode;
use BD\Subber\Episode\EpisodeMetadataFileParser;
use BD\Subber\Queue\Task;
use BD\Subber\Release\Release;
use BD\Subber\ReleaseSubtitles\Index as SubtitlesIndex;
use BD\Subber\ReleaseSubtitles\IndexFactory;
use BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeRelease;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SubtitledEpisodeReleaseFactorySpec extends ObjectBehavior
{
    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $subtitlesIndexFactory
     * @param \BD\Subber\Episode\EpisodeMetadataFileParser $episodeParser
     * @param \BD\Subber\Release\Parser\ReleaseParser $releaseParser
     * @param \BD\Subber\Queue\TaskRepository $taskRepository
     */
    function let( $subtitlesIndexFactory, $episodeParser, $releaseParser, $taskRepository )
    {
        $releaseParser->parseReleaseName( Argument::type( 'string' ) )->willReturn( new Release() );
        $this->beConstructedWith( $subtitlesIndexFactory, $episodeParser, $releaseParser, $taskRepository );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BD\Subber\SubtitledEpisodeRelease\SubtitledEpisodeReleaseFactory');
    }

    /**
     * @param \BD\Subber\Queue\TaskRepository $taskRepository
     * @param \BD\Subber\Release\Parser\ReleaseParser $releaseParser
     */
    function it_builds_from_a_release_name( $taskRepository, $releaseParser )
    {
        $taskRepository->loadByReleaseName( 'release name' )->willReturn( new Task() );
        $this->buildFromReleaseName( 'release name' )->shouldBeASubtitledEpisodeRelease();
    }

    /**
     * @param \BD\Subber\Queue\TaskRepository $taskRepository
     * @param \BD\Subber\Release\Parser\ReleaseParser $releaseParser
     */
    function it_builds_from_a_local_release_path( $taskRepository, $releaseParser )
    {
        $taskRepository->loadByLocalReleasePath( '/release/path' )->willReturn( new Task(['originalName' => 'release name']) );
        $this->buildFromLocalReleasePath( '/release/path' )->shouldBeASubtitledEpisodeRelease();
    }

    /**
     * @param \BD\Subber\Release\Parser\ReleaseParser $releaseParser
     */
    function it_builds_from_release_name_and_local_path()
    {
        $this->build( 'release name', '/release/path' )->shouldBeASubtitledEpisodeRelease();
    }

    /**
     * @param \BD\Subber\ReleaseSubtitles\IndexFactory $subtitlesIndexFactory
     */
    function it_gets_the_subtitles_index_when_building( $subtitlesIndexFactory )
    {
        $subtitlesIndex = new SubtitlesIndex([], []);

        $subtitlesIndexFactory->build( 'release name' )->willReturn( $subtitlesIndex );
        $result = $this->build( 'release name', '/release/path' );
        $result->shouldBeASubtitledEpisodeRelease();
        $result->shouldReferenceSubtitlesIndex( $subtitlesIndex );
    }

    /**
     * @param \BD\Subber\Episode\EpisodeMetadataFileParser $episodeParser
     */
    function it_parses_the_episode_metadata_when_building( $episodeParser )
    {
        $episode = new Episode();

        $episodeParser->parseFromEpisodeFilePath( '/release/path' )->willReturn( $episode );

        $result = $this->build( 'release name', '/release/path' );
        $result->shouldBeASubtitledEpisodeRelease();
        $result->shouldReferenceEpisode( $episode );
    }

    /**
     * @param \BD\Subber\Episode\EpisodeMetadataFileParser $episodeParser
     */
    function it_should_not_crash_if_the_episode_metadata_file_does_not_exist( $episodeParser)
    {
        $episodeParser->parseFromEpisodeFilePath( '/release/path' )->willThrow( new \InvalidArgumentException() );
        $this->build( 'release name', '/release/path' )->shouldBeASubtitledEpisodeRelease();
    }

    function getMatchers()
    {
        return [
            'beASubtitledEpisodeRelease' => function( $subject ) {
                return $subject instanceof SubtitledEpisodeRelease;
            },
            'referenceEpisode' => function ( SubtitledEpisodeRelease $subject, Episode $episode ) {
                return $subject->getEpisode() == $episode;
            },
            'referenceSubtitlesIndex' => function ( SubtitledEpisodeRelease $subject, SubtitlesIndex $subtitlesIndex ) {
                return $subject->getSubtitlesIndex() == $subtitlesIndex;
            }

        ];
    }
}
