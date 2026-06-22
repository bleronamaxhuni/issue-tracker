@props(['name', 'color' => null])

<span
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 text-xs font-medium text-stone-700']) }}
>
    <span class="h-2 w-2 rounded-sm" style="background-color: {{ $color ?? '#78716c' }}"></span>
    {{ $name }}
</span>
