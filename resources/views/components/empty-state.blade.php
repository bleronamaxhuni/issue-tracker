@props(['title', 'description' => null])

<div {{ $attributes->merge(['class' => 'border border-dashed border-stone-300 px-6 py-12 text-center']) }}>
    <p class="font-medium text-stone-900">{{ $title }}</p>
    @if ($description)
        <p class="mt-2 text-sm text-stone-500">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-5">{{ $action }}</div>
    @endisset
</div>
