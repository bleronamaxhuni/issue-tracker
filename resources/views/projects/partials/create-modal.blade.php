<x-modal name="create-project" :show="session('open_modal') === 'create-project'" focusable>
    <form method="post" action="{{ route('projects.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('New Project') }}
        </h2>

        <div class="mt-6 space-y-6">
            @include('projects.partials.form-fields', ['project' => null])
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button>
                {{ __('Create Project') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
