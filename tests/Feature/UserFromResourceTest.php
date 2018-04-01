<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use AlfredNutileInc\HPClient\UserFromResource;
use function GuzzleHttp\json_decode;

class UserFromResourceTest extends TestCase
{

    use UserFromResource;

    public function testResultsFromPayload()
    {
        $users = \File::get(base_path("tests/fixtures/resources.json"));
        $payload = \File::get(base_path("tests/fixtures/comments_report_limited.json"));

        $results = $this->transformResouceToResourceName(json_decode($payload, true), json_decode($users, true));

        $result = array_last($results);
        $this->assertArrayHasKey('user_name', $result);
        $this->assertEquals('Alfred Foo', $result['user_name']);
    }
}
