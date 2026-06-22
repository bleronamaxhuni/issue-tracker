<span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->statusLabel() }}</span>
<span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->priority }}</span>
@if ($issue->due_date)
    <span class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">{{ $issue->due_date->format('M j, Y') }}</span>
@endif
