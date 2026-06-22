<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-gray-700">{{ __('Projects') }}</a>
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }}
                </h2>
            </div>

            @include('projects.partials.actions', ['project' => $project])
        </div>
    </x-slot>

    @include('issues.partials.create-modal', ['project' => $project])

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('projects.partials.flash-status')
            @include('issues.partials.flash-status')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-sm font-medium text-gray-500">{{ __('Description') }}</h3>
                    <p class="mt-2 text-gray-900">
                        {{ $project->description ?: __('No description provided.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Issues') }}</h3>

                        <x-primary-button
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'create-issue')"
                        >{{ __('New Issue') }}</x-primary-button>
                    </div>

                    @if ($project->issues->isEmpty())
                        <p class="mt-4 text-gray-500">{{ __('No issues yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($project->issues as $issue)
                                <li class="py-4 flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('issues.show', $issue) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $issue->title }}
                                        </a>
                                        @if ($issue->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ Str::limit($issue->description, 120) }}</p>
                                        @endif
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @include('issues.partials.badges', ['issue' => $issue])
                                        </div>
                                    </div>

                                    @include('issues.partials.actions', ['issue' => $issue])
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('projects.partials.edit-modal', ['project' => $project])
    @include('projects.partials.delete-modal', ['project' => $project])

    @foreach ($project->issues as $issue)
        @include('issues.partials.edit-modal', ['issue' => $issue])
        @include('issues.partials.delete-modal', ['issue' => $issue])
    @endforeach
</x-app-layout>
