@php
    $messages = [
        'project-created' => __('Project created successfully.'),
        'project-updated' => __('Project updated successfully.'),
        'project-deleted' => __('Project deleted successfully.'),
        'issue-created' => __('Issue created successfully.'),
        'issue-updated' => __('Issue updated successfully.'),
        'issue-deleted' => __('Issue deleted successfully.'),
        'tag-created' => __('Tag created successfully.'),
    ];

    $status = session('status');
    $message = $status ? ($messages[$status] ?? null) : null;
@endphp

@if ($message)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="mb-6 flex items-center justify-between gap-4 border border-stone-300 bg-stone-100 px-4 py-3 text-sm text-stone-800"
        role="alert"
    >
        <span>{{ $message }}</span>
        <button type="button" @click="show = false" class="shrink-0 text-stone-500 hover:text-stone-900" aria-label="{{ __('Dismiss') }}">×</button>
    </div>
@endif
