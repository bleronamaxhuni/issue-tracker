@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block py-2 text-stone-900'
    : 'block py-2 text-stone-500 hover:text-stone-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
