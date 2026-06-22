<div
    x-show="sidebarOpen"
    x-transition:enter="transition-opacity ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="sidebarOpen = false"
    class="fixed inset-0 z-40 bg-stone-950/60 lg:hidden"
    style="display: none;"
></div>

<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-50 flex w-60 flex-col border-r border-stone-800 bg-stone-950 transition-transform duration-200 lg:static lg:translate-x-0"
>
    <div class="flex h-16 items-center justify-between border-b border-stone-800 px-5">
        <a href="{{ route('dashboard') }}" class="text-sm font-semibold tracking-tight text-white">
            {{ config('app.name', 'Issue Tracker') }}
        </a>
        <button
            type="button"
            @click="sidebarOpen = false"
            class="rounded p-1 text-stone-500 hover:text-white lg:hidden"
            aria-label="{{ __('Close menu') }}"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-5">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            {{ __('Dashboard') }}
        </x-nav-link>
        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
            {{ __('Projects') }}
        </x-nav-link>
        <x-nav-link :href="route('issues.index')" :active="request()->routeIs('issues.*')">
            {{ __('Issues') }}
        </x-nav-link>
        <x-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')">
            {{ __('Tags') }}
        </x-nav-link>
    </nav>

    <div class="border-t border-stone-800 p-4">
        <p class="truncate text-sm font-medium text-white">{{ Auth::user()->name }}</p>
        <p class="truncate text-xs text-stone-500">{{ Auth::user()->email }}</p>
        <div class="mt-3 flex flex-col gap-1 text-sm">
            <a href="{{ route('profile.edit') }}" class="text-stone-400 hover:text-white">{{ __('Profile') }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-stone-400 hover:text-white">{{ __('Log out') }}</button>
            </form>
        </div>
    </div>
</aside>
