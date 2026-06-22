@php
    $project = $project ?? null;
    $suffix = $project ? '-'.$project->id : '';
@endphp

<div>
    <x-input-label for="name{{ $suffix }}" :value="__('Name')" />
    <x-text-input
        id="name{{ $suffix }}"
        name="name"
        type="text"
        class="mt-1 block w-full"
        :value="old('name', $project?->name)"
        required
        autofocus
    />
    <x-input-error class="mt-2" :messages="$errors->get('name')" />
</div>

<div>
    <x-input-label for="description{{ $suffix }}" :value="__('Description')" />
    <textarea
        id="description{{ $suffix }}"
        name="description"
        rows="4"
        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    >{{ old('description', $project?->description) }}</textarea>
    <x-input-error class="mt-2" :messages="$errors->get('description')" />
</div>
