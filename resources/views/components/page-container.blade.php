@props(['class' => ''])

<div {{ $attributes->merge(['class' => "space-y-8 {$class}"]) }}>
    {{ $slot }}
</div>
