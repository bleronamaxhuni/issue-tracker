<div class="space-y-5">
    <div>
        <x-input-label for="title-{{ $fieldPrefix }}" :value="__('Title')" />
        <x-text-input id="title-{{ $fieldPrefix }}" name="title" type="text" class="mt-1" :value="old('title', $issue->title)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>
    <div>
        <x-input-label for="description-{{ $fieldPrefix }}" :value="__('Description')" />
        <textarea id="description-{{ $fieldPrefix }}" name="description" rows="4" class="input mt-1">{{ old('description', $issue->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <x-input-label for="status-{{ $fieldPrefix }}" :value="__('Status')" />
            <select id="status-{{ $fieldPrefix }}" name="status" class="input mt-1" required>
                @foreach ($issueStatuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $issue->status ?? 'open') === $status)>{{ $issueStatusLabels[$status] }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>
        <div>
            <x-input-label for="priority-{{ $fieldPrefix }}" :value="__('Priority')" />
            <select id="priority-{{ $fieldPrefix }}" name="priority" class="input mt-1" required>
                @foreach ($issuePriorities as $priority)
                    <option value="{{ $priority }}" @selected(old('priority', $issue->priority ?? 'medium') === $priority)>{{ $priority }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('priority')" />
        </div>
    </div>
    <div>
        <x-input-label for="due_date-{{ $fieldPrefix }}" :value="__('Due date')" />
        <x-text-input id="due_date-{{ $fieldPrefix }}" name="due_date" type="date" class="mt-1" :value="old('due_date', $issue->due_date?->format('Y-m-d'))" />
        <x-input-error class="mt-2" :messages="$errors->get('due_date')" />
    </div>
</div>
