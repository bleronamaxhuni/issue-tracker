@php
    $issue = $issue ?? null;
    $suffix = $issue ? '-'.$issue->id : '';
@endphp

<div>
    <x-input-label for="title{{ $suffix }}" :value="__('Title')" />
    <x-text-input
        id="title{{ $suffix }}"
        name="title"
        type="text"
        class="mt-1 block w-full"
        :value="old('title', $issue?->title)"
        required
        autofocus
    />
    <x-input-error class="mt-2" :messages="$errors->get('title')" />
</div>

<div>
    <x-input-label for="description{{ $suffix }}" :value="__('Description')" />
    <textarea
        id="description{{ $suffix }}"
        name="description"
        rows="4"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >{{ old('description', $issue?->description) }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>

<div>
    <x-input-label for="status{{ $suffix }}" :value="__('Status')" />
    <select
        id="status{{ $suffix }}"
        name="status"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        required
    >
        @foreach (\App\Models\Issue::STATUSES as $status)
            <option value="{{ $status }}" @selected(old('status', $issue?->status ?? 'open') === $status)>
                {{ str_replace('_', ' ', $status) }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('status')" />
</div>

<div>
    <x-input-label for="priority{{ $suffix }}" :value="__('Priority')" />
    <select
        id="priority{{ $suffix }}"
        name="priority"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        required
    >
        @foreach (\App\Models\Issue::PRIORITIES as $priority)
            <option value="{{ $priority }}" @selected(old('priority', $issue?->priority ?? 'medium') === $priority)>
                {{ $priority }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('priority')" />
</div>

<div>
    <x-input-label for="due_date{{ $suffix }}" :value="__('Due date')" />
    <x-text-input
        id="due_date{{ $suffix }}"
        name="due_date"
        type="date"
        class="mt-1 block w-full"
        :value="old('due_date', $issue?->due_date?->format('Y-m-d'))"
    />
    <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
</div>
