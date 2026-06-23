<div class="space-y-5">
    <div>
        <x-input-label for="{{ $fieldPrefix }}name" :value="__('Name')" />
        <x-text-input id="{{ $fieldPrefix }}name" name="name" type="text" class="mt-1" :value="old('name', $project->name)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('name')" />
    </div>
    <div>
        <x-input-label for="{{ $fieldPrefix }}description" :value="__('Description')" />
        <textarea id="{{ $fieldPrefix }}description" name="description" rows="4" class="input mt-1">{{ old('description', $project->description) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('description')" />
    </div>
    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <x-input-label for="{{ $fieldPrefix }}start_date" :value="__('Start date')" />
            <x-text-input id="{{ $fieldPrefix }}start_date" name="start_date" type="date" class="mt-1" :value="old('start_date', $project->start_date?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
        </div>
        <div>
            <x-input-label for="{{ $fieldPrefix }}deadline" :value="__('Deadline')" />
            <x-text-input id="{{ $fieldPrefix }}deadline" name="deadline" type="date" class="mt-1" :value="old('deadline', $project->deadline?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
        </div>
    </div>
</div>
