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
class RestContext implements Context, SnippetAcceptingContext
{
    use KernelDictionary;

    /**
     * @var \BD\Subber\WatchList\WatchList
     */
    private $watchList;

    private $payload;

    /**
     * @var \GuzzleHttp\Message\ResponseInterface
     */
    private $response;

    /**
     * @var RequestException
     */
    private $requestException;

   /** @var array */
    private $options;

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

    /**
     * @Given there is no WatchList item for Release :releaseName
     */
    public function thereIsNoWatchListForRelease($releaseName)
    {
        if (($item = $this->getWatchList()->loadByReleaseName($releaseName)) !== null) {
            $this->getWatchList()->remove($item);
        }
    }

    /**
     * @When I create a JSON payload
     */
    public function iCreateAJsonPayload()
    {
        $this->payload = [];
    }

    /**
     * @When I set :arg1 to :arg2
     */
    public function iSetTo($arg1, $arg2)
    {
        $this->payload[$arg1] = $arg2;
    }

    /**
     * @When I POST the payload to :url
     */
    public function iPostThePayloadTo($url)
    {
        $client = new Client();
        try {
            $this->response = $client->post(
                $this->options['base_url'] . $url,
                [
                    'body' => json_encode( $this->payload ),
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]
            );
        }
        catch ( RequestException $e) {
            $this->requestException = $e;
        }
    }

    /**
     * @Then I get a :httpCode HTTP response
     */
    public function iGetAnHttpResponse($httpCode)
    {
        if (isset($this->requestException)) {
            if ($this->requestException->hasResponse()) {
                $responseCode = $this->requestException->getResponse()->getStatusCode();
            } else {
                $responseCode = 0;
                $message = $this->requestException->getMessage();
            }
        } else {
            $responseCode = $this->response->getStatusCode();
        }

        if ( $responseCode != $httpCode ) {
            throw new Exception("Expected $httpCode".(isset($message) ? ": $message" : ""));
        }
    }

    /**
     * @Then a WatchList item was created for Release :releaseName
     */
    public function aWatchListItemWasCreatedForRelease($releaseName)
    {
        $this->watchList = $this->getContainer()->get('bd_subber.watchlist');
        if (($episode = $this->watchList->loadByReleaseName($releaseName)) === null) {
            throw new Exception("No watch list item named $releaseName");
        }
    }

    /**
     * @Given there is a WatchList item for Release :releaseName
     **/
    public function thereIsAWatchlistItemForRelease($releaseName)
    {
        $this->getWatchList()->addItem(
            new WatchListItem(
                [ 'originalName' => $releaseName, 'file' => '/tmp/' . uniqid('subber') . '.mkv' ]
            )
        );
    }

    /**
     * @When I execute the WatchList Monitor
     */
    public function iExecuteTheWatchlistMonitor()
    {
        $process = new Process("php app/console subber:watchlist:monitor -v");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception("subber:watchlist:monitor failed with error:\n" . $process->getErrorOutput() );
        }
    }

    /**
     * @Then I see subtitles checked for :arg1
     */
    public function iSeeSubtitlesCheckedFor($arg1)
    {
        throw new PendingException();
    }

    /**
     * @return WatchList
     */
    private function getWatchList()
    {
        return $this->getContainer()->get( 'bd_subber.watchlist' );
    }
}
