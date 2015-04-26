<?php
use BD\Subber\WatchList\WatchList;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelDictionary;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
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
        $this->watchList = $this->getContainer()->get('bd_subber.watchlist');
        if (($item = $this->watchList->loadByReleaseName($releaseName)) !== null) {
            $this->watchList->remove($item);
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
            throw new Exception("Expected $httpCode" . isset($message) ? ": $message" : "");
        }
    }

    /**
     * @Then there is a WatchList item for Release :releaseName
     */
    public function thereIsAWatchListItemForRelease($releaseName)
    {
        $this->watchList = $this->getContainer()->get('bd_subber.watchlist');
        if (($episode = $this->watchList->loadByReleaseName($releaseName)) === null) {
            throw new Exception("No watch list item named $releaseName");
        }
    }
}
