<div class="mb-4 flex items-baseline justify-between gap-4">
    <h2 class="section-title">{{ __('Results') }}</h2>
    <span class="text-xs text-stone-500" data-issue-count>{{ trans_choice(':count issue|:count issues', $issues->total(), ['count' => $issues->total()]) }}</span>
</div>

@if ($issues->isEmpty())
    <x-empty-state
        :title="__('No issues found')"
        :description="$hasActiveFilters ? __('Try different filters or search terms.') : __('Create an issue from a project page.')"
    >
        @unless ($hasActiveFilters)
            <x-slot name="action">
                <x-primary-button onclick="window.location='{{ route('projects.index') }}'">{{ __('Go to projects') }}</x-primary-button>
            </x-slot>
        @endunless
    </x-empty-state>
@else
    <ul class="panel divide-y divide-stone-200 px-5">
        @foreach ($issues as $issue)
            <li class="list-row">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('issues.show', $issue) }}" class="link !no-underline hover:underline">{{ $issue->title }}</a>
                        <p class="meta mt-1">
                            @if ($issue->is_owned)
                                <a href="{{ route('projects.show', $issue->project) }}" class="hover:text-stone-900">{{ $issue->project->name }}</a>
                            @else
                                {{ $issue->project->name }}
                                <span class="ml-2 text-xs text-stone-400">({{ __('assigned to you') }})</span>
                            @endif
                        </p>
                        @if ($excerpt = $issuePresenter->excerpt($issue))
                            <p class="meta mt-2">{{ $excerpt }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap items-center gap-3">
                            <x-issue-status-badge :status="$issue->status" />
                            <x-issue-priority-badge :priority="$issue->priority" />
                            <x-issue-due-date :issue="$issue" />
                            @foreach ($issue->tags as $tag)
                                <x-tag-badge :name="$tag->name" :color="$tag->color" />
                            @endforeach
                        </div>
                    </div>
                    @if ($issue->is_owned)
                        <div class="flex shrink-0 gap-3 text-sm">
                            <button type="button" data-open-modal="edit-issue-{{ $issue->id }}" class="text-stone-500 hover:text-stone-900">{{ __('Edit') }}</button>
                            <button type="button" data-open-modal="delete-issue-{{ $issue->id }}" class="text-red-600 hover:text-red-700">{{ __('Delete') }}</button>
                        </div>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

    <div class="mt-4">
        {{ $issues->links() }}
    </div>
@endif

@foreach ($issues as $issue)
    @if ($issue->is_owned)
        @include('issues.partials.edit-modal', ['issue' => $issue])
        @include('issues.partials.delete-modal', ['issue' => $issue])
    @endif
@endforeach
