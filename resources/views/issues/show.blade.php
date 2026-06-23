<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-sm text-stone-500">
                    <a href="{{ route('projects.index') }}" class="hover:text-stone-900">{{ __('Projects') }}</a>
                    <span class="mx-1">/</span>
                    @if ($isOwner)
                        <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-stone-900">{{ $issue->project->name }}</a>
                    @else
                        <span>{{ $issue->project->name }}</span>
                    @endif
                </p>
                <h1 class="page-title mt-1">{{ $issue->title }}</h1>
            </div>
            @if ($isOwner)
                <div class="flex gap-2">
                    <x-secondary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-issue-{{ $issue->id }}')">{{ __('Edit') }}</x-secondary-button>
                    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'delete-issue-{{ $issue->id }}')">{{ __('Delete') }}</x-danger-button>
                </div>
            @endif
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
                    <dd class="mt-2 text-sm">
                        <x-issue-due-date :issue="$issue" detail />
                    </dd>
                </div>
                <div>
                    <dt class="section-title">{{ __('Project') }}</dt>
                    <dd class="mt-2 text-sm">
                        @if ($isOwner)
                            <a href="{{ route('projects.show', $issue->project) }}" class="link">{{ $issue->project->name }}</a>
                        @else
                            {{ $issue->project->name }}
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        <section class="panel p-5">
            <h2 class="section-title">{{ __('Description') }}</h2>
            <p class="mt-3 whitespace-pre-wrap leading-relaxed text-stone-800">{{ $issue->description ?: __('No description.') }}</p>
        </section>

        <section class="panel p-5">
            <h2 class="section-title">{{ __('Tags') }}</h2>
            @if ($isOwner)
                <div
                    id="issue-tags"
                    class="mt-2"
                    data-attach-url="{{ route('issues.tags.attach', ['issue' => $issue, 'tag' => '__TAG__']) }}"
                    data-detach-url="{{ route('issues.tags.detach', ['issue' => $issue, 'tag' => '__TAG__']) }}"
                >
                    <p class="meta">{{ __('Click to attach or remove.') }}</p>
                    <p data-tag-error class="mt-2 text-sm text-red-600 hidden"></p>

                    <div class="mt-5">
                        <h3 class="text-xs font-medium text-stone-500">{{ __('Attached') }}</h3>
                        <p data-empty-attached class="meta mt-2 {{ $hasAttachedTags ? 'hidden' : '' }}">{{ __('None') }}</p>
                        <div data-attached-tags class="mt-2 flex flex-wrap gap-2">
                            @foreach ($issue->tags as $tag)
                                <button
                                    type="button"
                                    data-action="detach"
                                    data-tag-id="{{ $tag->id }}"
                                    class="inline-flex items-center gap-1.5 border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-700 hover:border-stone-900"
                                    title="{{ __('Remove') }}"
                                >
                                    <span class="h-2 w-2 rounded-sm" style="background-color: {{ $tagPresenter->displayColor($tag) }}"></span>
                                    {{ $tag->name }} ×
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-xs font-medium text-stone-500">{{ __('Available') }}</h3>
                        @if (! $hasAnyTags)
                            <p class="meta mt-2">
                                {{ __('No tags yet.') }}
                                <a href="{{ route('tags.index') }}" class="link">{{ __('Create one') }}</a>
                            </p>
                        @else
                            <p data-empty-available class="meta mt-2 {{ $hasAvailableTags ? 'hidden' : '' }}">{{ __('All attached') }}</p>
                            <div data-available-tags class="mt-2 flex flex-wrap gap-2">
                                @foreach ($availableTags as $tag)
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
            @elseif (! $hasAttachedTags)
                <p class="meta mt-2">{{ __('None') }}</p>
            @else
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($issue->tags as $tag)
                        <x-tag-badge :name="$tag->name" :color="$tag->color" />
                    @endforeach
                </div>
            @endif
        </section>

        <section class="panel p-5">
            <h2 class="section-title">{{ __('Assignees') }}</h2>
            @if ($isOwner)
                <div
                    id="issue-assignees"
                    class="mt-2"
                    data-attach-url="{{ route('issues.assignees.attach', ['issue' => $issue, 'user' => '__USER__']) }}"
                    data-detach-url="{{ route('issues.assignees.detach', ['issue' => $issue, 'user' => '__USER__']) }}"
                >
                    <p class="meta">{{ __('Click to assign or remove team members.') }}</p>
                    <p data-assignee-error class="mt-2 text-sm text-red-600 hidden"></p>

                    <div class="mt-5">
                        <h3 class="text-xs font-medium text-stone-500">{{ __('Assigned') }}</h3>
                        <p data-empty-attached class="meta mt-2 {{ $hasAttachedAssignees ? 'hidden' : '' }}">{{ __('None') }}</p>
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
                        <p data-empty-available class="meta mt-2 {{ $hasAvailableUsers ? 'hidden' : '' }}">{{ __('Everyone is assigned') }}</p>
                        <div data-available-assignees class="mt-2 flex flex-wrap gap-2">
                            @foreach ($availableUsers as $user)
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
            @elseif (! $hasAttachedAssignees)
                <p class="meta mt-2">{{ __('None') }}</p>
            @else
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($issue->assignees as $assignee)
                        <span class="inline-flex items-center border border-stone-300 bg-white px-2.5 py-1 text-xs font-medium text-stone-700" title="{{ $assignee->email }}">{{ $assignee->name }}</span>
                    @endforeach
                </div>
            @endif
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
                        <x-text-input id="author_name" name="author_name" type="text" class="mt-1" :value="$commentAuthorName" required />
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

    @if ($isOwner)
        @include('issues.partials.edit-modal', ['issue' => $issue])
        @include('issues.partials.delete-modal', ['issue' => $issue])
    @endif
</x-app-layout>
