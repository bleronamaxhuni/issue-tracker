<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-gray-700">{{ __('Projects') }}</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-gray-700">{{ $issue->project->name }}</a>
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $issue->title }}
                </h2>
            </div>

            @include('issues.partials.actions', ['issue' => $issue])
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('issues.partials.flash-status')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap gap-2">
                        @include('issues.partials.badges', ['issue' => $issue])
                    </div>

                    <h3 class="mt-6 text-sm font-medium text-gray-500">{{ __('Description') }}</h3>
                    <p class="mt-2 text-gray-900">
                        {{ $issue->description ?: __('No description provided.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Tags') }}</h3>

                    @if ($issue->tags->isEmpty())
                        <p class="mt-4 text-gray-500">{{ __('No tags attached.') }}</p>
                    @else
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach ($issue->tags as $tag)
                                <span
                                    class="rounded-full px-2 py-1 text-xs text-white"
                                    style="background-color: {{ $tag->color ?? '#6b7280' }}"
                                >{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Comments') }}</h3>

                    @if ($issue->comments->isEmpty())
                        <p class="mt-4 text-gray-500">{{ __('No comments yet.') }}</p>
                    @else
                        <ul class="mt-4 divide-y divide-gray-200">
                            @foreach ($issue->comments as $comment)
                                <li class="py-4">
                                    <p class="text-sm font-medium text-gray-900">{{ $comment->author_name }}</p>
                                    <p class="mt-1 text-sm text-gray-600">{{ $comment->body }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('issues.partials.edit-modal', ['issue' => $issue])
    @include('issues.partials.delete-modal', ['issue' => $issue])
</x-app-layout>
