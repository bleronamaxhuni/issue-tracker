<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-stone-900">{{ __('Projects') }}</a>
                </p>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h1 class="page-title">{{ $project->name }}</h1>
                    @if ($project->isDeadlineOverdue())
                        <span class="text-xs font-medium text-red-600">{{ __('Overdue') }}</span>
                    @elseif ($project->isDeadlineSoon())
                        <span class="text-xs font-medium text-amber-700">{{ __('Due soon') }}</span>
                    @endif
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-issue')">{{ __('New issue') }}</x-primary-button>
                <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->editModalName() }}')">{{ __('Edit') }}</x-secondary-button>
                <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->deleteModalName() }}')">{{ __('Delete') }}</x-danger-button>
            </div>
        </div>
    </x-slot>

    <x-modal name="create-issue" :show="session('open_modal') === 'create-issue'" focusable>
        <form method="post" action="{{ route('projects.issues.store', $project) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('New issue') }}</h2>
            <div class="mt-6 space-y-5">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1" :value="old('title')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="input mt-1">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="input mt-1" required>
                            @foreach (\App\Models\Issue::STATUSES as $status)
                                <option value="{{ $status }}" @selected(old('status', 'open') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                    <div>
                        <x-input-label for="priority" :value="__('Priority')" />
                        <select id="priority" name="priority" class="input mt-1" required>
                            @foreach (\App\Models\Issue::PRIORITIES as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ $priority }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                    </div>
                </div>
                <div>
                    <x-input-label for="due_date" :value="__('Due date')" />
                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1" :value="old('due_date')" />
                    <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
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

        <section class="panel p-5">
            <h2 class="section-title">{{ __('About') }}</h2>
            <p class="mt-3 text-stone-800 leading-relaxed">{{ $project->description ?: __('No description.') }}</p>
            @if ($project->start_date || $project->deadline)
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
                            <dd class="mt-1 text-sm font-medium {{ $project->isDeadlineOverdue() ? 'text-red-600' : 'text-stone-900' }}">{{ $project->deadline->format('M j, Y') }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-xs text-stone-500">{{ __('Issues') }}</dt>
                        <dd class="mt-1 text-sm font-medium text-stone-900">{{ $project->issues->count() }}</dd>
                    </div>
                </dl>
            @endif
        </section>

        <section>
            <div class="mb-4 flex items-baseline justify-between gap-4">
                <h2 class="section-title">{{ __('Issues') }}</h2>
                <span class="text-xs text-stone-500">{{ $project->issues->count() }}</span>
            </div>
            @if ($project->issues->isEmpty())
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
                                    @if ($issue->description)
                                        <p class="meta mt-2">{{ Str::limit($issue->description, 120) }}</p>
                                    @endif
                                    <div class="mt-3 flex flex-wrap items-center gap-3">
                                        <x-issue-status-badge :status="$issue->status" />
                                        <x-issue-priority-badge :priority="$issue->priority" />
                                        @if ($issue->due_date)
                                            <span class="text-xs {{ $issue->isOverdue() ? 'font-medium text-red-600' : 'text-stone-500' }}">{{ $issue->due_date->format('M j, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex shrink-0 gap-3 text-sm">
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->editModalName() }}')" class="text-stone-500 hover:text-stone-900">{{ __('Edit') }}</button>
                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->deleteModalName() }}')" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </x-page-container>

    <x-modal :name="$project->editModalName()" :show="session('open_modal') === $project->editModalName()" focusable>
        <form method="post" action="{{ route('projects.update', $project) }}" class="p-6">
            @csrf
            @method('patch')
            <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Edit project') }}</h2>
            <div class="mt-6 space-y-5">
                <div>
                    <x-input-label for="project-name" :value="__('Name')" />
                    <x-text-input id="project-name" name="name" type="text" class="mt-1" :value="old('name', $project->name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="project-description" :value="__('Description')" />
                    <textarea id="project-description" name="description" rows="4" class="input mt-1">{{ old('description', $project->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="project-start_date" :value="__('Start date')" />
                        <x-text-input id="project-start_date" name="start_date" type="date" class="mt-1" :value="old('start_date', $project->start_date?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                    </div>
                    <div>
                        <x-input-label for="project-deadline" :value="__('Deadline')" />
                        <x-text-input id="project-deadline" name="deadline" type="date" class="mt-1" :value="old('deadline', $project->deadline?->format('Y-m-d'))" />
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
            <p class="mt-2 text-sm text-stone-600">{{ __('Delete ":name" and all its issues?', ['name' => $project->name]) }}</p>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-danger-button>{{ __('Delete') }}</x-danger-button>
            </div>
        </form>
    </x-modal>

    @foreach ($project->issues as $issue)
        <x-modal :name="$issue->editModalName()" :show="session('open_modal') === $issue->editModalName()" focusable>
            <form method="post" action="{{ route('issues.update', $issue) }}" class="p-6">
                @csrf
                @method('patch')
                <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Edit issue') }}</h2>
                <div class="mt-6 space-y-5">
                    <div>
                        <x-input-label for="title-{{ $issue->id }}" :value="__('Title')" />
                        <x-text-input id="title-{{ $issue->id }}" name="title" type="text" class="mt-1" :value="old('title', $issue->title)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>
                    <div>
                        <x-input-label for="description-{{ $issue->id }}" :value="__('Description')" />
                        <textarea id="description-{{ $issue->id }}" name="description" rows="4" class="input mt-1">{{ old('description', $issue->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <x-input-label for="status-{{ $issue->id }}" :value="__('Status')" />
                            <select id="status-{{ $issue->id }}" name="status" class="input mt-1" required>
                                @foreach (\App\Models\Issue::STATUSES as $status)
                                    <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ str_replace('_', ' ', $status) }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                        <div>
                            <x-input-label for="priority-{{ $issue->id }}" :value="__('Priority')" />
                            <select id="priority-{{ $issue->id }}" name="priority" class="input mt-1" required>
                                @foreach (\App\Models\Issue::PRIORITIES as $priority)
                                    <option value="{{ $priority }}" @selected(old('priority', $issue->priority) === $priority)>{{ $priority }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="due_date-{{ $issue->id }}" :value="__('Due date')" />
                        <x-text-input id="due_date-{{ $issue->id }}" name="due_date" type="date" class="mt-1" :value="old('due_date', $issue->due_date?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal :name="$issue->deleteModalName()" focusable>
            <form method="post" action="{{ route('issues.destroy', $issue) }}" class="p-6">
                @csrf
                @method('delete')
                <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Delete issue') }}</h2>
                <p class="mt-2 text-sm text-stone-600">{{ __('Delete ":title" and all its comments?', ['title' => $issue->title]) }}</p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                    <x-danger-button>{{ __('Delete') }}</x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-app-layout>
