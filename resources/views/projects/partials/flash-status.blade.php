@php
    $messages = [
        'project-created' => __('Project created successfully.'),
        'project-updated' => __('Project updated successfully.'),
        'project-deleted' => __('Project deleted successfully.'),
    ];
@endphp

@if ($message = $messages[session('status')] ?? null)
    <div class="mb-4 text-sm font-medium text-green-600">
        {{ $message }}
    </div>
@endif
