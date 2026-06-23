<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="page-title">{{ __('Projects') }}</h1>
                <p class="page-subtitle">{{ __('Manage projects and their issues') }}</p>
            </div>
            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-project')">{{ __('New project') }}</x-primary-button>
        </div>
    </x-slot>

    <x-modal name="create-project" :show="session('open_modal') === 'create-project'" focusable>
        <form method="post" action="{{ route('projects.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('New project') }}</h2>
            <div class="mt-6">
                @include('projects.partials.form-fields', ['project' => $blankProject, 'fieldPrefix' => ''])
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Create') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-page-container>
        <x-flash-message />

        @if ($projects->isEmpty())
            <x-empty-state :title="__('No projects yet')" :description="__('Create your first project to start tracking issues.')">
                <x-slot name="action">
                    <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-project')">{{ __('Create project') }}</x-primary-button>
                </x-slot>
            </x-empty-state>
        @else
            <ul class="panel divide-y divide-stone-200 px-5">
                @foreach ($projects as $project)
                    <li class="list-row">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <a href="{{ route('projects.show', $project) }}" class="link !no-underline hover:underline text-base">{{ $project->name }}</a>
                                    <x-project-deadline-badge :project="$project" />
                                </div>
                                @if ($excerpt = $projectPresenter->excerpt($project))
                                    <p class="meta mt-2">{{ $excerpt }}</p>
                                @endif
                                <dl class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-stone-500">
                                    <div>{{ trans_choice(':count issue|:count issues', $project->issues_count, ['count' => $project->issues_count]) }}</div>
                                    @if ($project->start_date)
                                        <div>{{ __('Start') }} {{ $project->start_date->format('M j, Y') }}</div>
                                    @endif
                                    @if ($project->deadline)
                                        <div class="{{ $projectPresenter->deadlineTextClass($project) }}">{{ __('Deadline') }} {{ $project->deadline->format('M j, Y') }}</div>
                                    @endif
                                </dl>
                            </div>
                            <div class="flex shrink-0 gap-3 text-sm">
                                <a href="{{ route('projects.show', $project) }}" class="font-medium text-stone-900 hover:underline">{{ __('Open') }}</a>
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-project-{{ $project->id }}')" class="text-stone-500 hover:text-stone-900">{{ __('Edit') }}</button>
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-project-{{ $project->id }}')" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-4">
                {{ $projects->links() }}
            </div>
        @endif
    </x-page-container>

    @foreach ($projects as $project)
        @include('projects.partials.edit-modal', ['project' => $project])
        @include('projects.partials.delete-modal', ['project' => $project])
    @endforeach
</x-app-layout>
