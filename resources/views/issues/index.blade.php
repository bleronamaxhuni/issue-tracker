<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Issues') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('issues.partials.flash-status')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @include('issues.partials.filters')
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($issues->isEmpty())
                        <p class="text-gray-500">{{ __('No issues found.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($issues as $issue)
                                <li class="py-4 flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('issues.show', $issue) }}" class="text-lg font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $issue->title }}
                                        </a>
                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $issue->project->name }}
                                        </p>
                                        @if ($issue->description)
                                            <p class="mt-1 text-sm text-gray-600">{{ $issue->description }}</p>
                                        @endif
                                        <div class="mt-2 flex flex-wrap gap-2">
                                            @include('issues.partials.badges', ['issue' => $issue])
                                            @foreach ($issue->tags as $tag)
                                                <span class="rounded-full bg-indigo-100 px-2 py-1 text-xs text-indigo-700">{{ $tag->name }}</span>
                                            @endforeach
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

    @foreach ($issues as $issue)
        @include('issues.partials.edit-modal', ['issue' => $issue])
        @include('issues.partials.delete-modal', ['issue' => $issue])
    @endforeach
</x-app-layout>
