@props(['priority'])

@php
    $classes = match ($priority) {
        'high' => 'bg-red-100 text-red-800',
        'medium' => 'bg-amber-100 text-amber-800',
        'low' => 'bg-blue-100 text-blue-800',
        default => 'bg-stone-100 text-stone-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize {$classes}"]) }}>
    {{ $priority }}
</span>
