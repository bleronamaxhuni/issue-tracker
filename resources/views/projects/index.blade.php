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
            <div class="mt-6 space-y-5">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="input mt-1">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="start_date" :value="__('Start date')" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1" :value="old('start_date')" />
                        <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                    </div>
                    <div>
                        <x-input-label for="deadline" :value="__('Deadline')" />
                        <x-text-input id="deadline" name="deadline" type="date" class="mt-1" :value="old('deadline')" />
                        <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                    </div>
                </div>
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
                                    @if ($project->isDeadlineOverdue())
                                        <span class="text-xs font-medium text-red-600">{{ __('Overdue') }}</span>
                                    @elseif ($project->isDeadlineSoon())
                                        <span class="text-xs font-medium text-amber-700">{{ __('Due soon') }}</span>
                                    @endif
                                </div>
                                @if ($project->description)
                                    <p class="meta mt-2">{{ Str::limit($project->description, 140) }}</p>
                                @endif
                                <dl class="mt-3 flex flex-wrap gap-x-5 gap-y-1 text-xs text-stone-500">
                                    <div>{{ trans_choice(':count issue|:count issues', $project->issues_count, ['count' => $project->issues_count]) }}</div>
                                    @if ($project->start_date)
                                        <div>{{ __('Start') }} {{ $project->start_date->format('M j, Y') }}</div>
                                    @endif
                                    @if ($project->deadline)
                                        <div class="{{ $project->isDeadlineOverdue() ? 'text-red-600' : '' }}">{{ __('Deadline') }} {{ $project->deadline->format('M j, Y') }}</div>
                                    @endif
                                </dl>
                            </div>
                            <div class="flex shrink-0 gap-3 text-sm">
                                <a href="{{ route('projects.show', $project) }}" class="font-medium text-stone-900 hover:underline">{{ __('Open') }}</a>
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->editModalName() }}')" class="text-stone-500 hover:text-stone-900">{{ __('Edit') }}</button>
                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->deleteModalName() }}')" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-page-container>

    @foreach ($projects as $project)
        <x-modal :name="$project->editModalName()" :show="session('open_modal') === $project->editModalName()" focusable>
            <form method="post" action="{{ route('projects.update', $project) }}" class="p-6">
                @csrf
                @method('patch')
                <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Edit project') }}</h2>
                <div class="mt-6 space-y-5">
                    <div>
                        <x-input-label for="name-{{ $project->id }}" :value="__('Name')" />
                        <x-text-input id="name-{{ $project->id }}" name="name" type="text" class="mt-1" :value="old('name', $project->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="description-{{ $project->id }}" :value="__('Description')" />
                        <textarea id="description-{{ $project->id }}" name="description" rows="4" class="input mt-1">{{ old('description', $project->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="start_date-{{ $project->id }}" :value="__('Start date')" />
                            <x-text-input id="start_date-{{ $project->id }}" name="start_date" type="date" class="mt-1" :value="old('start_date', $project->start_date?->format('Y-m-d'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="deadline-{{ $project->id }}" :value="__('Deadline')" />
                            <x-text-input id="deadline-{{ $project->id }}" name="deadline" type="date" class="mt-1" :value="old('deadline', $project->deadline?->format('Y-m-d'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal :name="$project->deleteModalName()" focusable>
            <form method="post" action="{{ route('projects.destroy', $project) }}" class="p-6">
                @csrf
                @method('delete')
                <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Delete project') }}</h2>
                <p class="mt-2 text-sm text-stone-600">
                    {{ __('Delete ":name" and all its issues?', ['name' => $project->name]) }}
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                    <x-danger-button>{{ __('Delete') }}</x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-app-layout>
