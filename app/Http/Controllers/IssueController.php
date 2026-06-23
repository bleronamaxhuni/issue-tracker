<?php

namespace App\Http\Controllers;

use App\Http\Requests\Issue\StoreIssueRequest;
use App\Http\Requests\Issue\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Services\IssueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function __construct(private IssueService $issues) {}

    public function index(Request $request): View|JsonResponse
    {
        $issues = $this->issues->paginatedForRequest($request);
        $filters = $this->issues->filtersFromRequest($request);
        $hasActiveFilters = $this->issues->hasActiveFilters($filters);

        if ($request->wantsJson()) {
            return response()->json([
                'html' => view('issues.partials.results', compact('issues', 'filters', 'hasActiveFilters'))->render(),
                'count' => $issues->total(),
                'count_label' => trans_choice(':count issue|:count issues', $issues->total(), ['count' => $issues->total()]),
            ]);
        }

        $tags = Tag::query()
            ->orderBy('name')
            ->get();

        return view('issues.index', compact('issues', 'tags', 'filters', 'hasActiveFilters'));
    }

    public function show(Issue $issue): View
    {
        $this->authorize('view', $issue);

        return view('issues.show', $this->issues->showViewData($issue, auth()->user()));
    }

    public function store(StoreIssueRequest $request, Project $project): RedirectResponse
    {
        $issue = $project->issues()->create($request->validated());

        return redirect()
            ->route('issues.show', $issue)
            ->with('status', 'issue-created');
    }

    public function update(UpdateIssueRequest $request, Issue $issue): RedirectResponse
    {
        $issue->update($request->validated());

        return redirect()
            ->route('issues.show', $issue)
            ->with('status', 'issue-updated');
    }

    public function destroy(Issue $issue): RedirectResponse
    {
        $this->authorize('delete', $issue);

        $project = $issue->project;
        $issue->delete();

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'issue-deleted');
    }

    public function attachTag(Issue $issue, Tag $tag): JsonResponse
    {
        $this->authorize('update', $issue);

        if (! $this->issues->attachTag($issue, $tag)) {
            return response()->json([
                'message' => __('This tag is already attached.'),
            ], 422);
        }

        return response()->json([
            'tag' => $this->issues->tagPayload($tag),
        ]);
    }

    public function detachTag(Issue $issue, Tag $tag): JsonResponse
    {
        $this->authorize('update', $issue);

        $this->issues->detachTag($issue, $tag);

        return response()->json([
            'tag' => $this->issues->tagPayload($tag),
        ]);
    }

    public function attachAssignee(Issue $issue, User $user): JsonResponse
    {
        $this->authorize('update', $issue);

        if (! $this->issues->attachAssignee($issue, $user)) {
            return response()->json([
                'message' => __('This member is already assigned.'),
            ], 422);
        }

        return response()->json([
            'user' => $this->issues->assigneePayload($user),
        ]);
    }

    public function detachAssignee(Issue $issue, User $user): JsonResponse
    {
        $this->authorize('update', $issue);

        $this->issues->detachAssignee($issue, $user);

        return response()->json([
            'user' => $this->issues->assigneePayload($user),
        ]);
    }
}
