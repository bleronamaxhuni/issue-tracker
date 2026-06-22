<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Tags') }}</h2>
            <x-primary-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-tag')">{{ __('New Tag') }}</x-primary-button>
        </div>
    </x-slot>

    <x-modal name="create-tag" :show="session('open_modal') === 'create-tag'" focusable>
        <form method="post" action="{{ route('tags.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-gray-900">{{ __('New Tag') }}</h2>
            <div class="mt-6 space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div>
                    <x-input-label for="color" :value="__('Color')" />
                    <x-text-input id="color" name="color" type="text" class="mt-1 block w-full" :value="old('color')" placeholder="#6366f1" />
                    <p class="mt-1 text-xs text-gray-500">{{ __('Optional hex color, e.g. #6366f1') }}</p>
                    <x-input-error class="mt-2" :messages="$errors->get('color')" />
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                <x-primary-button>{{ __('Create Tag') }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') === 'tag-created')
                <div class="mb-4 text-sm font-medium text-green-600">{{ __('Tag created successfully.') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($tags->isEmpty())
                        <p class="text-gray-500">{{ __('No tags yet.') }}</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($tags as $tag)
                                <li class="py-4 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="inline-block h-4 w-4 rounded-full"
                                            style="background-color: {{ $tag->color ?? '#6b7280' }}"
                                        ></span>
                                        <span class="font-medium text-gray-900">{{ $tag->name }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">
                                        {{ trans_choice(':count issue|:count issues', $tag->issues_count, ['count' => $tag->issues_count]) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
