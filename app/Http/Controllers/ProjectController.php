<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = auth()->user()
            ->projects()
            ->withCount('issues')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load(['issues' => fn ($query) => $query->latest()]);

        return view('projects.show', compact('project'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $request->user()->projects()->create($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'project-created');
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.show', $project)
            ->with('status', 'project-updated');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('status', 'project-deleted');
    }
}
