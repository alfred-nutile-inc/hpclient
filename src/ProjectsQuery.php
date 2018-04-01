<?php

namespace AlfredNutileInc\HPClient;

class ProjectsQuery extends BaseApi
{

    public function getAllProjects()
    {
        $results = $this->client->getClient()->request(
            'POST',
            "/v1/project/search",
            [
                'form_params' =>
                    ['status' => 'STATUS_ACTIVE']
            ]
        );

        return $this->transformResults($results);
    }
}
