<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Project;

class ProjectService
{
  /**
   * @return array<string, mixed>
   */
  public function showViewData(Project $project): array
  {
    $project->load(['issues' => fn ($query) => $query->latest()]);

    return [
      'project' => $project,
      'blankIssue' => new Issue(),
      'issuesCount' => $project->issues->count(),
      'hasSchedule' => $project->start_date !== null || $project->deadline !== null,
    ];
  }
}
