<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="page-title">{{ __('Dashboard') }}</h1>
                <p class="page-subtitle">{{ __('Overview of your projects and issues') }}</p>
            </div>
            <x-primary-button onclick="window.location='{{ route('projects.index') }}'">{{ __('Projects') }}</x-primary-button>
        </div>
    </x-slot>

    <x-page-container>
        <x-flash-message />

        <dl class="grid grid-cols-3 border border-stone-200 bg-white divide-x divide-stone-200">
            <div class="px-5 py-6">
                <dt class="section-title">{{ __('Projects') }}</dt>
                <dd class="stat-value mt-2">{{ $projectsCount }}</dd>
            </div>
            <div class="px-5 py-6">
                <dt class="section-title">{{ __('Open') }}</dt>
                <dd class="stat-value mt-2">{{ $openIssuesCount }}</dd>
            </div>
            <div class="px-5 py-6">
                <dt class="section-title">{{ __('Overdue') }}</dt>
                <dd class="stat-value mt-2 {{ $overdueIssuesCount > 0 ? 'text-red-600' : '' }}">{{ $overdueIssuesCount }}</dd>
            </div>
        </dl>

        <section>
            <div class="mb-4 flex items-baseline justify-between gap-4">
                <h2 class="section-title">{{ __('Recent issues') }}</h2>
                <a href="{{ route('issues.index') }}" class="text-sm text-stone-500 hover:text-stone-900">{{ __('View all') }}</a>
            </div>
            @if ($recentIssues->isEmpty())
                <x-empty-state
                    :title="__('No issues yet')"
                    :description="__('Create a project and add your first issue.')"
                />
            @else
                <ul class="panel divide-y divide-stone-200 px-5">
                    @foreach ($recentIssues as $issue)
                        <li class="list-row">
                            <a href="{{ route('issues.show', $issue) }}" class="link !no-underline hover:underline">{{ $issue->title }}</a>
                            <p class="meta mt-1">
                                {{ $issue->project->name }}
                                @if ($issue->is_assigned_only)
                                    <span class="text-xs text-stone-400">({{ __('assigned to you') }})</span>
                                @endif
                            </p>
                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                <x-issue-status-badge :status="$issue->status" />
                                <x-issue-priority-badge :priority="$issue->priority" />
                                @if ($issue->isOverdue())
                                    <span class="text-xs font-medium text-red-600">{{ __('Overdue') }}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section>
            <div class="mb-4 flex items-baseline justify-between gap-4">
                <h2 class="section-title">{{ __('Your projects') }}</h2>
                <a href="{{ route('projects.index') }}" class="text-sm text-stone-500 hover:text-stone-900">{{ __('View all') }}</a>
            </div>
            @if ($projects->isEmpty())
                <x-empty-state :title="__('No projects yet')" :description="__('Create a project to start tracking work.')">
                    <x-slot name="action">
                        <x-primary-button onclick="window.location='{{ route('projects.index') }}'">{{ __('Go to projects') }}</x-primary-button>
                    </x-slot>
                </x-empty-state>
            @else
                <ul class="panel divide-y divide-stone-200 px-5">
                    @foreach ($projects as $project)
                        <li class="list-row">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <a href="{{ route('projects.show', $project) }}" class="link !no-underline hover:underline">{{ $project->name }}</a>
                                    <p class="meta mt-1">{{ trans_choice(':count issue|:count issues', $project->issues_count, ['count' => $project->issues_count]) }}</p>
                                </div>
                                <x-project-deadline-badge :project="$project" />
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </x-page-container>
</x-app-layout>
