<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
