@props(['status'])

@php
    $dot = match ($status) {
        'open' => 'bg-sky-500',
        'in_progress' => 'bg-amber-500',
        'closed' => 'bg-emerald-500',
        default => 'bg-stone-400',
    };
    $label = $issueStatusLabels[$status] ?? str_replace('_', ' ', $status);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 text-xs font-medium text-stone-600']) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
    {{ $label }}
</span>
