<?php

namespace AlfredNutileInc\HPClient;

class BaseApi
{

    /**
     * @var HubPlannerClient $client
     */
    protected $client;


    public function __construct(HubPlannerClient $client)
    {
        $this->client = $client;
    }

    protected function transformResults($results)
    {
        return json_decode($results->getBody(), true);
    }
}
