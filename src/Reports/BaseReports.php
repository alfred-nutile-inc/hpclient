<?php
namespace AlfredNutileInc\HPClient\Reports;

use Facades\AlfredNutileInc\HPClient\ResourceApi;

class BaseReports
{

    protected $resources = [];

    public function getResourcesFromApi()
    {
        $this->resources = ResourceApi::getResources();
    }

    protected function sleepToNotOverDoApiLimits()
    {
        if (!\App::environment("testing")) {
            sleep(3); //api limits
        }
    }
}
