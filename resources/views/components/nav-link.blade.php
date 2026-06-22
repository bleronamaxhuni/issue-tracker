@props(['active'])

@php
$classes = ($active ?? false)
    ? 'bg-stone-800 text-white'
    : 'text-stone-400 hover:bg-stone-900 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => "block rounded-md px-3 py-2 text-sm font-medium transition-colors {$classes}"]) }}>
    {{ $slot }}
</a>
