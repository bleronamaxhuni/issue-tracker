@if ($flashMessage)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        class="mb-6 flex items-center justify-between gap-4 border border-stone-300 bg-stone-100 px-4 py-3 text-sm text-stone-800"
        role="alert"
    >
        <span>{{ $flashMessage }}</span>
        <button type="button" @click="show = false" class="shrink-0 text-stone-500 hover:text-stone-900" aria-label="{{ __('Dismiss') }}">×</button>
    </div>
@endif
