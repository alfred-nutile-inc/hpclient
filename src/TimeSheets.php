<?php

namespace AlfredNutileInc\HPClient;

class TimeSheets extends BaseApi
{

    public function timeEntrySearch($query)
    {
        $results = $this->client->getClient()->request(
            'POST',
            "/v1/timeentry/search",
            [
                "form_params" => $query
            ]
        );


        return $this->transformResults($results);
    }
}
