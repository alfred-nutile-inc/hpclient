<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AlfredNutileInc\HPClient\HubPlannerClient;
use AlfredNutileInc\HPClient\TimeSheets;
use AlfredNutileInc\HPClient\ProjectsQuery;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class HubPlannerClientTest extends TestCase
{


    public function testingGettingAllProjects()
    {
        $mocked = \Mockery::mock(HubPlannerClient::class);
        $data = \File::get(base_path("tests/fixtures/projects_results.json"));
        $response = new Response(200, [], $data);
        $mocked->shouldReceive('getClient')->once()->andReturnSelf();
        $mocked->shouldReceive('request')->once()->andReturn($response);
        $this->app->instance(HubPlannerClient::class, $mocked);

        /** @var ProjectsQuery $client */
        $query = \App::make(ProjectsQuery::class);

        $results = $query->getAllProjects();

        $this->assertNotNull($results);
        $this->assertCount(2, $results);
    }

    public function testGettingProjectComments()
    {
        $mocked = \Mockery::mock(HubPlannerClient::class);
        $data = \File::get(base_path("tests/fixtures/comments_report.json"));
        $response = new Response(200, [], $data);
        $mocked->shouldReceive('getClient')->once()->andReturnSelf();
        $mocked->shouldReceive('request')->once()->andReturn($response);
        $this->app->instance(HubPlannerClient::class, $mocked);

        /** @var TimeSheets $client */
        $client = \App::make(TimeSheets::class);

        $results = $client->timeEntrySearch(
            [
                "project" => "5a27f6bcef32150d1188aa39",
                "date" => [
                    '$gte' => "2018-03-01",
                    '$lte' => "2018-03-31"
                ]
            ]
        );

        $this->assertNotNull($results);
        $this->assertCount(3, $results);
    }

    public function testGettingUserComments()
    {
        $mocked = \Mockery::mock(HubPlannerClient::class);
        $data = \File::get(base_path("tests/fixtures/results_from_user_query.json"));
        $response = new Response(200, [], $data);
        $mocked->shouldReceive('getClient')->once()->andReturnSelf();
        $mocked->shouldReceive('request')->once()->andReturn($response);
        $this->app->instance(HubPlannerClient::class, $mocked);

        /** @var TimeSheets $client */
        $client = \App::make(TimeSheets::class);

        $results = $client->timeEntrySearch(
            [
                "resource" => "56eb3dc1ed9236fa1735919f",
                "date" => [
                    '$gte' => "2018-03-01",
                    '$lte' => "2018-03-31"
                ]
            ]
        );

        $this->assertNotNull($results);
        $this->assertCount(2, $results);
    }
}
