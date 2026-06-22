<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>

            <x-primary-button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'create-project')"
            >{{ __('New Project') }}</x-primary-button>
        </div>
    </x-slot>

    <x-modal name="create-project" :show="session('open_modal') === 'create-project'" focusable>
        <form method="post" action="{{ route('projects.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">{{ __('New Project') }}</h2>

            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Create Project') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'project-created')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Project created successfully.') }}</div>
            @elseif (session('status') === 'project-updated')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Project updated successfully.') }}</div>
            @elseif (session('status') === 'project-deleted')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Project deleted successfully.') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($projects->isEmpty())
                        <p class="text-gray-500">{{ __('No projects yet.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($projects as $project)
                                <li class="py-4 flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('projects.show', $project) }}" class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $project->name }}
                                        </a>
                                        @if ($project->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ $project->description }}</p>
                                        @endif
                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ trans_choice(':count issue|:count issues', $project->issues_count, ['count' => $project->issues_count]) }}
                                        </p>
                                    </div>
                                    <div class="flex shrink-0 gap-2">
                                        <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->editModalName() }}')">{{ __('Edit') }}</x-secondary-button>
                                        <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $project->deleteModalName() }}')">{{ __('Delete') }}</x-danger-button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($projects as $project)
        <x-modal :name="$project->editModalName()" :show="session('open_modal') === $project->editModalName()" focusable>
            <form method="post" action="{{ route('projects.update', $project) }}" class="p-6">
                @csrf
                @method('patch')
                <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Project') }}</h2>
                <div class="mt-6 space-y-6">
                    <div>
                        <x-input-label for="name-{{ $project->id }}" :value="__('Name')" />
                        <x-text-input id="name-{{ $project->id }}" name="name" type="text" class="mt-1 block w-full" :value="old('name', $project->name)" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="description-{{ $project->id }}" :value="__('Description')" />
                        <textarea id="description-{{ $project->id }}" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $project->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
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
    @endforeach
</x-app-layout>
