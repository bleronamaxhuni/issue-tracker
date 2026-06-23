<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="page-title">{{ __('Issues') }}</h1>
            <p class="page-subtitle">{{ __('Filter and browse issues across projects') }}</p>
        </div>
    </x-slot>

    <x-page-container>
        <x-flash-message />

        <div id="issue-search" data-index-url="{{ route('issues.index') }}">
            <form data-filter-form class="panel grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2 lg:col-span-4">
                    <x-input-label for="search" :value="__('Search')" />
                    <x-text-input id="search" name="search" type="search" class="mt-1" :value="$filters['search'] ?? ''"
                        placeholder="{{ __('Search issues') }}" data-search-input autocomplete="off" />
                </div>
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" data-filter-select class="input mt-1">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\Models\Issue::STATUSES as $status)
                            <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                                {{ str_replace('_', ' ', $status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="priority" :value="__('Priority')" />
                    <select id="priority" name="priority" data-filter-select class="input mt-1">
                        <option value="">{{ __('All') }}</option>
                        @foreach (\App\Models\Issue::PRIORITIES as $priority)
                            <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ $priority }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="tag" :value="__('Tag')" />
                    <select id="tag" name="tag" data-filter-select class="input mt-1">
                        <option value="">{{ __('All') }}</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected((string) ($filters['tag'] ?? '') === (string) $tag->id)>{{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if (!empty($filters))
                    <div class="sm:col-span-2 lg:col-span-4">
                        <a href="{{ route('issues.index') }}"
                            class="text-sm text-stone-500 hover:text-stone-900">{{ __('Clear filters') }}</a>
                    </div>
                @endif
            </form>

            <section id="issue-results" class="mt-8" data-results>
                @include('issues._results', ['issues' => $issues, 'filters' => $filters])
            </section>

            <p data-search-status class="mt-2 hidden text-sm text-stone-500" aria-live="polite"></p>
        </div>
    </x-page-container>
</x-app-layout>
