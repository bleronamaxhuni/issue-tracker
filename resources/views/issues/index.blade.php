<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">{{ __('Issues') }}</h1>
            <p class="page-subtitle">{{ __('Filter and browse issues across projects') }}</p>
        </div>
    </x-slot>

    <x-page-container>
        <x-flash-message />

        <form method="get" action="{{ route('issues.index') }}" class="panel grid gap-4 p-5 sm:grid-cols-3">
            <div>
                <x-input-label for="status" :value="__('Status')" />
                <select id="status" name="status" onchange="this.form.submit()" class="input mt-1">
                    <option value="">{{ __('All') }}</option>
                    @foreach (\App\Models\Issue::STATUSES as $status)
                        <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="priority" :value="__('Priority')" />
                <select id="priority" name="priority" onchange="this.form.submit()" class="input mt-1">
                    <option value="">{{ __('All') }}</option>
                    @foreach (\App\Models\Issue::PRIORITIES as $priority)
                        <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="tag" :value="__('Tag')" />
                <select id="tag" name="tag" onchange="this.form.submit()" class="input mt-1">
                    <option value="">{{ __('All') }}</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected((string) ($filters['tag'] ?? '') === (string) $tag->id)>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($filters)
                <div class="sm:col-span-3">
                    <a href="{{ route('issues.index') }}" class="text-sm text-stone-500 hover:text-stone-900">{{ __('Clear filters') }}</a>
                </div>
            @endif
        </form>

        <section>
            <div class="mb-4 flex items-baseline justify-between gap-4">
                <h2 class="section-title">{{ __('Results') }}</h2>
                <span class="text-xs text-stone-500">{{ trans_choice(':count issue|:count issues', $issues->count(), ['count' => $issues->count()]) }}</span>
            </div>

            @if ($issues->isEmpty())
                <x-empty-state
                    :title="__('No issues found')"
                    :description="$filters ? __('Try different filters.') : __('Create an issue from a project page.')"
                >
                    @if (! $filters)
                        <x-slot name="action">
                            <x-primary-button onclick="window.location='{{ route('projects.index') }}'">{{ __('Go to projects') }}</x-primary-button>
                        </x-slot>
                    @endif
                </x-empty-state>
            @else
                <ul class="panel divide-y divide-stone-200 px-5">
                    @foreach ($issues as $issue)
                        <li class="list-row">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('issues.show', $issue) }}" class="link !no-underline hover:underline">{{ $issue->title }}</a>
                                    <p class="meta mt-1">
                                        <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-stone-900">{{ $issue->project->name }}</a>
                                    </p>
                                    @if ($issue->description)
                                        <p class="meta mt-2">{{ Str::limit($issue->description, 140) }}</p>
                                    @endif
                                    <div class="mt-3 flex flex-wrap items-center gap-3">
                                        <x-issue-status-badge :status="$issue->status" />
                                        <x-issue-priority-badge :priority="$issue->priority" />
                                        @if ($issue->due_date)
                                            <span class="text-xs {{ $issue->isOverdue() ? 'font-medium text-red-600' : 'text-stone-500' }}">{{ $issue->due_date->format('M j, Y') }}</span>
                                        @endif
                                        @foreach ($issue->tags as $tag)
                                            <x-tag-badge :name="$tag->name" :color="$tag->color" />
                                        @endforeach
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

    @foreach ($issues as $issue)
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
