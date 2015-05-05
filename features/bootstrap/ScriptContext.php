<?php
use BD\Subber\WatchList\WatchList;
use BD\Subber\WatchList\WatchListItem;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Process\Process;

/**
 * Defines application features from the specific context.
 */
class ScriptContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;

    private $scriptOutput;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct( array $options )
    {
        $this->options = $options;
    }

    /** @var array */
    private $options;

    /**
     * @Given that the Release :arg1 will be moved to :arg2 by the post-processor
     */
    public function thatTheReleaseWillBeMovedToByThePostProcessor($releaseName, $filePath)
    {
        $parsedReleaseName = 'ShowName - S1E10 - quality (group)';
        $showName = 'ShowName';
        $tvdbId = 123456;
        $seasonNumber = 1;
        $episodeNumber = 10;
        $quality = '1080P.HDTV.X264 (DIMENSION)';
        $destinationFolder = '/media/ShowName';

        $this->scriptOutput = <<< EOF
Loading config from /mnt/sgbg2T1/download/apps/sab-postprocessing/autoProcessTV.cfg
Opening URL: http://localhost:8081/home/postprocess/processEpisode?nzbName=$releaseName.nzb&quiet=1&dir=%2Fmedia%2Fdownload%2Fcomplete%2FTV%2F$releaseName
Processing folder: /media/download/complete/TV/$releaseName



Processing /media/download/complete/TV/$releaseName/$releaseName.mkv ($releaseName.nzb)

Parsed $releaseName into $parsedReleaseName [ABD: False]

Looking up $showName in the DB

Lookup successful, using tvdb id $tvdbId

Loading show object for tvdb_id $tvdbId

Retrieving episode object for {$seasonNumber}x{$episodeNumber}

Snatch history had a quality in it, using that: $quality

Sick Beard snatched this episode, marking it safe to replace

This download is marked as safe to replace existing file

Found release name $releaseName

Destination folder for this episode: $destinationFolder

Moving file from /media/complete/download/TV/$releaseName/$releaseName.mkv to $filePath

Deleted folder: /media/download/complete/TV/$releaseName

Processing succeeded for /media/complete/download/TV/$releaseName/$releaseName.mkv

EOF;

    }

    /**
     * @When the SickBeard Wrapper is executed
     */
    public function theSickbeardWrapperIsExecuted()
    {
        $temporaryFile = tempnam(sys_get_temp_dir(), 'subber');

        file_put_contents($temporaryFile, $this->scriptOutput);
        putenv("SUBBERTEST_SBWRAPPER_OUTPUT_FROM=$temporaryFile");
        putenv("SUBBER_CONFIG_SUBBER_HOST={$this->options['subber_host']}");
        exec('php app/scripts/sabToSickbeard_wrapper.php', $output, $exitCode);

        @unlink($temporaryFile);
        putenv("SUBBERTEST_SBWRAPPER_OUTPUT_FROM=");
    }

    /**
     * @Then post-processing is successful
     */
    public function postProcessingIsSuccessful()
    {
        return;
    }

    /**
     * @Then there is a WatchList item for Release :releaseName and file :filePath
     */
    public function thereIsAWatchlistItemForReleaseAndFile($releaseName, $filePath)
    {
        $this->watchList = $this->getContainer()->get('bd_subber.watchlist');
        if (($watchListItem = $this->watchList->loadByReleaseName($releaseName)) === null) {
            throw new Exception("No watch list item named $releaseName");
        }
        if ($watchListItem->getFile() !== $filePath) {
            throw new Exception(
                sprintf(
                    "The watchlist item was found, but name differs (expected %s, got %s)",
                    $filePath,
                    $watchListItem->getFile()
                )
            );
        }
    }
}
