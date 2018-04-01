<?php

namespace Tests\Feature;

use Tests\TestCase;
use AlfredNutileInc\HPClient\Reports\CommentsReport;
use Facades\AlfredNutileInc\HPClient\ProjectsQuery;
use Facades\AlfredNutileInc\HPClient\ResourceApi;
use Facades\AlfredNutileInc\HPClient\TimeSheets;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client;

class CommentsReportTest extends TestCase
{

    public function testDefaultReport()
    {

        $users = \File::get(base_path("tests/fixtures/resources.json"));
        $users = json_decode($users, true);
        ResourceApi::shouldReceive('getResources')->once()->andReturn($users);

        $projects = \File::get(base_path("tests/fixtures/projects_results_limited.json"));
        $projects = json_decode($projects, true);
        ProjectsQuery::shouldReceive('getAllProjects')->once()->andReturn($projects);

        $timesheets = \File::get(base_path("tests/fixtures/comments_report.json"));
        $timesheets = json_decode($timesheets, true);
        TimeSheets::shouldReceive('timeEntrySearch')->andReturn($timesheets);

        /** @var CommentsReport $report */
        $report = \App::make(CommentsReport::class);
        $results = $report->getReportAllProjectsAndDateRange();

        $this->assertCount(6, $results);
        $comment = array_first($results);

        $this->assertArrayHasKey("user_name", $comment);
        $this->assertEquals("Alfred Foo", $comment['user_name']);
    }
}
