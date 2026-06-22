<?php

namespace App\Http\Controllers;

use App\Http\Requests\Issue\StoreIssueRequest;
use App\Http\Requests\Issue\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    public function index(Request $request): View
    {
        $issues = Issue::query()
            ->whereHas('project', fn ($query) => $query->where('user_id', $request->user()->id))
            ->with(['project', 'tags'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('priority'), fn ($query) => $query->where('priority', $request->priority))
            ->when($request->filled('tag'), fn ($query) => $query->whereHas(
                'tags',
                fn ($tagQuery) => $tagQuery->where('tags.id', $request->tag)
            ))
            ->latest()
            ->get();

        $tags = Tag::query()
            ->orderBy('name')
            ->get();

        return view('issues.index', [
            'issues' => $issues,
            'tags' => $tags,
            'filters' => $request->only(['status', 'priority', 'tag']),
        ]);
    }

    public function show(Issue $issue): View
    {
        $this->authorize('view', $issue);

        $issue->load(['project', 'tags']);

        $allTags = Tag::query()->orderBy('name')->get();

        return view('issues.show', compact('issue', 'allTags'));
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

        if ($issue->tags()->where('tags.id', $tag->id)->exists()) {
            return response()->json([
                'message' => __('This tag is already attached.'),
            ], 422);
        }

        $issue->tags()->attach($tag);

        return response()->json([
            'tag' => $this->tagPayload($tag),
        ]);
    }

    public function detachTag(Issue $issue, Tag $tag): JsonResponse
    {
        $this->authorize('update', $issue);

        $issue->tags()->detach($tag);

        return response()->json([
            'tag' => $this->tagPayload($tag),
        ]);
    }

    /**
     * @return array<string, int|string|null>
     */
    private function tagPayload(Tag $tag): array
    {
        return [
            'id' => $tag->id,
            'name' => $tag->name,
            'color' => $tag->color ?? '#6b7280',
        ];
    }
}
