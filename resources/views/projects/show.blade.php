<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-stone-900">{{ __('Projects') }}</a>
                </p>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h1 class="page-title">{{ $project->name }}</h1>
                    <x-project-deadline-badge :project="$project" />
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-issue')">{{ __('New issue') }}</x-primary-button>
                <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-project-{{ $project->id }}')">{{ __('Edit') }}</x-secondary-button>
                <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-project-{{ $project->id }}')">{{ __('Delete') }}</x-danger-button>
            </div>
        </div>
    </x-slot>

    <x-modal name="create-issue" :show="session('open_modal') === 'create-issue'" focusable>
        <form method="post" action="{{ route('projects.issues.store', $project) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('New issue') }}</h2>
            <div class="mt-6">
                @include('issues.partials.form-fields', ['issue' => $blankIssue, 'fieldPrefix' => 'create'])
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Create') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-page-container>
        <x-flash-message />

        <section class="panel p-5">
            <h2 class="section-title">{{ __('About') }}</h2>
            <p class="mt-3 text-stone-800 leading-relaxed">{{ $project->description ?: __('No description.') }}</p>
            @if ($hasSchedule)
                <dl class="mt-5 grid gap-4 border-t border-stone-200 pt-5 sm:grid-cols-3">
                    @if ($project->start_date)
                        <div>
                            <dt class="text-xs text-stone-500">{{ __('Start') }}</dt>
                            <dd class="mt-1 text-sm font-medium text-stone-900">{{ $project->start_date->format('M j, Y') }}</dd>
                        </div>
                    @endif
                    @if ($project->deadline)
                        <div>
                            <dt class="text-xs text-stone-500">{{ __('Deadline') }}</dt>
                            <dd class="mt-1 text-sm font-medium {{ $projectPresenter->deadlineTextClass($project) ?: 'text-stone-900' }}">{{ $project->deadline->format('M j, Y') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-stone-500">{{ __('Issues') }}</dt>
                        <dd class="mt-1 text-sm font-medium text-stone-900">{{ $issuesCount }}</dd>
                    </div>
                </dl>
            @endif
        </section>

        <section>
            <div class="mb-4 flex items-baseline justify-between gap-4">
                <h2 class="section-title">{{ __('Issues') }}</h2>
                <span class="text-xs text-stone-500">{{ $issuesCount }}</span>
            </div>
            @if ($issuesCount === 0)
                <x-empty-state :title="__('No issues yet')" :description="__('Add the first issue for this project.')">
                    <x-slot name="action">
                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-issue')">{{ __('Create issue') }}</x-primary-button>
                    </x-slot>
                </x-empty-state>
            @else
                <ul class="panel divide-y divide-stone-200 px-5">
                    @foreach ($project->issues as $issue)
                        <li class="list-row">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('issues.show', $issue) }}" class="link !no-underline hover:underline">{{ $issue->title }}</a>
                                    @if ($excerpt = $issuePresenter->excerpt($issue, 120))
                                        <p class="meta mt-2">{{ $excerpt }}</p>
                                    @endif
                                    <div class="mt-3 flex flex-wrap items-center gap-3">
                                        <x-issue-status-badge :status="$issue->status" />
                                        <x-issue-priority-badge :priority="$issue->priority" />
                                        <x-issue-due-date :issue="$issue" />
                                    </div>
                                </div>
                                <div class="flex shrink-0 gap-3 text-sm">
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-issue-{{ $issue->id }}')" class="text-stone-500 hover:text-stone-900">{{ __('Edit') }}</button>
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-issue-{{ $issue->id }}')" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </x-page-container>

    @include('projects.partials.edit-modal', ['project' => $project])
    @include('projects.partials.delete-modal', ['project' => $project])

    @foreach ($project->issues as $issue)
        @include('issues.partials.edit-modal', ['issue' => $issue])
        @include('issues.partials.delete-modal', ['issue' => $issue])
    @endforeach
</x-app-layout>
