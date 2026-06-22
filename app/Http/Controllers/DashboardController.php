<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $projectsCount = $user->projects()->count();

        $issuesQuery = Issue::query()
            ->whereHas('project', fn ($query) => $query->where('user_id', $user->id));

        $openIssuesCount = (clone $issuesQuery)->where('status', '!=', 'closed')->count();

        $overdueIssuesCount = (clone $issuesQuery)
            ->where('status', '!=', 'closed')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now())
            ->count();

        $recentIssues = (clone $issuesQuery)
            ->with(['project', 'tags'])
            ->latest()
            ->limit(6)
            ->get();

        $projects = $user->projects()
            ->withCount('issues')
            ->latest()
            ->limit(4)
            ->get();

        return view('dashboard', compact(
            'projectsCount',
            'openIssuesCount',
            'overdueIssuesCount',
            'recentIssues',
            'projects',
        ));
    }
}
