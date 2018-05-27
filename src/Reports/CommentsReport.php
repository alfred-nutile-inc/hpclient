<?php
namespace AlfredNutileInc\HPClient\Reports;

use Facades\AlfredNutileInc\HPClient\ProjectsQuery;
use Facades\AlfredNutileInc\HPClient\TimeSheets;
use Carbon\Carbon;
use AlfredNutileInc\HPClient\UserFromResource;
use Illuminate\Support\Collection;

class CommentsReport extends BaseReports
{

    use UserFromResource;

    /**
     * @var Collection $report
     */
    protected $report = [];


    protected $range = [];

    public function getReportAllProjectsAndDateRange($range = [])
    {
        $this->getResourcesFromApi();

        $this->range = $range;
        if (empty($this->range)) {
            $this->range['$gte'] = Carbon::now()->subDays(61)->toDateString();
        }

        $projects = ProjectsQuery::getAllProjects();

        $this->iterateOverProjects($projects);

        return collect($this->report)->sortByDesc('created_date')->toArray();
    }

    protected function iterateOverProjects($projects)
    {
        collect($projects)->map(
            function ($project) {
                $this->sleepToNotOverDoApiLimits();
                $project_trimmed =
                    $this->getTimeEntriesCommentsForProjectAndDateRange(
                    $project['_id'],
                    $this->range
                );
                $results = collect($project_trimmed)->transform(
                    function ($item) {
                        $date = Carbon::parse(array_get($item, 'createdDate'))->format("m-d-Y");
                        $transformed = [];
                        $transformed['user_name'] = array_get($item, 'user_name');
                        $transformed['created_date'] = $date;
                        $transformed['project_name'] = array_get($item, 'projectName');
                        $transformed['note'] = array_get($item, 'note');
                        return $transformed;
                    }
                )->toArray();
                $this->report = array_merge($this->report, $results);
            }
        );
    }

    public function getTimeEntriesCommentsForProjectAndDateRange(
        $project,
        array $range = []
    ) {
        $query = [];

        if ($project) {
            $query['project'] = $project;
        }

        if (!empty($range)) {
            if ($lte = array_get($range, '$lte')) {
                $range['$lte'] = $lte;
            }
            if ($gte = array_get($range, '$gte')) {
                $range['$gte'] = $gte;
            }
            $query['date'] = $range;
        }

        $results = TimeSheets::timeEntrySearch($query);

        $results = $this->transformResouceToResourceName($results, $this->resources);

        return $results;
    }
}
