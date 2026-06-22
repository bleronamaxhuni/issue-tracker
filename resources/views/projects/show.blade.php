<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-gray-700">{{ __('Projects') }}</a>
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $project->name }}</h2>
            </div>
            <div class="flex shrink-0 gap-2">
                <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->editModalName() }}')">{{ __('Edit') }}</x-secondary-button>
                <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->deleteModalName() }}')">{{ __('Delete') }}</x-danger-button>
            </div>
        </div>
    </x-slot>

    <x-modal name="create-issue" :show="session('open_modal') === 'create-issue'" focusable>
        <form method="post" action="{{ route('projects.issues.store', $project) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">{{ __('New Issue') }}</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @foreach (\App\Models\Issue::STATUSES as $status)
                            <option value="{{ $status }}" @selected(old('status', 'open') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>
                <div>
                    <x-input-label for="priority" :value="__('Priority')" />
                    <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @foreach (\App\Models\Issue::PRIORITIES as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', 'medium') === $priority)>{{ $priority }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                </div>
                <div>
                    <x-input-label for="due_date" :value="__('Due date')" />
                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date')" />
                    <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Create Issue') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'project-created')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Project created successfully.') }}</div>
            @elseif (session('status') === 'project-updated')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Project updated successfully.') }}</div>
            @elseif (session('status') === 'project-deleted')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Project deleted successfully.') }}</div>
            @elseif (session('status') === 'issue-created')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Issue created successfully.') }}</div>
            @elseif (session('status') === 'issue-updated')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Issue updated successfully.') }}</div>
            @elseif (session('status') === 'issue-deleted')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Issue deleted successfully.') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Description') }}</h3>
                    <p class="mt-2 text-gray-900">{{ $project->description ?: __('No description provided.') }}</p>
                    @if ($project->start_date || $project->deadline)
                        <dl class="mt-4 flex flex-wrap gap-x-8 gap-y-2 text-sm">
                            @if ($project->start_date)
                                <div>
                                    <dt class="font-medium text-gray-500">{{ __('Start date') }}</dt>
                                    <dd class="mt-1 text-gray-900">{{ $project->start_date->format('M j, Y') }}</dd>
                                </div>
                            @endif
                            @if ($project->deadline)
                                <div>
                                    <dt class="font-medium text-gray-500">{{ __('Deadline') }}</dt>
                                    <dd class="mt-1 text-gray-900">{{ $project->deadline->format('M j, Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Issues') }}</h3>
                        <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-issue')">{{ __('New Issue') }}</x-primary-button>
                    </div>

                    @if ($project->issues->isEmpty())
                        <p class="mt-4 text-gray-500">{{ __('No issues yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($project->issues as $issue)
                                <li class="py-4 flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('issues.show', $issue) }}" class="font-medium text-gray-900 hover:text-indigo-600">{{ $issue->title }}</a>
                                        @if ($issue->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ Str::limit($issue->description, 120) }}</p>
                                        @endif
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->statusLabel() }}</span>
                                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->priority }}</span>
                                            @if ($issue->due_date)
                                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->due_date->format('M j, Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex shrink-0 gap-2">
                                        <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->editModalName() }}')">{{ __('Edit') }}</x-secondary-button>
                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->deleteModalName() }}')">{{ __('Delete') }}</x-danger-button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-modal :name="$project->editModalName()" :show="session('open_modal') === $project->editModalName()" focusable>
        <form method="post" action="{{ route('projects.update', $project) }}" class="p-6">
            @csrf
            @method('patch')
            <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Project') }}</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="project-name" :value="__('Name')" />
                    <x-text-input id="project-name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $project->name)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="project-description" :value="__('Description')" />
                    <textarea id="project-description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div>
                    <x-input-label for="project-start_date" :value="__('Start date')" />
                    <x-text-input id="project-start_date" name="start_date" type="date" class="mt-1 block w-full" :value="old('start_date', $project->start_date?->format('Y-m-d'))" />
                    <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                </div>
                <div>
                    <x-input-label for="project-deadline" :value="__('Deadline')" />
                    <x-text-input id="project-deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $project->deadline?->format('Y-m-d'))" />
                    <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal :name="$project->deleteModalName()" focusable>
        <form method="post" action="{{ route('projects.destroy', $project) }}" class="p-6">
            @csrf
            @method('delete')
            <h2 class="text-lg font-medium text-gray-900">{{ __('Delete Project') }}</h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Are you sure you want to delete ":name"? All issues in this project will also be deleted.', ['name' => $project->name]) }}
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-danger-button>{{ __('Delete Project') }}</x-danger-button>
            </div>
        </form>
    </x-modal>

    @foreach ($project->issues as $issue)
        <x-modal :name="$issue->editModalName()" :show="session('open_modal') === $issue->editModalName()" focusable>
            <form method="post" action="{{ route('issues.update', $issue) }}" class="p-6">
                @csrf
                @method('patch')
                <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Issue') }}</h2>
                <div class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="title-{{ $issue->id }}" :value="__('Title')" />
                        <x-text-input id="title-{{ $issue->id }}" name="title" type="text" class="mt-1 block w-full" :value="old('title', $issue->title)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>
                    <div>
                        <x-input-label for="description-{{ $issue->id }}" :value="__('Description')" />
                        <textarea id="description-{{ $issue->id }}" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $issue->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>
                    <div>
                        <x-input-label for="status-{{ $issue->id }}" :value="__('Status')" />
                        <select id="status-{{ $issue->id }}" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach (\App\Models\Issue::STATUSES as $status)
                                <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ str_replace('_', ' ', $status) }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                    <div>
                        <x-input-label for="priority-{{ $issue->id }}" :value="__('Priority')" />
                        <select id="priority-{{ $issue->id }}" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach (\App\Models\Issue::PRIORITIES as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', $issue->priority) === $priority)>{{ $priority }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                    </div>
                    <div>
                        <x-input-label for="due_date-{{ $issue->id }}" :value="__('Due date')" />
                        <x-text-input id="due_date-{{ $issue->id }}" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', $issue->due_date?->format('Y-m-d'))" />
                        <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                    <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                </div>
            </form>
        </x-modal>

        <x-modal :name="$issue->deleteModalName()" focusable>
            <form method="post" action="{{ route('issues.destroy', $issue) }}" class="p-6">
                @csrf
                @method('delete')
                <h2 class="text-lg font-medium text-gray-900">{{ __('Delete Issue') }}</h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Are you sure you want to delete ":title"? All comments on this issue will also be deleted.', ['title' => $issue->title]) }}
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                    <x-danger-button>{{ __('Delete Issue') }}</x-danger-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-app-layout>
