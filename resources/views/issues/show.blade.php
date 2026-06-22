<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-gray-700">{{ __('Projects') }}</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-gray-700">{{ $issue->project->name }}</a>
                </p>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $issue->title }}</h2>
            </div>
            <div class="flex shrink-0 gap-2">
                <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->editModalName() }}')">{{ __('Edit') }}</x-secondary-button>
                <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->deleteModalName() }}')">{{ __('Delete') }}</x-danger-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'issue-created')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Issue created successfully.') }}</div>
            @elseif (session('status') === 'issue-updated')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Issue updated successfully.') }}</div>
            @elseif (session('status') === 'issue-deleted')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Issue deleted successfully.') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->statusLabel() }}</span>
                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->priority }}</span>
                        @if ($issue->due_date)
                            <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->due_date->format('M j, Y') }}</span>
                        @endif
                    </div>

                    <h3 class="mt-6 text-sm font-medium text-gray-500">{{ __('Description') }}</h3>
                    <p class="mt-2 text-gray-900">{{ $issue->description ?: __('No description provided.') }}</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    id="issue-tags"
                    class="p-6 text-gray-900"
                    data-attach-url="{{ route('issues.tags.attach', ['issue' => $issue, 'tag' => '__TAG__']) }}"
                    data-detach-url="{{ route('issues.tags.detach', ['issue' => $issue, 'tag' => '__TAG__']) }}"
                >
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Tags') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('Click a tag to attach or detach without reloading the page.') }}</p>

                    <p data-tag-error class="mt-2 text-sm text-red-600 hidden"></p>

                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700">{{ __('Attached') }}</h4>
                        <p data-empty-attached class="mt-2 text-sm text-gray-500 @if ($issue->tags->isNotEmpty()) hidden @endif">{{ __('No tags attached.') }}</p>
                        <div data-attached-tags class="mt-2 flex flex-wrap gap-2">
                            @foreach ($issue->tags as $tag)
                                <button
                                    type="button"
                                    data-action="detach"
                                    data-tag-id="{{ $tag->id }}"
                                    class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs text-white hover:opacity-80"
                                    style="background-color: {{ $tag->color ?? '#6b7280' }}"
                                >{{ $tag->name }} ×</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700">{{ __('Available') }}</h4>
                        @if ($allTags->isEmpty())
                            <p class="mt-2 text-sm text-gray-500">
                                {{ __('No tags exist yet.') }}
                                <a href="{{ route('tags.index') }}" class="text-indigo-600 hover:text-indigo-800">{{ __('Create a tag') }}</a>
                            </p>
                        @else
                            @php $attachedIds = $issue->tags->pluck('id'); @endphp
                            <p data-empty-available class="mt-2 text-sm text-gray-500 @if ($allTags->whereNotIn('id', $attachedIds)->isNotEmpty()) hidden @endif">{{ __('All tags are attached.') }}</p>
                            <div data-available-tags class="mt-2 flex flex-wrap gap-2">
                                @foreach ($allTags->whereNotIn('id', $attachedIds) as $tag)
                                    <button
                                        type="button"
                                        data-action="attach"
                                        data-tag-id="{{ $tag->id }}"
                                        class="inline-flex items-center rounded-full border border-gray-300 px-2 py-1 text-xs text-gray-700 hover:bg-gray-50"
                                    >+ {{ $tag->name }}</button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div
                    id="issue-comments"
                    class="p-6 text-gray-900"
                    data-list-url="{{ route('issues.comments.index', $issue) }}"
                    data-store-url="{{ route('issues.comments.store', $issue) }}"
                >
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Comments') }}</h3>

                    <form data-comment-form class="mt-4 space-y-4 border-b border-gray-200 pb-6">
                        <div>
                            <x-input-label for="author_name" :value="__('Your name')" />
                            <x-text-input
                                id="author_name"
                                name="author_name"
                                type="text"
                                class="mt-1 block w-full"
                                :value="auth()->user()->name"
                                required
                            />
                            <p data-error="author_name" class="mt-2 text-sm text-red-600 hidden"></p>
                        </div>
                        <div>
                            <x-input-label for="body" :value="__('Comment')" />
                            <textarea
                                id="body"
                                name="body"
                                rows="3"
                                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required
                            ></textarea>
                            <p data-error="body" class="mt-2 text-sm text-red-600 hidden"></p>
                        </div>
                        <p data-error="general" class="text-sm text-red-600 hidden"></p>
                        <x-primary-button type="submit" data-submit>{{ __('Add Comment') }}</x-primary-button>
                    </form>

                    <p data-empty-comments class="mt-4 text-sm text-gray-500 hidden">{{ __('No comments yet.') }}</p>

                    <ul data-comment-list class="mt-4 divide-y divide-gray-200"></ul>

                    <div class="mt-4">
                        <x-secondary-button type="button" data-load-more class="hidden">{{ __('Load more comments') }}</x-secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal :name="$issue->editModalName()" :show="session('open_modal') === $issue->editModalName()" focusable>
        <form method="post" action="{{ route('issues.update', $issue) }}" class="p-6">
            @csrf
            @method('patch')
            <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Issue') }}</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $issue->title)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $issue->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @foreach (\App\Models\Issue::STATUSES as $status)
                            <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ str_replace('_', ' ', $status) }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status')" />
                </div>
                <div>
                    <x-input-label for="priority" :value="__('Priority')" />
                    <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        @foreach (\App\Models\Issue::PRIORITIES as $priority)
                            <option value="{{ $priority }}" @selected(old('priority', $issue->priority) === $priority)>{{ $priority }}</option>
                        @endforeach
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                </div>
                <div>
                    <x-input-label for="due_date" :value="__('Due date')" />
                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" :value="old('due_date', $issue->due_date?->format('Y-m-d'))" />
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
</x-app-layout>
