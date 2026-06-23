@props(['issue', 'detail' => false])

@if ($issue->due_date)
    <span class="text-xs {{ $detail ? $issuePresenter->dueDateDisplayClass($issue) : $issuePresenter->dueDateTextClass($issue) }}">
        {{ $issue->due_date->format('M j, Y') }}
        @if ($detail && $issue->isOverdue())
            <span class="ml-2 text-xs">({{ __('overdue') }})</span>
        @endif
    </span>
@elseif ($detail)
    <span class="text-stone-400">{{ __('Not set') }}</span>
@endif
