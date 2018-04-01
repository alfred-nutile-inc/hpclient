<?php

namespace AlfredNutileInc\HPClient;

class ResourceApi extends BaseApi
{

    public function getResources()
    {
        $results = $this->client->getClient()->request(
            'POST',
            "/v1/resource/search",
            [
                "form_params" => [
                    'status' => 'STATUS_ACTIVE'
                ]
            ]
        );


        return $this->transformResults($results);
    }
}
