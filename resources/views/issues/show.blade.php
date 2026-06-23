<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-stone-900">{{ __('Projects') }}</a>
                    <span class="mx-1">/</span>
                    <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-stone-900">{{ $issue->project->name }}</a>
                </p>
                <h1 class="page-title mt-1">{{ $issue->title }}</h1>
            </div>
            <div class="flex gap-2">
                <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->editModalName() }}')">{{ __('Edit') }}</x-secondary-button>
                <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', '{{ $issue->deleteModalName() }}')">{{ __('Delete') }}</x-danger-button>
            </div>
        </div>
    </x-slot>

    <x-page-container>
        <x-flash-message />

        <section class="panel p-5">
            <dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="section-title">{{ __('Status') }}</dt>
                    <dd class="mt-2"><x-issue-status-badge :status="$issue->status" /></dd>
                </div>
                <div>
                    <dt class="section-title">{{ __('Priority') }}</dt>
                    <dd class="mt-2"><x-issue-priority-badge :priority="$issue->priority" /></dd>
                </div>
                <div>
                    <dt class="section-title">{{ __('Due') }}</dt>
                    <dd class="mt-2 text-sm {{ $issue->isOverdue() ? 'font-medium text-red-600' : 'text-stone-900' }}">
                        @if ($issue->due_date)
                            {{ $issue->due_date->format('M j, Y') }}
                            @if ($issue->isOverdue())
                                <span class="ml-2 text-xs">({{ __('overdue') }})</span>
                            @endif
                        @else
                            <span class="text-stone-400">{{ __('Not set') }}</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="section-title">{{ __('Project') }}</dt>
                    <dd class="mt-2 text-sm">
                        <a href="{{ route('projects.show', $issue->project) }}" class="link">{{ $issue->project->name }}</a>
                    </dd>
                </div>
            </dl>
        </section>

        <section class="panel p-5">
            <h2 class="section-title">{{ __('Description') }}</h2>
            <p class="mt-3 whitespace-pre-wrap leading-relaxed text-stone-800">{{ $issue->description ?: __('No description.') }}</p>
        </section>

        <section class="panel p-5">
            <div
                id="issue-tags"
                data-attach-url="{{ route('issues.tags.attach', ['issue' => $issue, 'tag' => '__TAG__']) }}"
                data-detach-url="{{ route('issues.tags.detach', ['issue' => $issue, 'tag' => '__TAG__']) }}"
            >
                <h2 class="section-title">{{ __('Tags') }}</h2>
                <p class="meta mt-2">{{ __('Click to attach or remove.') }}</p>
                <p data-tag-error class="mt-2 text-sm text-red-600 hidden"></p>

                <div class="mt-5">
                    <h3 class="text-xs font-medium text-stone-500">{{ __('Attached') }}</h3>
                    <p data-empty-attached class="meta mt-2 @if ($issue->tags->isNotEmpty()) hidden @endif">{{ __('None') }}</p>
                    <div data-attached-tags class="mt-2 flex flex-wrap gap-2">
                        @foreach ($issue->tags as $tag)
                            <button
                                type="button"
                                data-action="detach"
                                data-tag-id="{{ $tag->id }}"
                                class="inline-flex items-center gap-1.5 border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-700 hover:border-stone-900"
                                title="{{ __('Remove') }}"
                            >
                                <span class="h-2 w-2 rounded-sm" style="background-color: {{ $tag->color ?? '#78716c' }}"></span>
                                {{ $tag->name }} ×
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-xs font-medium text-stone-500">{{ __('Available') }}</h3>
                    @if ($allTags->isEmpty())
                        <p class="meta mt-2">
                            {{ __('No tags yet.') }}
                            <a href="{{ route('tags.index') }}" class="link">{{ __('Create one') }}</a>
                        </p>
                    @else
                        @php $attachedIds = $issue->tags->pluck('id'); @endphp
                        <p data-empty-available class="meta mt-2 @if ($allTags->whereNotIn('id', $attachedIds)->isNotEmpty()) hidden @endif">{{ __('All attached') }}</p>
                        <div data-available-tags class="mt-2 flex flex-wrap gap-2">
                            @foreach ($allTags->whereNotIn('id', $attachedIds) as $tag)
                                <button
                                    type="button"
                                    data-action="attach"
                                    data-tag-id="{{ $tag->id }}"
                                    class="inline-flex items-center gap-1.5 border border-dashed border-stone-300 px-2.5 py-1 text-xs text-stone-600 hover:border-stone-500 hover:text-stone-900"
                                >+ {{ $tag->name }}</button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <section class="panel p-5">
            <div
                id="issue-assignees"
                data-attach-url="{{ route('issues.assignees.attach', ['issue' => $issue, 'user' => '__USER__']) }}"
                data-detach-url="{{ route('issues.assignees.detach', ['issue' => $issue, 'user' => '__USER__']) }}"
            >
                <h2 class="section-title">{{ __('Assignees') }}</h2>
                <p class="meta mt-2">{{ __('Click to assign or remove team members.') }}</p>
                <p data-assignee-error class="mt-2 text-sm text-red-600 hidden"></p>

                <div class="mt-5">
                    <h3 class="text-xs font-medium text-stone-500">{{ __('Assigned') }}</h3>
                    <p data-empty-attached class="meta mt-2 @if ($issue->assignees->isNotEmpty()) hidden @endif">{{ __('None') }}</p>
                    <div data-attached-assignees class="mt-2 flex flex-wrap gap-2">
                        @foreach ($issue->assignees as $assignee)
                            <button
                                type="button"
                                data-action="detach"
                                data-user-id="{{ $assignee->id }}"
                                class="inline-flex items-center gap-2 border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-700 hover:border-stone-900"
                                title="{{ $assignee->email }}"
                            >{{ $assignee->name }} ×</button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-xs font-medium text-stone-500">{{ __('Available') }}</h3>
                    @php $assignedIds = $issue->assignees->pluck('id'); @endphp
                    <p data-empty-available class="meta mt-2 @if ($allUsers->whereNotIn('id', $assignedIds)->isNotEmpty()) hidden @endif">{{ __('Everyone is assigned') }}</p>
                    <div data-available-assignees class="mt-2 flex flex-wrap gap-2">
                        @foreach ($allUsers->whereNotIn('id', $assignedIds) as $user)
                            <button
                                type="button"
                                data-action="attach"
                                data-user-id="{{ $user->id }}"
                                class="inline-flex items-center gap-2 border border-dashed border-stone-300 px-2.5 py-1 text-xs text-stone-600 hover:border-stone-500 hover:text-stone-900"
                                title="{{ $user->email }}"
                            >+ {{ $user->name }}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="panel p-5">
            <div
                id="issue-comments"
                data-list-url="{{ route('issues.comments.index', $issue) }}"
                data-store-url="{{ route('issues.comments.store', $issue) }}"
            >
                <h2 class="section-title">{{ __('Comments') }}</h2>
                <p class="meta mt-2">{{ __('Newest first.') }}</p>

                <form data-comment-form class="mt-6 space-y-4 border border-stone-200 p-4">
                    <div>
                        <x-input-label for="author_name" :value="__('Name')" />
                        <x-text-input id="author_name" name="author_name" type="text" class="mt-1" :value="auth()->user()->name" required />
                        <p data-error="author_name" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>
                    <div>
                        <x-input-label for="body" :value="__('Comment')" />
                        <textarea id="body" name="body" rows="3" placeholder="{{ __('Write a comment...') }}" class="input mt-1" required></textarea>
                        <p data-error="body" class="mt-2 text-sm text-red-600 hidden"></p>
                    </div>
                    <p data-error="general" class="text-sm text-red-600 hidden"></p>
                    <x-primary-button type="submit" data-submit>{{ __('Post comment') }}</x-primary-button>
                </form>

                <p data-empty-comments class="meta mt-6 hidden">{{ __('No comments yet.') }}</p>
                <ul data-comment-list class="mt-6 space-y-3"></ul>
                <div class="mt-4">
                    <x-secondary-button type="button" data-load-more class="hidden">{{ __('Load more') }}</x-secondary-button>
                </div>
            </div>
        </section>
    </x-page-container>

    <x-modal :name="$issue->editModalName()" :show="session('open_modal') === $issue->editModalName()" focusable>
        <form method="post" action="{{ route('issues.update', $issue) }}" class="p-6">
            @csrf
            @method('patch')
            <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('Edit issue') }}</h2>
            <div class="mt-6 space-y-5">
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1" :value="old('title', $issue->title)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="input mt-1">{{ old('description', $issue->description) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="input mt-1" required>
                            @foreach (\App\Models\Issue::STATUSES as $status)
                                <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ str_replace('_', ' ', $status) }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                    <div>
                        <x-input-label for="priority" :value="__('Priority')" />
                        <select id="priority" name="priority" class="input mt-1" required>
                            @foreach (\App\Models\Issue::PRIORITIES as $priority)
                                <option value="{{ $priority }}" @selected(old('priority', $issue->priority) === $priority)>{{ $priority }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('priority')" />
                    </div>
                </div>
                <div>
                    <x-input-label for="due_date" :value="__('Due date')" />
                    <x-text-input id="due_date" name="due_date" type="date" class="mt-1" :value="old('due_date', $issue->due_date?->format('Y-m-d'))" />
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
</x-app-layout>
