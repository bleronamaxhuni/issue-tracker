<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded border border-stone-300 px-3 py-1.5 text-sm font-medium text-stone-700 hover:bg-stone-50 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
