@php
    $messages = [
        'issue-created' => __('Issue created successfully.'),
        'issue-updated' => __('Issue updated successfully.'),
        'issue-deleted' => __('Issue deleted successfully.'),
    ];
@endphp

@if ($message = $messages[session('status')] ?? null)
    <div class="mb-4 text-sm font-medium text-green-600">
        {{ $message }}
    </div>
@endif
