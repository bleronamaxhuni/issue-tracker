<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="page-title">{{ __('Tags') }}</h1>
                <p class="page-subtitle">{{ __('Labels for organizing issues') }}</p>
            </div>
            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-tag')">{{ __('New tag') }}</x-primary-button>
        </div>
    </x-slot>

    <x-modal name="create-tag" :show="session('open_modal') === 'create-tag'" focusable>
        <form method="post" action="{{ route('tags.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-semibold tracking-tight text-stone-900">{{ __('New tag') }}</h2>
            <div class="mt-6 space-y-5">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="color" :value="__('Color')" />
                    <div class="mt-1 flex items-center gap-3">
                        <input
                            id="color-picker"
                            type="color"
                            value="{{ old('color', '#57534e') }}"
                            class="h-10 w-12 cursor-pointer rounded border border-stone-300 p-1"
                            oninput="document.getElementById('color').value = this.value"
                        />
                        <x-text-input id="color" name="color" type="text" :value="old('color', '#57534e')" placeholder="#57534e" />
                    </div>
                    <x-input-error class="mt-2" :messages="$errors->get('color')" />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Create') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-page-container>
        <x-flash-message />

        @if ($tags->isEmpty())
            <x-empty-state :title="__('No tags yet')" :description="__('Create labels like bug, feature, or urgent.')">
                <x-slot name="action">
                    <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-tag')">{{ __('Create tag') }}</x-primary-button>
                </x-slot>
            </x-empty-state>
        @else
            <ul class="panel divide-y divide-stone-200 px-5">
                @foreach ($tags as $tag)
                    <li class="list-row flex items-center justify-between gap-4">
                        <x-tag-badge :name="$tag->name" :color="$tag->color" class="text-sm" />
                        <span class="text-xs text-stone-500">
                            {{ trans_choice(':count issue|:count issues', $tag->issues_count, ['count' => $tag->issues_count]) }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-page-container>
</x-app-layout>
