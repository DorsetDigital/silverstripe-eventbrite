<?php

namespace DorsetDigital\SilverStripeEventBrite\Model;

use GuzzleHttp\Client;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

class EventBriteConnector
{
    use Injectable;
    use Configurable;

    /**
     * @config
     * @var string
     * Should include a trailing slash to allow the addition of relative URIs
     */
    private static $api_endpoint = 'https://www.eventbriteapi.com/v3/';

    /**
     * @config
     * @var
     */
    private static $personal_token;

    private $client;
    private $response;

    /**
     * EventBriteConnector constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if ($this->config()->get('personal_token') == '') {
            throw new \Exception('A personal token must be provided.');
        }
        $this->client = new Client(['base_uri' => $this->config()->get('api_endpoint')]);
    }

    /**
     * Get the list of events
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @todo add some nice error handling here so it doesn't kill the application on failure
     */
    public function getEventsList()
    {
        $vars = [
            'token' => $this->config()->get('personal_token')
        ];
        $this->response = $this->client->request('GET', 'users/me/events', ['query' => $vars]);
        if ($this->response->getStatusCode() > 300) {
            throw new \Exception('Error communicating with EventBrite');
        }

        return $this;
    }

    public function toArray() {
        $body = (string) $this->response->getBody();
        $obj = json_decode($body, true);
        return $obj;
    }


    public function getEventDescription($eventID) {
        $vars = [
            'token' => $this->config()->get('personal_token')
        ];
        $eventData = $this->client->request('GET', 'events/'.$eventID.'/description', ['query' => $vars]);
        if ($eventData->getStatusCode() > 300) {
            throw new \Exception('Error communicating with EventBrite');
        }
        $body = $eventData->getBody();
        $obj = json_decode($body, true);
        if (isset($obj['description'])) {
            return $obj['description'];
        }
    }
}
