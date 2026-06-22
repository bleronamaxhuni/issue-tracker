<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded bg-stone-900 px-3 py-1.5 text-sm font-medium text-white hover:bg-stone-800 focus:outline-none focus:ring-2 focus:ring-stone-900 focus:ring-offset-2 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
