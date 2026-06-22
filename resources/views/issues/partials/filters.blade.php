<form method="get" action="{{ route('issues.index') }}" class="flex flex-wrap items-end gap-4">
    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select
            id="status"
            name="status"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        >
            <option value="">{{ __('All') }}</option>
            @foreach (\App\Models\Issue::STATUSES as $status)
                <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>
                    {{ str_replace('_', ' ', $status) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="priority" :value="__('Priority')" />
        <select
            id="priority"
            name="priority"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        >
            <option value="">{{ __('All') }}</option>
            @foreach (\App\Models\Issue::PRIORITIES as $priority)
                <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>
                    {{ $priority }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <x-input-label for="tag" :value="__('Tag')" />
        <select
            id="tag"
            name="tag"
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        >
            <option value="">{{ __('All') }}</option>
            @foreach ($tags as $tag)
                <option value="{{ $tag->id }}" @selected((string) ($filters['tag'] ?? '') === (string) $tag->id)>
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>
    </div>

    <x-primary-button>{{ __('Filter') }}</x-primary-button>

    @if ($filters)
        <a href="{{ route('issues.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
            {{ __('Clear') }}
        </a>
    @endif
</form>
