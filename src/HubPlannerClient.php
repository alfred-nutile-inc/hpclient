<?php


namespace AlfredNutileInc\HPClient;

use GuzzleHttp\Client;

class HubPlannerClient
{

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function client()
    {
        return $this->getClient();
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }
}
