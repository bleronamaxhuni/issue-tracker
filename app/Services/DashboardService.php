<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class DashboardService
{
  /**
   * @return array{
   *     projectsCount: int,
   *     openIssuesCount: int,
   *     overdueIssuesCount: int,
   *     recentIssues: Collection<int, Issue>,
   *     projects: Collection<int, \App\Models\Project>,
   * }
   */
  public function dataFor(User $user): array
  {
    $projectsCount = $user->projects()->count();

    $issuesQuery = Issue::query()->forUser($user);

    $openIssuesCount = (clone $issuesQuery)->where('status', '!=', 'closed')->count();

    $overdueIssuesCount = (clone $issuesQuery)
      ->where('status', '!=', 'closed')
      ->whereNotNull('due_date')
      ->whereDate('due_date', '<', now())
      ->count();

    $recentIssues = (clone $issuesQuery)
      ->with(['project', 'tags', 'assignees'])
      ->latest()
      ->limit(6)
      ->get()
      ->each(fn (Issue $issue) => $issue->setAttribute(
        'is_assigned_only',
        ! $issue->isOwnedBy($user),
      ));

    $projects = $user->projects()
      ->withCount('issues')
      ->latest()
      ->limit(4)
      ->get();

    return compact(
      'projectsCount',
      'openIssuesCount',
      'overdueIssuesCount',
      'recentIssues',
      'projects',
    );
  }
}
