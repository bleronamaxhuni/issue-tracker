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

    @include('projects.partials.create-modal')

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('projects.partials.flash-status')

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

                                    @include('projects.partials.actions', ['project' => $project])
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($projects as $project)
        @include('projects.partials.edit-modal', ['project' => $project])
        @include('projects.partials.delete-modal', ['project' => $project])
    @endforeach
</x-app-layout>
